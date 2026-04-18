<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\AiStrategy;
use App\Models\Wallet;

class AIStrategyController extends Controller
{
    private function isAdminUser($user)
    {
        if (isset($user->is_admin) && (int) $user->is_admin === 1) {
            return true;
        }

        if (isset($user->email) && $user->email === 'admin@test.com') {
            return true;
        }

        return false;
    }

    private function getUserWallet($user)
    {
        return Wallet::where('user_name', $user->name)->first();
    }

    private function getStrategyMaxSeconds($strategy)
    {
        if ($strategy->started_at && $strategy->unlock_at) {
            $startedAt = Carbon::parse($strategy->started_at);
            $unlockAt = Carbon::parse($strategy->unlock_at);

            $seconds = $startedAt->diffInSeconds($unlockAt, false);

            if ($seconds > 0) {
                return $seconds;
            }
        }

        return (int) $strategy->lock_hours * 3600;
    }

    private function refreshStrategyProfit($strategy)
    {
        if ($strategy->status !== 'executing') {
            return $strategy;
        }

        if (!$strategy->started_at) {
            $strategy->started_at = now();
        }

        if (!$strategy->unlock_at) {
            $strategy->unlock_at = Carbon::parse($strategy->started_at)->copy()->addHours((int) $strategy->lock_hours);
        }

        $startedAt = Carbon::parse($strategy->started_at);
        $now = now();

        $elapsedSeconds = $startedAt->diffInSeconds($now, false);

        if ($elapsedSeconds < 0) {
            $elapsedSeconds = 0;
        }

        $maxSeconds = $this->getStrategyMaxSeconds($strategy);

        if ($elapsedSeconds > $maxSeconds) {
            $elapsedSeconds = $maxSeconds;
        }

        $dailyProfit = ((float) $strategy->amount * (float) $strategy->target_percent) / 100;
        $profitPerSecond = $dailyProfit / 86400;
        $currentProfit = $profitPerSecond * $elapsedSeconds;

        if ($currentProfit < 0) {
            $currentProfit = 0;
        }

        $strategy->current_profit = round($currentProfit, 2);
        $strategy->save();

        return $strategy;
    }

    private function closeRedeemIfReady($strategy, $wallet)
    {
        if (
            $strategy->status === 'redeem_pending' &&
            $strategy->redeem_available_at &&
            now()->gte($strategy->redeem_available_at)
        ) {
            if ($wallet) {
                $wallet->balance += round(((float) $strategy->amount + (float) $strategy->current_profit), 2);
                $wallet->save();
            }

            $strategy->status = 'closed';
            $strategy->closed_at = now();
            $strategy->save();
        }

        return $strategy;
    }

    public function index()
    {
        $user = auth()->user();
        $wallet = $this->getUserWallet($user);

        $allStrategies = AiStrategy::where('user_name', $user->name)
            ->latest()
            ->get();

        foreach ($allStrategies as $strategy) {
            $this->refreshStrategyProfit($strategy);
            $this->closeRedeemIfReady($strategy, $wallet);
        }

        $strategies = AiStrategy::where('user_name', $user->name)
            ->latest()
            ->get();

        return view('ai', compact('wallet', 'strategies', 'user'));
    }

    public function live()
    {
        $user = auth()->user();
        $wallet = $this->getUserWallet($user);

        $strategies = AiStrategy::where('user_name', $user->name)
            ->latest()
            ->get();

        $result = [];

        foreach ($strategies as $strategy) {
            $this->refreshStrategyProfit($strategy);
            $this->closeRedeemIfReady($strategy, $wallet);

            $secondsLeft = null;
            if (
                $strategy->status === 'executing' &&
                $strategy->unlock_at
            ) {
                $secondsLeft = now()->lt($strategy->unlock_at)
                    ? now()->diffInSeconds($strategy->unlock_at)
                    : 0;
            }

            $redeemSecondsLeft = null;
            if (
                $strategy->status === 'redeem_pending' &&
                $strategy->redeem_available_at
            ) {
                $redeemSecondsLeft = now()->lt($strategy->redeem_available_at)
                    ? now()->diffInSeconds($strategy->redeem_available_at)
                    : 0;
            }

            $result[] = [
                'id' => $strategy->id,
                'status' => $strategy->status,
                'amount' => number_format((float) $strategy->amount, 2, '.', ''),
                'current_profit' => number_format((float) $strategy->current_profit, 2, '.', ''),
                'total_value' => number_format(round((float) $strategy->amount + (float) $strategy->current_profit, 2), 2, '.', ''),
                'seconds_left' => $secondsLeft,
                'redeem_seconds_left' => $redeemSecondsLeft,
                'can_redeem' => $strategy->status === 'executing' && $secondsLeft === 0,
            ];
        }

        $walletBalance = $wallet ? number_format((float) $wallet->balance, 2, '.', '') : '0.00';

        return response()->json([
            'wallet_balance' => $walletBalance,
            'strategies' => $result,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $wallet = $this->getUserWallet($user);

        $activeStrategy = AiStrategy::where('user_name', $user->name)
            ->whereIn('status', ['executing', 'redeem_pending'])
            ->first();

        if ($activeStrategy) {
            return redirect('/ai')->with('error', 'You can only run one AI strategy at a time.');
        }

        $isAdmin = $this->isAdminUser($user);

        if (!$isAdmin) {
            $lastCancelled = AiStrategy::where('user_name', $user->name)
                ->where('status', 'cancelled')
                ->whereNotNull('closed_at')
                ->latest('closed_at')
                ->first();

            if ($lastCancelled) {
                $cooldownUntil = Carbon::parse($lastCancelled->closed_at)->addHours(48);

                if (now()->lt($cooldownUntil)) {
                    $hoursLeft = (int) ceil(now()->diffInMinutes($cooldownUntil) / 60);

                    return redirect('/ai')->with(
                        'error',
                        'After cancelling a strategy, you must wait 48 hours before starting a new one. Remaining: ' . $hoursLeft . ' hour(s).'
                    );
                }
            }
        }

        return view('ai-create', compact('wallet'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $wallet = $this->getUserWallet($user);

        if (!$wallet) {
            return redirect('/ai/create')->with('error', 'Wallet not found.');
        }

        $amount = (float) $request->amount;
        $strategy = $request->strategy;

        if ($amount < 1000) {
            return redirect('/ai/create')->with('error', 'Minimum investment for AI trading is $1000.');
        }

        $activeStrategy = AiStrategy::where('user_name', $user->name)
            ->whereIn('status', ['executing', 'redeem_pending'])
            ->first();

        if ($activeStrategy) {
            return redirect('/ai')->with('error', 'Only one AI strategy is allowed at a time.');
        }

        $isAdmin = $this->isAdminUser($user);

        if (!$isAdmin) {
            $lastCancelled = AiStrategy::where('user_name', $user->name)
                ->where('status', 'cancelled')
                ->whereNotNull('closed_at')
                ->latest('closed_at')
                ->first();

            if ($lastCancelled) {
                $cooldownUntil = Carbon::parse($lastCancelled->closed_at)->addHours(48);

                if (now()->lt($cooldownUntil)) {
                    $hoursLeft = (int) ceil(now()->diffInMinutes($cooldownUntil) / 60);

                    return redirect('/ai')->with(
                        'error',
                        'After cancelling a strategy, you must wait 48 hours before starting a new one. Remaining: ' . $hoursLeft . ' hour(s).'
                    );
                }
            }
        }

        if ((float) $wallet->balance < $amount) {
            return redirect('/ai/create')->with('error', 'Insufficient balance.');
        }

        $plans = [
            'starter' => [
                'strategy_name' => 'Starter AI',
                'target_percent' => 1.2,
                'lock_hours' => 72,
                'risk_level' => 'low',
            ],
            'pro' => [
                'strategy_name' => 'Pro AI',
                'target_percent' => 1.8,
                'lock_hours' => 216,
                'risk_level' => 'medium',
            ],
            'advanced' => [
                'strategy_name' => 'Advanced AI',
                'target_percent' => 3.0,
                'lock_hours' => 576,
                'risk_level' => 'high',
            ],
        ];

        if (!isset($plans[$strategy])) {
            return redirect('/ai/create')->with('error', 'Invalid strategy.');
        }

        $pairs = [
            'BTC/USDT',
            'ETH/USDT',
            'SOL/USDT',
            'ADA/USDT',
            'XRP/USDT',
            'LINK/USDT',
        ];

        $selectedPair = $pairs[array_rand($pairs)];
        $plan = $plans[$strategy];

        $wallet->balance = round((float) $wallet->balance - $amount, 2);
        $wallet->save();

        $startedAt = now();
        $unlockAt = $startedAt->copy()->addHours((int) $plan['lock_hours']);

        $created = new AiStrategy();
        $created->user_name = $user->name;
        $created->strategy_name = $plan['strategy_name'];

        // مهم: بعض قواعد البيانات عندك تطلب pair
        $created->pair = $selectedPair;

        // ونحفظ target_pair أيضًا إذا كان العمود موجودًا ويُستخدم في الواجهة
        $created->target_pair = $selectedPair;

       $created->amount = round($amount, 2);

$created->target_percent = $plan['target_percent'];
$created->percent = $plan['target_percent']; // ← هذا هو الحل

$created->lock_hours = $plan['lock_hours'];
$created->risk_level = $plan['risk_level'];
        $created->status = 'executing';
        $created->order_no = 'AI-' . strtoupper(Str::random(10));
        $created->current_profit = 0;
        $created->started_at = $startedAt;
        $created->unlock_at = $unlockAt;
        $created->redeem_requested_at = null;
        $created->redeem_available_at = null;
        $created->closed_at = null;

        $saved = $created->save();

        if (!$saved) {
            $wallet->balance = round((float) $wallet->balance + $amount, 2);
            $wallet->save();

            return redirect('/ai/create')->with('error', 'Strategy could not be created.');
        }

        return redirect('/ai')->with('success', 'AI strategy created successfully.');
    }

    public function redeem($id)
    {
        $strategy = AiStrategy::findOrFail($id);
        $user = auth()->user();

        if ($strategy->user_name !== $user->name) {
            return redirect('/ai')->with('error', 'Unauthorized.');
        }

        if ($strategy->status === 'closed') {
            return redirect('/ai')->with('error', 'Strategy already closed.');
        }

        if ($strategy->status === 'cancelled') {
            return redirect('/ai')->with('error', 'Strategy already cancelled.');
        }

        if ($strategy->status === 'redeem_pending') {
            return redirect('/ai')->with('error', 'Redeem already requested. Please wait 24 hours.');
        }

        if ($strategy->unlock_at && now()->lt($strategy->unlock_at)) {
            $hoursLeft = (int) ceil(now()->diffInMinutes($strategy->unlock_at) / 60);
            return redirect('/ai')->with('error', 'Redeem available after ' . $hoursLeft . ' hour(s).');
        }

        $strategy = $this->refreshStrategyProfit($strategy);

        $strategy->status = 'redeem_pending';
        $strategy->redeem_requested_at = now();
        $strategy->redeem_available_at = now()->addDay();
        $strategy->save();

        return redirect('/ai')->with('success', 'Redeem requested successfully. Funds will be credited after 24 hours.');
    }

    public function cancel($id)
    {
        $strategy = AiStrategy::findOrFail($id);
        $user = auth()->user();

        if ($strategy->user_name !== $user->name) {
            return redirect('/ai')->with('error', 'Unauthorized.');
        }

        if (in_array($strategy->status, ['closed', 'cancelled'])) {
            return redirect('/ai')->with('error', 'This strategy cannot be cancelled.');
        }

        $wallet = $this->getUserWallet($user);

        if (!$wallet) {
            return redirect('/ai')->with('error', 'Wallet not found.');
        }

        $wallet->balance = round((float) $wallet->balance + (float) $strategy->amount, 2);
        $wallet->save();

        $strategy->current_profit = 0;
        $strategy->status = 'cancelled';
        $strategy->redeem_requested_at = null;
        $strategy->redeem_available_at = null;
        $strategy->closed_at = now();
        $strategy->save();

        if ($this->isAdminUser($user)) {
            return redirect('/ai')->with('success', 'Strategy cancelled. Principal returned. Admin bypass is active, so no 48-hour wait is applied.');
        }

        return redirect('/ai')->with('success', 'Strategy cancelled. Principal returned, profits were removed. New AI strategy can be started after 48 hours.');
    }
}