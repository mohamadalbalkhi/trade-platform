<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PlatformWallet;
use App\Http\Controllers\AIStrategyController;
use App\Http\Controllers\GoogleAuthController;

use App\Models\User;
use App\Models\Deposit;
use App\Models\Wallet;
use App\Models\Trade;
use App\Models\Withdrawal;
use App\Models\Order;
use App\Models\AiStrategy;
use App\Models\UserAsset;
use App\Http\Controllers\VerificationController;

Route::get('/verification', [VerificationController::class, 'index'])->middleware('auth');
Route::post('/verification', [VerificationController::class, 'submit'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| Language Switch
|--------------------------------------------------------------------------
*/
Route::get('/language/{lang}', function ($lang) {
    $allowedLanguages = [
        'ar',
        'en',
        'de',
        'es',
        'sv',
        'tr',
        'fr',
        'ru',
        'pt',
        'it',
    ];

    if (in_array($lang, $allowedLanguages)) {
        Session::put('locale', $lang);
        App::setLocale($lang);

        if (auth()->check()) {
            $user = auth()->user();
            $user->preferred_language = $lang;
            $user->save();
        }
    }

    return redirect()->back();
})->name('language.switch');

Route::get('/', function () {
    return redirect('/home');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function () {

        $users = User::count();
        $deposits = Deposit::count();
        $withdrawals = Withdrawal::count();
        $trades = Trade::count();

        return view('admin.dashboard', compact('users', 'deposits', 'withdrawals', 'trades'));

    });

    Route::get('/admin/users', function () {

        $users = User::all()->map(function ($user) {

            $activeAiStrategy = AiStrategy::where('user_name', $user->name)
                ->whereIn('status', ['executing', 'redeem_pending', 'pending'])
                ->latest()
                ->first();

            $user->ai_strategy_status = $activeAiStrategy ? 'Active' : 'Inactive';
            $user->ai_strategy_order_no = $activeAiStrategy->order_no ?? null;
            $user->ai_strategy_name = $activeAiStrategy->strategy_name ?? null;
            $user->ai_strategy_pair = $activeAiStrategy->pair ?? ($activeAiStrategy->target_pair ?? null);
            $user->ai_strategy_amount = $activeAiStrategy->amount ?? null;
            $user->ai_strategy_current_profit = $activeAiStrategy->current_profit ?? null;
            $user->ai_strategy_total_value = $activeAiStrategy
                ? round((float) $activeAiStrategy->amount + (float) $activeAiStrategy->current_profit, 2)
                : null;
            $user->ai_strategy_run_status = $activeAiStrategy->status ?? null;
            $user->ai_strategy_unlock_at = $activeAiStrategy->unlock_at ?? null;

            $lastCancelled = AiStrategy::where('user_name', $user->name)
                ->where('status', 'cancelled')
                ->whereNotNull('closed_at')
                ->latest('closed_at')
                ->first();

            if ($lastCancelled) {
                $cooldownUntil = \Carbon\Carbon::parse($lastCancelled->closed_at)->addHours(48);

                $user->ai_cooldown_active = now()->lt($cooldownUntil);
                $user->ai_cooldown_until = $cooldownUntil;
                $user->ai_cooldown_hours_left = now()->lt($cooldownUntil)
                    ? (int) ceil(now()->diffInMinutes($cooldownUntil) / 60)
                    : 0;
            } else {
                $user->ai_cooldown_active = false;
                $user->ai_cooldown_until = null;
                $user->ai_cooldown_hours_left = 0;
            }

            return $user;
        });

        return view('admin.users', compact('users'));

    });

    Route::post('/admin/users/{id}/force-cancel-ai', function ($id) {

        $user = User::findOrFail($id);

        $strategy = AiStrategy::where('user_name', $user->name)
            ->whereIn('status', ['executing', 'redeem_pending', 'pending'])
            ->latest()
            ->first();

        if (!$strategy) {
            return redirect('/admin/users')->with('error', 'No active AI strategy found for this user.');
        }

        $wallet = Wallet::firstOrCreate(
            ['user_name' => $user->name],
            ['balance' => 0, 'btc_balance' => 0]
        );

        $wallet->balance = round((float) $wallet->balance + (float) $strategy->amount, 2);
        $wallet->save();

        $strategy->current_profit = 0;
        $strategy->status = 'cancelled';
        $strategy->redeem_requested_at = null;
        $strategy->redeem_available_at = null;
        $strategy->closed_at = now();
        $strategy->save();

        return redirect('/admin/users')->with('success', 'Active AI strategy cancelled by admin and principal returned to wallet.');

    });

    Route::post('/admin/users/{id}/reset-ai-cooldown', function ($id) {

        $user = User::findOrFail($id);

        $lastCancelled = AiStrategy::where('user_name', $user->name)
            ->where('status', 'cancelled')
            ->whereNotNull('closed_at')
            ->latest('closed_at')
            ->first();

        if (!$lastCancelled) {
            return redirect('/admin/users')->with('error', 'No cancelled AI strategy found for this user.');
        }

        $lastCancelled->closed_at = now()->subHours(49);
        $lastCancelled->save();

        return redirect('/admin/users')->with('success', 'AI cooldown reset successfully. User can start a new AI strategy now.');

    });

    Route::get('/admin/wallet/{id}', function ($id) {

        $user = User::findOrFail($id);

        $wallet = Wallet::firstOrCreate(
            ['user_name' => $user->name],
            ['balance' => 0, 'btc_balance' => 0]
        );

        return view('admin.wallet', compact('user', 'wallet'));

    });

    Route::post('/admin/wallet/{id}/update', function ($id) {

        $user = User::findOrFail($id);
        $wallet = Wallet::where('user_name', $user->name)->first();

        if ($wallet) {
            $wallet->balance = request('balance');
            $wallet->btc_balance = request('btc_balance');
            $wallet->save();
        }

        return redirect('/admin/users');

    });

    Route::post('/admin/users/{id}/disable', function ($id) {

        $user = User::findOrFail($id);
        $user->status = 'disabled';
        $user->save();

        return redirect('/admin/users');

    });

    Route::post('/admin/users/{id}/activate', function ($id) {

        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect('/admin/users');

    });

    Route::post('/admin/users/{id}/reset-withdraw-address', function ($id) {

        $user = User::findOrFail($id);

        $user->withdraw_wallet_address = null;
        $user->withdraw_wallet_network = null;
        $user->withdraw_wallet_locked_at = null;
        $user->save();

        return redirect('/admin/users')->with('success', 'Withdraw address reset successfully.');

    });

    Route::post('/admin/users/{id}/reset-trading-password', function ($id) {

        $user = User::findOrFail($id);

        $user->trading_password = null;
        $user->save();

        return redirect('/admin/users')->with('success', 'Trading password reset successfully.');

    });

    Route::delete('/admin/users/{id}', function ($id) {

        $user = User::findOrFail($id);

        if ($user->email === 'admin@test.com') {
            return redirect('/admin/users');
        }

        $user->delete();

        return redirect('/admin/users');

    });

    Route::get('/admin/deposits', function () {

        $deposits = Deposit::latest()->get();

        return view('admin.deposits', compact('deposits'));

    });

    Route::post('/admin/deposits/{id}/status', function ($id) {

        $deposit = Deposit::findOrFail($id);
        $newStatus = request('status');

        if (!in_array($newStatus, ['Pending', 'Approved', 'Rejected'])) {
            return redirect('/admin/deposits');
        }

        $oldStatus = $deposit->status;

        $wallet = Wallet::firstOrCreate(
            ['user_name' => $deposit->user_name],
            ['balance' => 0, 'btc_balance' => 0]
        );

        if ($oldStatus === 'Approved' && $newStatus !== 'Approved') {
            $wallet->balance -= $deposit->amount;

            if ($wallet->balance < 0) {
                $wallet->balance = 0;
            }

            $wallet->save();
        }

        if ($oldStatus !== 'Approved' && $newStatus === 'Approved') {
            $wallet->balance += $deposit->amount;
            $wallet->save();
        }

        $deposit->status = $newStatus;
        $deposit->save();

        return redirect('/admin/deposits')->with('success', 'Deposit status updated successfully.');

    });

    Route::get('/admin/withdrawals', function () {

        $withdrawals = Withdrawal::all();

        return view('admin.withdrawals', compact('withdrawals'));

    });

    Route::post('/admin/withdrawals/{id}/status', function ($id) {

        $withdrawal = Withdrawal::findOrFail($id);
        $newStatus = request('status');

        if (!in_array($newStatus, ['Pending', 'Approved', 'Rejected'])) {
            return redirect('/admin/withdrawals');
        }

        $oldStatus = $withdrawal->status;

        $wallet = Wallet::where('user_name', $withdrawal->user_name)->first();

        if (!$wallet) {
            return redirect('/admin/withdrawals');
        }

        if ($oldStatus !== 'Approved' && $newStatus === 'Approved') {
            if ($wallet->balance >= $withdrawal->amount) {
                $wallet->balance -= $withdrawal->amount;
                $wallet->save();
            }
        }

        if ($oldStatus === 'Approved' && $newStatus !== 'Approved') {
            $wallet->balance += $withdrawal->amount;
            $wallet->save();
        }

        $withdrawal->status = $newStatus;
        $withdrawal->save();

        return redirect('/admin/withdrawals');

    });

    Route::get('/admin/support', function () {

        $tickets = DB::table('support_tickets')
            ->join('users', 'support_tickets.user_id', '=', 'users.id')
            ->select(
                'support_tickets.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->latest('support_tickets.created_at')
            ->get();

        return view('admin.support', compact('tickets'));

    });

    Route::post('/admin/support/{id}/reply', function ($id) {

        $reply = trim((string) request('admin_reply'));
        $status = trim((string) request('status', 'Replied'));

        if ($reply === '' || mb_strlen($reply) < 2) {
            return redirect('/admin/support')->with('error', 'Please enter a valid support reply.');
        }

        if (!in_array($status, ['Pending', 'Replied', 'Closed'])) {
            $status = 'Replied';
        }

        DB::table('support_tickets')
            ->where('id', $id)
            ->update([
                'admin_reply' => $reply,
                'status' => $status,
                'updated_at' => now(),
            ]);

        return redirect('/admin/support')->with('success', 'Support reply sent successfully.');

    });

    Route::get('/admin/referrals', function () {

        $referrals = User::whereNotNull('referred_by')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($invited) {
                $inviter = User::find($invited->referred_by);

                $wallet = Wallet::where('user_name', $invited->name)->first();

                $hasApprovedDeposit = Deposit::where('user_id', $invited->id)
                    ->where('status', 'Approved')
                    ->exists();

                $hasWalletFunds = $wallet && (
                    ((float) ($wallet->balance ?? 0)) > 0 ||
                    ((float) ($wallet->btc_balance ?? 0)) > 0
                );

                $hasDeposit = $hasApprovedDeposit || $hasWalletFunds;
                $isVerified = $invited->verification_status === 'verified';
                $isQualified = $isVerified && $hasDeposit;
                $invitedUsersCount = User::where('referred_by', $invited->id)->count();

                return (object) [
                    'id' => $invited->id,
                    'name' => $invited->name,
                    'email' => $invited->email,
                    'account_id' => $invited->account_id,
                    'created_at' => $invited->created_at,
                    'referred_by' => $invited->referred_by,
                    'inviter_account_id' => $inviter?->account_id,
                    'verification_status' => $isVerified ? 'Yes' : 'No',
                    'deposit_status' => $hasDeposit ? 'Yes' : 'No',
                    'qualified_status' => $isQualified ? 'Yes' : 'No',
                    'referral_reward_level' => (int) ($invited->referral_reward_level ?? 0),
                    'is_agent' => !empty($invited->is_agent) ? 'Yes' : 'No',
                    'weekly_profit_enabled' => !empty($invited->weekly_profit_enabled) ? 'Yes' : 'No',
                    'invited_users_count' => $invitedUsersCount,
                ];
            });

        return view('admin.referrals', compact('referrals'));

    });

    Route::post('/admin/referral/reward/{id}', function ($id) {

        $user = User::findOrFail($id);

        $qualifiedCount = User::where('referred_by', $user->id)
            ->get()
            ->filter(function ($u) {
                $wallet = Wallet::where('user_name', $u->name)->first();

                $hasApprovedDeposit = Deposit::where('user_id', $u->id)
                    ->where('status', 'Approved')
                    ->exists();

                $hasWalletFunds = $wallet && (
                    ((float) ($wallet->balance ?? 0)) > 0 ||
                    ((float) ($wallet->btc_balance ?? 0)) > 0
                );

                $hasDeposit = $hasApprovedDeposit || $hasWalletFunds;
                $isVerified = $u->verification_status === 'verified';

                return $isVerified && $hasDeposit;
            })
            ->count();

        $level = 0;
        $reward = 0;
        $message = '';

        if ($qualifiedCount >= 30 && (int) $user->referral_reward_level < 3) {
            $level = 3;
            $reward = 4000;
            $message = 'Level 3 reward granted successfully.';
        } elseif ($qualifiedCount >= 15 && (int) $user->referral_reward_level < 2) {
            $level = 2;
            $reward = 2000;
            $message = 'Level 2 reward granted successfully.';
        } elseif ($qualifiedCount >= 5 && (int) $user->referral_reward_level < 1) {
            $level = 1;
            $reward = 500;
            $message = 'Level 1 reward granted successfully.';
        } else {
            return redirect('/admin/referrals')->with('error', 'User is not eligible yet or reward already granted.');
        }

        $wallet = Wallet::firstOrCreate(
            ['user_name' => $user->name],
            ['balance' => 0, 'btc_balance' => 0]
        );

        $wallet->balance += $reward;
        $wallet->save();

        $user->referral_reward_level = $level;

        if ($level === 3) {
            $user->is_agent = true;
            $user->weekly_profit_enabled = true;
        }

        $user->save();

        return redirect('/admin/referrals')->with('success', $message);

    });
});

Route::middleware(['auth'])->group(function () {

    Route::get('/google-auth', [GoogleAuthController::class, 'index']);
    Route::post('/google-auth/enable', [GoogleAuthController::class, 'enable']);
    Route::post('/google-auth/disable', [GoogleAuthController::class, 'disable']);

    Route::get('/support', function () {

        $tickets = DB::table('support_tickets')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('support', compact('tickets'));

    });

    Route::post('/support/send', function () {

        $user = auth()->user();

        $subject = trim((string) request('subject'));
        $message = trim((string) request('message'));

        if ($subject === '' || mb_strlen($subject) < 3) {
            return redirect('/support')->with('error', 'Please enter a valid subject.');
        }

        if ($message === '' || mb_strlen($message) < 5) {
            return redirect('/support')->with('error', 'Please enter a valid message.');
        }

        DB::table('support_tickets')->insert([
            'user_id' => $user->id,
            'subject' => $subject,
            'message' => $message,
            'admin_reply' => null,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/support')->with('success', 'Your support ticket has been created successfully.');

    });

    Route::get('/deposit', function () {

        $user = auth()->user();

        $adminWallet = PlatformWallet::where('is_main', true)
            ->where('is_active', true)
            ->where('network', 'TRC20')
            ->first();

        $latestDeposit = Deposit::where('user_id', $user->id)
            ->where('status', 'Pending')
            ->latest()
            ->first();

        return view('deposit', compact('user', 'adminWallet', 'latestDeposit'));

    });

    Route::post('/deposit/request', function () {

        $user = auth()->user();

        $adminWallet = PlatformWallet::where('is_main', true)
            ->where('is_active', true)
            ->where('network', 'TRC20')
            ->first();

        if (!$adminWallet) {
            return redirect('/deposit')->with('error', 'Admin wallet is not configured yet.');
        }

        if (!$user->withdraw_wallet_address) {
            return redirect('/withdraw/address')->with('error', 'Please save your TRC20 wallet address first.');
        }

        $requestedAmount = (float) request('amount');

        if ($requestedAmount <= 0) {
            return redirect('/deposit')->with('error', 'Please enter a valid deposit amount.');
        }

        if ($requestedAmount < 10) {
            return redirect('/deposit')->with('error', 'Minimum deposit amount is 10 USDT.');
        }

        $oldPending = Deposit::where('user_id', $user->id)
            ->where('status', 'Pending')
            ->latest()
            ->first();

        if ($oldPending) {
            return redirect('/deposit')->with('error', 'You already have a pending deposit request. Please complete it or wait for admin review.');
        }

        $randomCents = random_int(11, 89) / 100;
        $exactAmount = round($requestedAmount + $randomCents, 2);

        do {
            $depositId = 'DEP-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid((string) $user->id, true)), 0, 6));
        } while (Deposit::where('deposit_id', $depositId)->exists());

        DB::table('deposits')->insert([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'deposit_id' => $depositId,
            'amount' => $exactAmount,
            'requested_amount' => $requestedAmount,
            'method' => 'USDT (TRC20)',
            'wallet_address' => $adminWallet->wallet_address,
            'request_note' => 'Send exact amount to admin wallet from registered wallet',
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/deposit')->with('success', 'Deposit request created successfully.');

    });

    Route::get('/withdraw/address', function () {

        $user = auth()->user();

        return view('withdraw-address', compact('user'));

    });

    Route::post('/withdraw/address', function () {

        $user = auth()->user();

        if ($user->withdraw_wallet_address) {
            return redirect('/withdraw/address')->with('error', 'Your USDT TRC20 address is already locked. Please contact support or admin to reset it.');
        }

        $address = trim((string) request('withdraw_wallet_address'));

        if ($address === '') {
            return redirect('/withdraw/address')->with('error', 'Please enter your USDT TRC20 address.');
        }

        if (!preg_match('/^T[1-9A-HJ-NP-Za-km-z]{33}$/', $address)) {
            return redirect('/withdraw/address')->with('error', 'Invalid TRC20 wallet address. Please enter a valid USDT TRC20 address.');
        }

        $existingAddressUser = User::where('withdraw_wallet_address', $address)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingAddressUser) {
            return redirect('/withdraw/address')->with('error', 'This withdrawal address is already used by another account.');
        }

        $user->withdraw_wallet_address = $address;
        $user->withdraw_wallet_network = 'TRC20';
        $user->withdraw_wallet_locked_at = now();
        $user->save();

        return redirect('/withdraw')->with('success', 'Your USDT TRC20 address has been saved and locked successfully.');

    });

    Route::get('/trading-password', function () {

        $user = auth()->user();

        return view('trading-password', compact('user'));

    });

    Route::post('/trading-password', function () {

        $user = auth()->user();

        $tradingPassword = (string) request('trading_password');
        $confirmTradingPassword = (string) request('trading_password_confirmation');

        if ($tradingPassword === '' || $confirmTradingPassword === '') {
            return redirect('/trading-password')->with('error', 'Trading password and confirmation are required.');
        }

        if (strlen($tradingPassword) < 6) {
            return redirect('/trading-password')->with('error', 'Trading password must be at least 6 characters.');
        }

        if ($tradingPassword !== $confirmTradingPassword) {
            return redirect('/trading-password')->with('error', 'Trading password confirmation does not match.');
        }

        $user->trading_password = Hash::make($tradingPassword);
        $user->save();

        return redirect('/withdraw')->with('success', 'Trading password saved successfully.');

    });

    Route::post('/wallet/convert', function () {

        $user = auth()->user();

        $fromAsset = trim((string) request('from_asset'));
        $toAsset = trim((string) request('to_asset'));
        $amount = (float) request('amount');

        if ($fromAsset !== 'USDT') {
            return redirect('/wallet')->with('error', 'Only USDT can be converted.');
        }

        if (!in_array($toAsset, ['BTC', 'ETH', 'TRX', 'DOGE', 'BCH'])) {
            return redirect('/wallet')->with('error', 'Invalid target asset.');
        }

        if ($amount <= 0) {
            return redirect('/wallet')->with('error', 'Please enter a valid conversion amount.');
        }

        $wallet = Wallet::where('user_name', $user->name)->first();

        if (!$wallet) {
            return redirect('/wallet')->with('error', 'Wallet not found.');
        }

        if ($wallet->balance < $amount) {
            return redirect('/wallet')->with('error', 'Insufficient USDT balance.');
        }

        $prices = [
            'BTC' => 65000,
            'ETH' => 3200,
            'TRX' => 0.12,
            'DOGE' => 0.16,
            'BCH' => 520,
        ];

        if (!isset($prices[$toAsset])) {
            return redirect('/wallet')->with('error', 'Unsupported asset.');
        }

        $convertedAmount = round($amount / $prices[$toAsset], 8);

        DB::transaction(function () use ($wallet, $user, $amount, $toAsset, $convertedAmount, $prices) {

            $wallet->balance -= $amount;
            $wallet->save();

            $userAsset = UserAsset::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'asset_symbol' => $toAsset,
                ],
                [
                    'balance' => 0,
                ]
            );

            $userAsset->balance += $convertedAmount;
            $userAsset->save();

            Trade::create([
                'user_name' => $user->name,
                'pair' => $toAsset . '/USDT',
                'type' => 'BUY',
                'amount' => $convertedAmount,
                'price' => $prices[$toAsset],
            ]);
        });

        return redirect('/wallet')->with(
            'success',
            'Successfully converted ' . number_format($amount, 2) . ' USDT to ' . number_format($convertedAmount, 8) . ' ' . $toAsset . '.'
        );

    });

    Route::get('/referrals', function () {

        $user = auth()->user();

        if (!$user->referral_code) {
            do {
                $user->referral_code = strtoupper(Str::random(8));
            } while (User::where('referral_code', $user->referral_code)->where('id', '!=', $user->id)->exists());

            $user->save();
        }

        $invitedUsers = User::where('referred_by', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $referralRows = $invitedUsers->map(function ($invitedUser) {
            $wallet = Wallet::where('user_name', $invitedUser->name)->first();

            $hasApprovedDeposit = Deposit::where('user_id', $invitedUser->id)
                ->where('status', 'Approved')
                ->exists();

            $hasWalletFunds = $wallet && (
                ((float) ($wallet->balance ?? 0)) > 0 ||
                ((float) ($wallet->btc_balance ?? 0)) > 0
            );

            $hasDeposit = $hasApprovedDeposit || $hasWalletFunds;
            $isVerified = $invitedUser->verification_status === 'verified';
            $isQualified = $isVerified && $hasDeposit;

            return (object) [
                'account_id' => $invitedUser->account_id ?? $invitedUser->id,
                'joined_at' => $invitedUser->created_at,
                'verified' => $isVerified ? 'Yes' : 'No',
                'deposited' => $hasDeposit ? 'Yes' : 'No',
                'qualified' => $isQualified ? 'Yes' : 'No',
            ];
        });

        $totalInvites = $referralRows->count();
        $verifiedCount = $referralRows->where('verified', 'Yes')->count();
        $depositedCount = $referralRows->where('deposited', 'Yes')->count();
        $qualifiedCount = $referralRows->where('qualified', 'Yes')->count();

        $rewardAmount = 0;
        if ($qualifiedCount >= 5) {
            $rewardAmount = 500;
        }
        if ($qualifiedCount >= 15) {
            $rewardAmount = 2000;
        }
        if ($qualifiedCount >= 30) {
            $rewardAmount = 4000;
        }

        $referralLink = url('/register?ref=' . $user->referral_code);

        return view('referrals', compact(
            'user',
            'referralLink',
            'referralRows',
            'totalInvites',
            'verifiedCount',
            'depositedCount',
            'qualifiedCount',
            'rewardAmount'
        ));

    })->name('referrals');

    Route::get('/ai', [AIStrategyController::class, 'index'])->name('ai.index');
    Route::get('/ai/create', [AIStrategyController::class, 'create']);
    Route::get('/ai/live', [AIStrategyController::class, 'live']);
    Route::post('/ai/store', [AIStrategyController::class, 'store']);
    Route::post('/ai/cancel/{id}', [AIStrategyController::class, 'cancel']);
    Route::post('/ai/redeem/{id}', [AIStrategyController::class, 'redeem']);

    Route::get('/markets', function () {
        return view('markets');
    });

    Route::get('/trade', function () {
        return redirect('/trade/BTCUSDT');
    });

    Route::get('/trade/{symbol}', function ($symbol) {

        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        $symbols = [
            'BTCUSDT' => ['coin_id' => 'bitcoin', 'name' => 'BTC / USDT'],
            'ETHUSDT' => ['coin_id' => 'ethereum', 'name' => 'ETH / USDT'],
            'TRXUSDT' => ['coin_id' => 'tron', 'name' => 'TRX / USDT'],
            'DOGEUSDT' => ['coin_id' => 'dogecoin', 'name' => 'DOGE / USDT'],
            'BCHUSDT' => ['coin_id' => 'bitcoin-cash', 'name' => 'BCH / USDT'],
        ];

        $symbol = strtoupper(trim($symbol));

        if (!isset($symbols[$symbol])) {
            return redirect('/markets')->with('error', 'Trading pair not found.');
        }

        $marketTable = [];
        $selectedName = $symbols[$symbol]['name'];
        $selectedPrice = 0;
        $selectedChange = 0;

        try {
            $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => implode(',', collect($symbols)->pluck('coin_id')->all()),
                'vs_currencies' => 'usd',
                'include_24hr_change' => 'true',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                foreach ($symbols as $pairSymbol => $meta) {
                    $price = $data[$meta['coin_id']]['usd'] ?? 0;
                    $change = $data[$meta['coin_id']]['usd_24h_change'] ?? 0;

                    $marketTable[$pairSymbol] = [
                        'symbol' => $pairSymbol,
                        'name' => $meta['name'],
                        'price' => $price,
                        'change' => $change,
                    ];
                }
            }
        } catch (\Exception $e) {
        }

        if (count($marketTable) === 0) {
            $marketTable = [
                'BTCUSDT' => ['symbol' => 'BTCUSDT', 'name' => 'BTC / USDT', 'price' => 66474.66, 'change' => -2.37],
                'ETHUSDT' => ['symbol' => 'ETHUSDT', 'name' => 'ETH / USDT', 'price' => 2038.59, 'change' => -0.80],
                'TRXUSDT' => ['symbol' => 'TRXUSDT', 'name' => 'TRX / USDT', 'price' => 0.31578, 'change' => 0.05],
                'DOGEUSDT' => ['symbol' => 'DOGEUSDT', 'name' => 'DOGE / USDT', 'price' => 0.14056, 'change' => 0.59],
                'BCHUSDT' => ['symbol' => 'BCHUSDT', 'name' => 'BCH / USDT', 'price' => 444.30, 'change' => -2.40],
            ];
        }

        $selectedPrice = $marketTable[$symbol]['price'] ?? 0;
        $selectedChange = $marketTable[$symbol]['change'] ?? 0;

        return view('trade', compact(
            'wallet',
            'symbol',
            'selectedName',
            'selectedPrice',
            'selectedChange',
            'marketTable'
        ));

    });

    Route::get('/home', function () {

        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        $symbols = [
            'bitcoin' => ['symbol' => 'BTCUSDT', 'name' => 'BTC / USDT'],
            'ethereum' => ['symbol' => 'ETHUSDT', 'name' => 'ETH / USDT'],
            'tron' => ['symbol' => 'TRXUSDT', 'name' => 'TRX / USDT'],
            'dogecoin' => ['symbol' => 'DOGEUSDT', 'name' => 'DOGE / USDT'],
            'bitcoin-cash' => ['symbol' => 'BCHUSDT', 'name' => 'BCH / USDT'],
        ];

        $marketCards = [];
        $marketTable = [];

        try {
            $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => implode(',', array_keys($symbols)),
                'vs_currencies' => 'usd',
                'include_24hr_change' => 'true',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                foreach ($symbols as $coinId => $meta) {
                    $price = $data[$coinId]['usd'] ?? 0;
                    $change = $data[$coinId]['usd_24h_change'] ?? 0;

                    $marketTable[] = [
                        'symbol' => $meta['symbol'],
                        'name' => $meta['name'],
                        'price' => $price,
                        'change' => $change,
                    ];
                }
            }
        } catch (\Exception $e) {
            $marketTable = [
                ['symbol' => 'BTCUSDT', 'name' => 'BTC / USDT', 'price' => 66474.66, 'change' => -2.37],
                ['symbol' => 'ETHUSDT', 'name' => 'ETH / USDT', 'price' => 2038.59, 'change' => -0.80],
                ['symbol' => 'TRXUSDT', 'name' => 'TRX / USDT', 'price' => 0.31578, 'change' => 0.05],
                ['symbol' => 'DOGEUSDT', 'name' => 'DOGE / USDT', 'price' => 0.14056, 'change' => 0.59],
                ['symbol' => 'BCHUSDT', 'name' => 'BCH / USDT', 'price' => 444.30, 'change' => -2.40],
            ];
        }

        if (count($marketTable) === 0) {
            $marketTable = [
                ['symbol' => 'BTCUSDT', 'name' => 'BTC / USDT', 'price' => 66474.66, 'change' => -2.37],
                ['symbol' => 'ETHUSDT', 'name' => 'ETH / USDT', 'price' => 2038.59, 'change' => -0.80],
                ['symbol' => 'TRXUSDT', 'name' => 'TRX / USDT', 'price' => 0.31578, 'change' => 0.05],
                ['symbol' => 'DOGEUSDT', 'name' => 'DOGE / USDT', 'price' => 0.14056, 'change' => 0.59],
                ['symbol' => 'BCHUSDT', 'name' => 'BCH / USDT', 'price' => 444.30, 'change' => -2.40],
            ];
        }

        $marketCards = array_slice($marketTable, 0, 3);

        $gainers = collect($marketTable)->sortByDesc('change')->values()->all();
        $losers = collect($marketTable)->sortBy('change')->values()->all();
        $volumeList = $marketTable;

        $totalAssets = 0;
        if ($wallet) {
            $btcPrice = collect($marketTable)->firstWhere('symbol', 'BTCUSDT')['price'] ?? 65000;
            $totalAssets = $wallet->balance + ($wallet->btc_balance * $btcPrice);
        }

        return view('home', compact(
            'user',
            'wallet',
            'totalAssets',
            'marketCards',
            'gainers',
            'losers',
            'volumeList'
        ));

    });

    Route::get('/dashboard', function () {
        return redirect('/home');
    })->name('dashboard');

    Route::get('/profile', function () {

        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        return view('profile', compact('user', 'wallet'));

    })->name('profile.edit');

    Route::post('/profile/update', function () {

        $user = auth()->user();

        $name = request('name');
        $email = request('email');
        $currentPassword = request('current_password');
        $newPassword = request('new_password');
        $confirmPassword = request('new_password_confirmation');

        if (!$name || !$email) {
            return redirect('/profile')->with('error', 'Name and email are required');
        }

        $existingUser = User::where('email', $email)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return redirect('/profile')->with('error', 'Email already used by another account');
        }

        $oldName = $user->name;

        $user->name = $name;
        $user->email = $email;

        if ($newPassword || $confirmPassword || $currentPassword) {

            if (!$currentPassword) {
                return redirect('/profile')->with('error', 'Current password is required');
            }

            if (!Hash::check($currentPassword, $user->password)) {
                return redirect('/profile')->with('error', 'Current password is incorrect');
            }

            if (!$newPassword || strlen($newPassword) < 8) {
                return redirect('/profile')->with('error', 'New password must be at least 8 characters');
            }

            if ($newPassword !== $confirmPassword) {
                return redirect('/profile')->with('error', 'New password confirmation does not match');
            }

            $user->password = Hash::make($newPassword);
        }

        $user->save();

        if ($oldName !== $name) {
            $wallet = Wallet::where('user_name', $oldName)->first();

            if ($wallet) {
                $wallet->user_name = $name;
                $wallet->save();
            }

            Deposit::where('user_name', $oldName)->update(['user_name' => $name]);
            Withdrawal::where('user_name', $oldName)->update(['user_name' => $name]);
            Trade::where('user_name', $oldName)->update(['user_name' => $name]);
            Order::where('user_name', $oldName)->update(['user_name' => $name]);
            AiStrategy::where('user_name', $oldName)->update(['user_name' => $name]);
        }

        return redirect('/profile')->with('success', 'Profile updated successfully.');

    });

    Route::get('/wallet', function () {

        $wallet = Wallet::where('user_name', auth()->user()->name)->first();

        return view('wallet', compact('wallet'));

    });

    Route::post('/trade/buy', function () {

        $wallet = Wallet::where('user_name', auth()->user()->name)->first();

        $btcPrice = 65000;

        try {
            $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'bitcoin',
                'vs_currencies' => 'usd',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['bitcoin']['usd'])) {
                    $btcPrice = $data['bitcoin']['usd'];
                }
            }
        } catch (\Exception $e) {
        }

        $btcAmount = (float) request('btc_amount');
        $usdAmount = $btcAmount * $btcPrice;

        if ($btcAmount <= 0) {
            return redirect('/trade/BTCUSDT')->with('error', 'Invalid BTC amount');
        }

        if ($wallet && $wallet->balance >= $usdAmount) {

            $wallet->balance -= $usdAmount;
            $wallet->btc_balance += $btcAmount;
            $wallet->save();

            Trade::create([
                'user_name' => auth()->user()->name,
                'pair' => 'BTC/USDT',
                'type' => 'BUY',
                'amount' => $btcAmount,
                'price' => $btcPrice
            ]);

            return redirect('/trade/BTCUSDT')->with('success', 'BTC Bought Successfully');
        }

        return redirect('/trade/BTCUSDT')->with('error', 'Insufficient USD Balance');

    });

    Route::post('/trade/sell', function () {

        $wallet = Wallet::where('user_name', auth()->user()->name)->first();

        $btcPrice = 65000;

        try {
            $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'bitcoin',
                'vs_currencies' => 'usd',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['bitcoin']['usd'])) {
                    $btcPrice = $data['bitcoin']['usd'];
                }
            }
        } catch (\Exception $e) {
        }

        $btcAmount = (float) request('btc_amount');
        $usdAmount = $btcAmount * $btcPrice;

        if ($btcAmount <= 0) {
            return redirect('/trade/BTCUSDT')->with('error', 'Invalid BTC amount');
        }

        if ($wallet && $wallet->btc_balance >= $btcAmount) {

            $wallet->btc_balance -= $btcAmount;
            $wallet->balance += $usdAmount;
            $wallet->save();

            Trade::create([
                'user_name' => auth()->user()->name,
                'pair' => 'BTC/USDT',
                'type' => 'SELL',
                'amount' => $btcAmount,
                'price' => $btcPrice
            ]);

            return redirect('/trade/BTCUSDT')->with('success', 'BTC Sold Successfully');
        }

        return redirect('/trade/BTCUSDT')->with('error', 'Insufficient BTC Balance');

    });

    Route::post('/trade/limit-order', function () {

        $btcAmount = (float) request('btc_amount');
        $price = (float) request('price');
        $type = request('type');

        if ($btcAmount <= 0 || $price <= 0 || !in_array($type, ['BUY', 'SELL'])) {
            return redirect('/trade/BTCUSDT')->with('error', 'Invalid order data');
        }

        Order::create([
            'user_name' => auth()->user()->name,
            'pair' => 'BTC/USDT',
            'type' => $type,
            'btc_amount' => $btcAmount,
            'price' => $price,
            'status' => 'Open'
        ]);

        return redirect('/trade/BTCUSDT')->with('success', 'Limit order created successfully.');

    });

    Route::post('/trade/order/{id}/cancel', function ($id) {

        $order = Order::findOrFail($id);

        if ($order->user_name !== auth()->user()->name) {
            return redirect('/trade/BTCUSDT')->with('error', 'Unauthorized action');
        }

        if ($order->status === 'Open') {
            $order->status = 'Cancelled';
            $order->save();
        }

        return redirect('/trade/BTCUSDT')->with('success', 'Order cancelled successfully.');

    });

    Route::get('/history', function () {

        $trades = Trade::where('user_name', auth()->user()->name)->latest()->get();

        return view('history', compact('trades'));

    });

    Route::get('/withdraw', function () {

        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        if (!$user->withdraw_wallet_address) {
            return redirect('/withdraw/address')->with('error', 'Please save your USDT TRC20 address first.');
        }

        if (!$user->trading_password) {
            return redirect('/trading-password')->with('error', 'Please set your trading password first.');
        }

        $feeRate = 0.15;
        $minWithdraw = 10;

        return view('withdraw', compact('wallet', 'user', 'feeRate', 'minWithdraw'));

    });

    Route::post('/withdraw', function () {

        $user = auth()->user();
        $wallet = Wallet::where('user_name', $user->name)->first();

        $amount = (float) request('amount');
        $platformName = trim((string) request('platform_name'));
        $tradingPassword = (string) request('trading_password');
        $googleCode = trim((string) request('google_code'));

        $feeRate = 0.15;
        $minWithdraw = 10;

        if (!$wallet) {
            return redirect('/withdraw')->with('error', 'Wallet not found.');
        }

        if (!$user->withdraw_wallet_address || !$user->withdraw_wallet_locked_at) {
            return redirect('/withdraw/address')->with('error', 'Please save and lock your USDT TRC20 address first.');
        }

        if (!$user->trading_password) {
            return redirect('/trading-password')->with('error', 'Please set your trading password first.');
        }

        if ($amount <= 0) {
            return redirect('/withdraw')->with('error', 'Please enter a valid withdrawal amount.');
        }

        if ($amount < $minWithdraw) {
            return redirect('/withdraw')->with('error', 'Minimum withdrawal amount is $' . number_format($minWithdraw, 2));
        }

        if ($wallet->balance < $amount) {
            return redirect('/withdraw')->with('error', 'Insufficient balance.');
        }

        if ($platformName === '' || strlen($platformName) < 2) {
            return redirect('/withdraw')->with('error', 'Please enter a valid receiving platform name.');
        }

        if ($tradingPassword === '') {
            return redirect('/withdraw')->with('error', 'Trading password is required.');
        }

        if (!Hash::check($tradingPassword, $user->trading_password)) {
            return redirect('/withdraw')->with('error', 'Invalid trading password.');
        }

        $feeAmount = round($amount * $feeRate, 2);
        $netAmount = round($amount - $feeAmount, 2);

        if ($netAmount <= 0) {
            return redirect('/withdraw')->with('error', 'Net withdrawal amount must be greater than zero.');
        }

        if ($user->google2fa_enabled) {
            if ($googleCode === '') {
                return redirect('/withdraw')->with('error', 'Google Authenticator code is required.');
            }

            if (!preg_match('/^\d{6}$/', $googleCode)) {
                return redirect('/withdraw')->with('error', 'Google Authenticator code must be exactly 6 digits.');
            }

            $googleAuthController = app(GoogleAuthController::class);

            if (!$googleAuthController->verifyTotpCode($user->google2fa_secret, $googleCode)) {
                return redirect('/withdraw')->with('error', 'Invalid Google Authenticator code.');
            }
        }

        Withdrawal::create([
            'user_name' => $user->name,
            'amount' => $amount,
            'method' => 'USDT (TRC20)',
            'wallet_address' => 'Platform: ' . $platformName
                . ' | Network: ' . ($user->withdraw_wallet_network ?? 'TRC20')
                . ' | Address: ' . $user->withdraw_wallet_address
                . ' | Fee: $' . number_format($feeAmount, 2)
                . ' | Net: $' . number_format($netAmount, 2),
            'status' => 'Pending'
        ]);

        return redirect('/withdraw')->with('success', 'Withdrawal request submitted successfully.');

    });

});

require __DIR__.'/auth.php';