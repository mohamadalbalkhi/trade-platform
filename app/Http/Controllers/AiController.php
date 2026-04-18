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

    public function index()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        $allStrategies = AiStrategy::where(function ($q) use ($user) {
                $q->where('user_name', $user->name)
                  ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->get();

        foreach ($allStrategies as $strategy) {

            if ($strategy->status === 'executing') {
                $elapsedHours = now()->diffInHours($strategy->started_at);

                if ($elapsedHours > $strategy->lock_hours) {
                    $elapsedHours = $strategy->lock_hours;
                }

                $dailyProfit = ($strategy->amount * $strategy->target_percent) / 100;
                $hourlyProfit = $dailyProfit / 24;
                $currentProfit = $hourlyProfit * $elapsedHours;

                $strategy->current_profit = round($currentProfit, 2);
                $strategy->save();
            }

            if (
                $strategy->status === 'redeem_pending' &&
                $strategy->redeem_available_at &&
                now()->gte($strategy->redeem_available_at)
            ) {
                if ($wallet) {
                    $wallet->balance += ($strategy->amount + $strategy->current_profit);
                    $wallet->save();
                }

                $strategy->status = 'closed';
                $strategy->closed_at = now();
                $strategy->save();
            }
        }

        $strategies = AiStrategy::where(function ($q) use ($user) {
                $q->where('user_name', $user->name)
                  ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('ai', compact('wallet', 'strategies', 'user'));
    }

    public function live()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        $strategies = AiStrategy::where(function ($q) use ($user) {
                $q->where('user_name', $user->name)
                  ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->get();

        $result = [];

        foreach ($strategies as $strategy) {

            if ($strategy->status === 'executing') {
                $elapsedHours = now()->diffInHours($strategy->started_at);

                if ($elapsedHours > $strategy->lock_hours) {
                    $elapsedHours = $strategy->lock_hours;
                }

                $dailyProfit = ($strategy->amount * $strategy->target_percent) / 100;
                $hourlyProfit = $dailyProfit / 24;
                $currentProfit = $hourlyProfit * $elapsedHours;

                $strategy->current_profit = round($currentProfit, 2);
                $strategy->save();
            }

            if (
                $strategy->status === 'redeem_pending' &&
                $strategy->redeem_available_at &&
                now()->gte($strategy->redeem_available_at)
            ) {
                if ($wallet) {
                    $wallet->balance += ($strategy->amount + $strategy->current_profit);
                    $wallet->save();
                }

                $strategy->status = 'closed';
                $strategy->closed_at = now();
                $strategy->save();
            }

            $hoursLeft = null;
            if (
                $strategy->status === 'executing' &&
                $strategy->unlock_at &&
                now()->lt($strategy->unlock_at)
            ) {
                $hoursLeft = (int) ceil(now()->diffInMinutes($strategy->unlock_at) / 60);
            }

            $redeemHoursLeft = null;
            if (
                $strategy->status === 'redeem_pending' &&
                $strategy->redeem_available_at &&
                now()->lt($strategy->redeem_available_at)
            ) {
                $redeemHoursLeft = (int) ceil(now()->diffInMinutes($strategy->redeem_available_at) / 60);
            }

            $result[] = [
                'id' => $strategy->id,
                'status' => $strategy->status,
                'current_profit' => number_format($strategy->current_profit, 2, '.', ''),
                'hours_left' => $hoursLeft,
                'redeem_hours_left' => $redeemHoursLeft,
            ];
        }

        $walletBalance = $wallet ? number_format($wallet->balance, 2, '.', '') : '0.00';

        return response()->json([
            'wallet_balance' => $walletBalance,
            'strategies' => $result,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        $activeStrategy = AiStrategy::where(function ($q) use ($user) {
                $q->where('user_name', $user->name)
                  ->orWhere('user_id', $user->id);
            })
            ->whereIn('status', ['executing', 'redeem_pending'])
            ->first();

        if ($activeStrategy) {
            return redirect('/ai')->with('error', 'You can only run one AI strategy at a time.');
        }

        $isAdmin = $this->isAdminUser($user);

        if (!$isAdmin) {
            $lastCancelled = AiStrategy::where(function ($q) use ($user) {
                    $q->where('user_name', $user->name)
                      ->orWhere('user_id', $user->id);
                })
                ->where('status', 'cancelled')
                ->whereNotNull('closed_at')
                ->latest('closed_at')
                ->first();

            if ($lastCancelled) {
                $cooldownUntil = Carbon::parse($lastCancelled->closed_at)->addHours(48);

                if (now()->lt($cooldownUntil)) {
                    $hoursLeft = (int) ceil(now()->diffInMinutes($cooldownUntil) / 60);
                    return redirect('/ai')->with('error', 'After cancelling a strategy, you must wait 48 hours before starting a new one. Remaining: ' . $hoursLeft . ' hour(s).');
                }
            }
        }

        return view('ai-create', compact('wallet'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        if (!$wallet) {
            return redirect('/ai/create')->with('error', 'Wallet not found.');
        }

        $amount = (float) $request->amount;

        if ($amount < 1000) {
            return redirect('/ai/create')->with('error', 'Minimum investment for AI trading is $1000.');
        }

        $activeStrategy = AiStrategy::where(function ($q) use ($user) {
                $q->where('user_name', $user->name)
                  ->orWhere('user_id', $user->id);
            })
            ->whereIn('status', ['executing', 'redeem_pending'])
            ->first();

        if ($activeStrategy) {
            return redirect('/ai')->with('error', 'Only one AI strategy is allowed at a time.');
        }

        $isAdmin = $this->isAdminUser($user);

        if (!$isAdmin) {
            $lastCancelled = AiStrategy::where(function ($q) use ($user) {
                    $q->where('user_name', $user->name)
                      ->orWhere('user_id', $user->id);
                })
                ->where('status', 'cancelled')
                ->whereNotNull('closed_at')
                ->latest('closed_at')
                ->first();

            if ($lastCancelled) {
                $cooldownUntil = Carbon::parse($lastCancelled->closed_at)->addHours(48);

                if (now()->lt($cooldownUntil)) {
                    $hoursLeft = (int) ceil(now()->diffInMinutes($cooldownUntil) / 60);
                    return redirect('/ai')->with('error', 'After cancelling a strategy, you must wait 48 hours before starting a new one. Remaining: ' . $hoursLeft . ' hour(s).');
                }
            }
        }

        if ($wallet->balance < $amount) {
            return redirect('/ai/create')->with('error', 'Insufficient balance.');
        }

        $strategy = $request->strategy;

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

        $wallet->balance -= $amount;
        $wallet->save();

        $created = AiStrategy::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'strategy_name' => $plan['strategy_name'],
            'target_pair' => $selectedPair,
            'amount' => $amount,
            'target_percent' => $plan['target_percent'],
            'lock_hours' => $plan['lock_hours'],
            'risk_level' => $plan['risk_level'],
            'status' => 'executing',
            'order_no' => 'AI-' . strtoupper(Str::random(10)),
            'current_profit' => 0,
            'started_at' => now(),
            'unlock_at' => now()->addHours($plan['lock_hours']),
            'redeem_requested_at' => null,
            'redeem_available_at' => null,
            'closed_at' => null,
        ]);

        if (!$created) {
            return redirect('/ai/create')->with('error', 'Strategy could not be created.');
        }

        return redirect('/ai')->with('success', 'AI strategy created successfully.');
    }

    public function redeem($id)
    {
        $strategy = AiStrategy::findOrFail($id);
        $user = auth()->user();

        if ($strategy->user_name !== $user->name && $strategy->user_id != $user->id) {
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

        if (now()->lt($strategy->unlock_at)) {
            $hoursLeft = (int) ceil(now()->diffInMinutes($strategy->unlock_at) / 60);
            return redirect('/ai')->with('error', 'Redeem available after ' . $hoursLeft . ' hour(s).');
        }

        $elapsedHours = now()->diffInHours($strategy->started_at);

        if ($elapsedHours > $strategy->lock_hours) {
            $elapsedHours = $strategy->lock_hours;
        }

        $dailyProfit = ($strategy->amount * $strategy->target_percent) / 100;
        $hourlyProfit = $dailyProfit / 24;
        $currentProfit = $hourlyProfit * $elapsedHours;

        $strategy->current_profit = round($currentProfit, 2);
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

        if ($strategy->user_name !== $user->name && $strategy->user_id != $user->id) {
            return redirect('/ai')->with('error', 'Unauthorized.');
        }

        if (in_array($strategy->status, ['closed', 'cancelled'])) {
            return redirect('/ai')->with('error', 'This strategy cannot be cancelled.');
        }

        $wallet = Wallet::where('user_name', $user->name)->first();

        if (!$wallet) {
            return redirect('/ai')->with('error', 'Wallet not found.');
        }

        $wallet->balance += $strategy->amount;
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