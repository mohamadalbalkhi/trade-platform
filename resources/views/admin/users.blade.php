<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Users</title>
    <style>
        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
            font-family:Arial, Helvetica, sans-serif;
        }

        :root{
            --bg:#0b1020;
            --bg-2:#111827;
            --sidebar:#0f172a;
            --panel:#111c31;
            --panel-2:#16233b;
            --line:rgba(255,255,255,0.08);
            --text:#edf2f7;
            --muted:#94a3b8;
            --cyan:#38bdf8;
            --cyan-soft:rgba(56,189,248,0.14);
            --amber:#f59e0b;
            --amber-soft:rgba(245,158,11,0.14);
            --green:#22c55e;
            --green-soft:rgba(34,197,94,0.14);
            --red:#ef4444;
            --red-soft:rgba(239,68,68,0.14);
            --violet:#8b5cf6;
            --violet-soft:rgba(139,92,246,0.14);
            --white-soft:rgba(255,255,255,0.03);
        }

        body{
            min-height:100vh;
            color:var(--text);
            background:
                radial-gradient(circle at top left, rgba(56,189,248,0.10), transparent 22%),
                radial-gradient(circle at bottom right, rgba(245,158,11,0.08), transparent 24%),
                linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
        }

        .page{
            min-height:100vh;
            display:flex;
        }

        .sidebar{
            width:285px;
            background:linear-gradient(180deg, rgba(15,23,42,0.98) 0%, rgba(8,13,25,0.99) 100%);
            border-right:1px solid var(--line);
            padding:28px 20px;
            box-shadow:0 0 40px rgba(0,0,0,0.30);
        }

        .brand{
            margin-bottom:30px;
        }

        .brand h1{
            font-size:28px;
            font-weight:900;
            letter-spacing:.5px;
            color:#f8fafc;
            margin-bottom:4px;
        }

        .brand h1 span{
            color:var(--cyan);
        }

        .brand p{
            font-size:12px;
            letter-spacing:1.3px;
            color:var(--muted);
        }

        .nav{
            display:flex;
            flex-direction:column;
            gap:10px;
        }

        .nav a{
            text-decoration:none;
            color:#e5e7eb;
            padding:14px 16px;
            border-radius:18px;
            background:rgba(255,255,255,0.02);
            border:1px solid transparent;
            font-weight:800;
            transition:.2s ease;
        }

        .nav a:hover,
        .nav a.active{
            background:var(--cyan-soft);
            border-color:rgba(56,189,248,0.18);
            color:#e0f2fe;
        }

        .logout-form{
            margin-top:22px;
        }

        .logout-btn{
            width:100%;
            border:none;
            cursor:pointer;
            padding:14px 16px;
            border-radius:18px;
            background:var(--red-soft);
            border:1px solid rgba(239,68,68,0.22);
            color:#fecaca;
            font-weight:900;
            transition:.2s ease;
        }

        .logout-btn:hover{
            background:rgba(239,68,68,0.20);
        }

        .content{
            flex:1;
            padding:28px;
        }

        .alert{
            border-radius:18px;
            padding:14px 16px;
            margin-bottom:14px;
            font-size:14px;
            font-weight:800;
            line-height:1.6;
            border:1px solid transparent;
        }

        .alert.success{
            background:var(--green-soft);
            color:#bbf7d0;
            border-color:rgba(34,197,94,0.20);
        }

        .alert.error{
            background:var(--red-soft);
            color:#fecaca;
            border-color:rgba(239,68,68,0.20);
        }

        .hero{
            position:relative;
            overflow:hidden;
            margin-bottom:22px;
            padding:26px;
            border-radius:30px;
            background:
                radial-gradient(circle at 88% 12%, rgba(56,189,248,0.12), transparent 26%),
                radial-gradient(circle at 18% 100%, rgba(139,92,246,0.10), transparent 30%),
                linear-gradient(180deg, rgba(18,29,49,0.98) 0%, rgba(12,20,35,0.99) 100%);
            border:1px solid var(--line);
            box-shadow:0 20px 44px rgba(0,0,0,0.28);
        }

        .hero-top{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:16px;
        }

        .hero h2{
            font-size:34px;
            font-weight:900;
            margin-bottom:8px;
        }

        .hero p{
            color:var(--muted);
            font-size:14px;
            line-height:1.75;
            max-width:760px;
        }

        .hero-badge{
            padding:10px 14px;
            border-radius:16px;
            font-size:12px;
            font-weight:900;
            color:#dbeafe;
            border:1px solid rgba(56,189,248,0.22);
            background:var(--cyan-soft);
            white-space:nowrap;
        }

        .table-wrap{
            background:linear-gradient(180deg, rgba(18,29,49,0.98) 0%, rgba(12,20,35,0.99) 100%);
            border:1px solid var(--line);
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 18px 40px rgba(0,0,0,0.24);
        }

        .table-scroll{
            overflow:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:2400px;
        }

        thead{
            background:rgba(255,255,255,0.03);
        }

        th{
            text-align:left;
            padding:16px;
            font-size:13px;
            color:#a9b8cc;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.6px;
            border-bottom:1px solid var(--line);
        }

        td{
            padding:16px;
            border-top:1px solid rgba(255,255,255,0.05);
            vertical-align:top;
            font-size:14px;
            color:#ecf4ee;
        }

        tr:hover td{
            background:rgba(255,255,255,0.015);
        }

        .user-id{
            font-size:18px;
            font-weight:900;
            color:#fff;
        }

        .user-name{
            font-weight:900;
            color:#fff;
            margin-bottom:4px;
        }

        .email{
            color:#e8eef7;
            line-height:1.6;
            word-break:break-word;
        }

        .muted{
            color:#93a4ba;
            font-size:12px;
            line-height:1.6;
        }

        .address-box{
            max-width:230px;
            word-break:break-all;
            white-space:pre-wrap;
            line-height:1.65;
            color:#e8eef7;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            border-radius:16px;
            padding:12px 14px;
            margin-top:8px;
        }

        .ai-box,
        .cooldown-box{
            min-width:260px;
            line-height:1.7;
            color:#e8eef7;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            border-radius:16px;
            padding:12px 14px;
            margin-top:8px;
        }

        .ai-box-row,
        .cooldown-row{
            display:flex;
            justify-content:space-between;
            gap:10px;
            padding:5px 0;
            border-bottom:1px solid rgba(255,255,255,0.04);
        }

        .ai-box-row:last-child,
        .cooldown-row:last-child{
            border-bottom:none;
        }

        .ai-box-label,
        .cooldown-label{
            color:#93a4ba;
            font-size:12px;
            font-weight:800;
            white-space:nowrap;
        }

        .ai-box-value,
        .cooldown-value{
            color:#ffffff;
            font-size:12px;
            font-weight:800;
            text-align:right;
            word-break:break-word;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:6px 10px;
            border-radius:999px;
            font-size:11px;
            font-weight:900;
            white-space:nowrap;
        }

        .badge.active{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.20);
        }

        .badge.disabled{
            background:var(--red-soft);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.20);
        }

        .badge.ok{
            background:var(--cyan-soft);
            color:#bae6fd;
            border:1px solid rgba(56,189,248,0.20);
        }

        .badge.no{
            background:rgba(255,255,255,0.06);
            color:#cbd5e1;
            border:1px solid rgba(255,255,255,0.08);
        }

        .badge.pending{
            background:var(--amber-soft);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.20);
        }

        .badge.ai-live{
            background:rgba(34,197,94,0.14);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.22);
        }

        .badge.ai-off{
            background:rgba(255,255,255,0.06);
            color:#cbd5e1;
            border:1px solid rgba(255,255,255,0.08);
        }

        .badge.cooldown-on{
            background:rgba(245,158,11,0.14);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.22);
        }

        .badge.cooldown-off{
            background:rgba(34,197,94,0.14);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.22);
        }

        .action-grid{
            display:flex;
            flex-wrap:wrap;
            gap:8px;
        }

        .action-grid form,
        .action-grid a{
            display:inline-flex;
        }

        .btn{
            border:none;
            cursor:pointer;
            text-decoration:none;
            padding:10px 12px;
            border-radius:14px;
            font-size:12px;
            font-weight:900;
            transition:.2s ease;
            white-space:nowrap;
        }

        .btn.wallet{
            background:var(--cyan-soft);
            color:#bae6fd;
            border:1px solid rgba(56,189,248,0.20);
        }

        .btn.activate{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.20);
        }

        .btn.disable{
            background:var(--amber-soft);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.20);
        }

        .btn.reset{
            background:var(--violet-soft);
            color:#ddd6fe;
            border:1px solid rgba(139,92,246,0.20);
        }

        .btn.delete{
            background:var(--red-soft);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.20);
        }

        .btn.ai-cancel{
            background:rgba(239,68,68,0.16);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.24);
        }

        .btn.cooldown{
            background:rgba(139,92,246,0.16);
            color:#ddd6fe;
            border:1px solid rgba(139,92,246,0.24);
        }

        .btn:hover{
            transform:translateY(-1px);
            filter:brightness(1.05);
        }

        .empty{
            padding:30px;
            text-align:center;
            color:#93a4ba;
        }

        .modal-overlay{
            position:fixed;
            inset:0;
            background:rgba(3,8,18,0.72);
            backdrop-filter:blur(6px);
            -webkit-backdrop-filter:blur(6px);
            display:none;
            align-items:center;
            justify-content:center;
            padding:20px;
            z-index:3000;
        }

        .modal-overlay.show{
            display:flex;
        }

        .modal-box{
            width:min(100%, 520px);
            border-radius:28px;
            background:linear-gradient(180deg, rgba(20,31,52,0.99) 0%, rgba(12,21,36,1) 100%);
            border:1px solid var(--line);
            box-shadow:0 28px 60px rgba(0,0,0,0.38);
            padding:24px;
            animation:modalUp .18s ease;
        }

        @keyframes modalUp{
            from{
                transform:translateY(12px);
                opacity:0;
            }
            to{
                transform:translateY(0);
                opacity:1;
            }
        }

        .modal-title{
            font-size:24px;
            font-weight:900;
            color:#fff;
            margin-bottom:8px;
        }

        .modal-text{
            color:var(--muted);
            font-size:14px;
            line-height:1.7;
            margin-bottom:18px;
        }

        .modal-preview{
            border-radius:18px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            padding:14px;
            margin-bottom:18px;
        }

        .modal-preview-row{
            display:flex;
            justify-content:space-between;
            gap:12px;
            padding:8px 0;
            border-bottom:1px solid rgba(255,255,255,0.05);
        }

        .modal-preview-row:last-child{
            border-bottom:none;
        }

        .modal-preview-label{
            color:#97a8be;
            font-size:12px;
            font-weight:800;
        }

        .modal-preview-value{
            color:#fff;
            font-size:13px;
            font-weight:800;
            text-align:right;
            word-break:break-word;
            max-width:260px;
        }

        .modal-actions{
            display:flex;
            gap:12px;
            justify-content:flex-end;
        }

        .modal-btn{
            border:none;
            cursor:pointer;
            padding:12px 16px;
            border-radius:16px;
            font-size:13px;
            font-weight:900;
            transition:.2s ease;
        }

        .modal-btn.cancel{
            background:rgba(255,255,255,0.06);
            color:#e5e7eb;
            border:1px solid rgba(255,255,255,0.08);
        }

        .modal-btn.confirm{
            color:#fff;
        }

        .modal-btn.confirm.activate{
            background:linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
        }

        .modal-btn.confirm.disable{
            background:linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        }

        .modal-btn.confirm.reset{
            background:linear-gradient(180deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .modal-btn.confirm.delete{
            background:linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        }

        .modal-btn.confirm.ai-cancel{
            background:linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        }

        .modal-btn.confirm.cooldown{
            background:linear-gradient(180deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .modal-btn:hover{
            transform:translateY(-1px);
        }

        @media (max-width: 980px){
            .page{
                flex-direction:column;
            }

            .sidebar{
                width:100%;
                border-right:none;
                border-bottom:1px solid var(--line);
            }

            .content{
                padding:18px;
            }

            .hero h2{
                font-size:28px;
            }
        }

        @media (max-width:700px){
            .hero-top{
                flex-direction:column;
            }

            .modal-actions{
                flex-direction:column;
            }

            .modal-btn{
                width:100%;
            }
        }
    </style>
</head>
<body>

<div class="page">

    <aside class="sidebar">
        <div class="brand">
            <h1>Dawn<span>EX</span></h1>
            <p>EXECUTIVE ADMIN PANEL</p>
        </div>

        <nav class="nav">
            <a href="/admin">Dashboard</a>
            <a href="/admin/users" class="active">Users</a>
            <a href="/admin/deposits">Deposits</a>
            <a href="/admin/withdrawals">Withdrawals</a>
            <a href="/admin/support">Support</a>
            <a href="/admin/referrals">Referrals</a>
        </nav>

        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </aside>

    <main class="content">

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        <div class="hero">
            <div class="hero-top">
                <div>
                    <h2>Users Management</h2>
                    <p>
                        Manage account access, verification, wallet controls, 2FA, withdraw security, AI strategy activity, cooldown state, and direct admin intervention from one place.
                    </p>
                </div>
                <div class="hero-badge">User Control Center</div>
            </div>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Wallet</th>
                            <th>Status</th>
                            <th>Verification</th>
                            <th>2FA</th>
                            <th>AI Strategy</th>
                            <th>AI Details</th>
                            <th>AI Cooldown</th>
                            <th>Withdraw Address</th>
                            <th>Trading Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $verification = $user->verification_status ?? 'unverified';
                                $accountStatus = $user->status ?? 'active';

                                $aiStatus = $user->ai_strategy_status ?? 'Inactive';
                                $aiName = $user->ai_strategy_name ?? null;
                                $aiOrder = $user->ai_strategy_order_no ?? null;
                                $aiPair = $user->ai_strategy_pair ?? null;
                                $aiAmount = $user->ai_strategy_amount ?? null;
                                $aiCurrentProfit = $user->ai_strategy_current_profit ?? null;
                                $aiTotalValue = $user->ai_strategy_total_value ?? null;
                                $aiRunStatus = $user->ai_strategy_run_status ?? null;
                                $aiUnlockAt = $user->ai_strategy_unlock_at ?? null;

                                $cooldownActive = !empty($user->ai_cooldown_active);
                                $cooldownUntil = $user->ai_cooldown_until ?? null;
                                $cooldownHoursLeft = $user->ai_cooldown_hours_left ?? 0;
                            @endphp

                            <tr>
                                <td>
                                    <div class="user-id">#{{ $user->id }}</div>
                                </td>

                                <td>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="muted">Account ID: {{ $user->account_id ?? '----' }}</div>
                                </td>

                                <td>
                                    <div class="email">{{ $user->email }}</div>
                                </td>

                                <td>
                                    <a href="/admin/wallet/{{ $user->id }}" class="btn wallet">Open Wallet</a>
                                </td>

                                <td>
                                    @if($accountStatus === 'active')
                                        <span class="badge active">Active</span>
                                    @else
                                        <span class="badge disabled">Disabled</span>
                                    @endif
                                </td>

                                <td>
                                    @if($verification === 'verified')
                                        <span class="badge ok">Verified</span>
                                    @elseif($verification === 'pending')
                                        <span class="badge pending">Pending</span>
                                    @elseif($verification === 'rejected')
                                        <span class="badge disabled">Rejected</span>
                                    @else
                                        <span class="badge no">Unverified</span>
                                    @endif
                                </td>

                                <td>
                                    @if($user->google2fa_enabled)
                                        <span class="badge ok">Enabled</span>
                                    @else
                                        <span class="badge no">Not Enabled</span>
                                    @endif
                                </td>

                                <td>
                                    @if($aiStatus === 'Active')
                                        <span class="badge ai-live">AI Strategy Active</span>
                                    @else
                                        <span class="badge ai-off">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    @if($aiStatus === 'Active')
                                        <div class="ai-box">
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Name</div>
                                                <div class="ai-box-value">{{ $aiName ?? '-' }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Order</div>
                                                <div class="ai-box-value">{{ $aiOrder ?? '-' }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Pair</div>
                                                <div class="ai-box-value">{{ $aiPair ?? '-' }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Amount</div>
                                                <div class="ai-box-value">${{ number_format((float) ($aiAmount ?? 0), 2) }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Current Profit</div>
                                                <div class="ai-box-value">${{ number_format((float) ($aiCurrentProfit ?? 0), 2) }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Total Value</div>
                                                <div class="ai-box-value">${{ number_format((float) ($aiTotalValue ?? 0), 2) }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Run Status</div>
                                                <div class="ai-box-value">{{ $aiRunStatus ?? '-' }}</div>
                                            </div>
                                            <div class="ai-box-row">
                                                <div class="ai-box-label">Unlock At</div>
                                                <div class="ai-box-value">
                                                    {{ $aiUnlockAt ? \Carbon\Carbon::parse($aiUnlockAt)->format('Y-m-d H:i') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="muted">No active AI strategy</span>
                                    @endif
                                </td>

                                <td>
                                    @if($cooldownActive)
                                        <span class="badge cooldown-on">Cooldown Active</span>
                                        <div class="cooldown-box">
                                            <div class="cooldown-row">
                                                <div class="cooldown-label">Hours Left</div>
                                                <div class="cooldown-value">{{ $cooldownHoursLeft }} h</div>
                                            </div>
                                            <div class="cooldown-row">
                                                <div class="cooldown-label">Until</div>
                                                <div class="cooldown-value">
                                                    {{ $cooldownUntil ? \Carbon\Carbon::parse($cooldownUntil)->format('Y-m-d H:i') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge cooldown-off">No Cooldown</span>
                                    @endif
                                </td>

                                <td>
                                    @if($user->withdraw_wallet_address)
                                        <span class="badge ok">Saved & Locked</span>
                                        <div class="address-box">
                                            {{ $user->withdraw_wallet_address }}
                                        </div>
                                        <div class="muted" style="margin-top:8px;">
                                            {{ $user->withdraw_wallet_network ?? 'TRC20' }}
                                        </div>
                                    @else
                                        <span class="badge no">Not Set</span>
                                    @endif
                                </td>

                                <td>
                                    @if($user->trading_password)
                                        <span class="badge ok">Set</span>
                                    @else
                                        <span class="badge no">Not Set</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-grid">

                                        @if($aiStatus === 'Active')
                                            <form method="POST" action="/admin/users/{{ $user->id }}/force-cancel-ai" class="action-form">
                                                @csrf
                                                <button
                                                    type="button"
                                                    class="btn ai-cancel open-action-modal"
                                                    data-action="ai-cancel"
                                                    data-title="Force Cancel AI Strategy"
                                                    data-text="You are about to cancel the active AI strategy for this user. The principal amount only will be returned to the wallet, profits will be removed, and the strategy will be closed by admin."
                                                    data-user="{{ $user->name }}"
                                                    data-userid="{{ $user->id }}"
                                                    data-email="{{ $user->email }}"
                                                    data-status="AI Force Cancelled"
                                                >
                                                    Force Cancel AI
                                                </button>
                                            </form>
                                        @endif

                                        @if($cooldownActive)
                                            <form method="POST" action="/admin/users/{{ $user->id }}/reset-ai-cooldown" class="action-form">
                                                @csrf
                                                <button
                                                    type="button"
                                                    class="btn cooldown open-action-modal"
                                                    data-action="cooldown"
                                                    data-title="Reset AI Cooldown"
                                                    data-text="You are about to remove the 48-hour AI cooldown for this user. After this action, the user will be allowed to start a new AI strategy immediately."
                                                    data-user="{{ $user->name }}"
                                                    data-userid="{{ $user->id }}"
                                                    data-email="{{ $user->email }}"
                                                    data-status="Cooldown Reset"
                                                >
                                                    Reset AI Cooldown
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="/admin/users/{{ $user->id }}/disable" class="action-form">
                                            @csrf
                                            <button
                                                type="button"
                                                class="btn disable open-action-modal"
                                                data-action="disable"
                                                data-title="Disable User"
                                                data-text="You are about to disable this user account. The user will lose normal access until the account is activated again."
                                                data-user="{{ $user->name }}"
                                                data-userid="{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-status="Disabled"
                                            >
                                                Disable
                                            </button>
                                        </form>

                                        <form method="POST" action="/admin/users/{{ $user->id }}/activate" class="action-form">
                                            @csrf
                                            <button
                                                type="button"
                                                class="btn activate open-action-modal"
                                                data-action="activate"
                                                data-title="Activate User"
                                                data-text="You are about to activate this user account and restore account access."
                                                data-user="{{ $user->name }}"
                                                data-userid="{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-status="Active"
                                            >
                                                Activate
                                            </button>
                                        </form>

                                        <form method="POST" action="/admin/users/{{ $user->id }}/reset-withdraw-address" class="action-form">
                                            @csrf
                                            <button
                                                type="button"
                                                class="btn reset open-action-modal"
                                                data-action="reset"
                                                data-title="Reset Withdraw Address"
                                                data-text="You are about to clear the saved and locked withdraw address for this user. The user will need to set a new address again."
                                                data-user="{{ $user->name }}"
                                                data-userid="{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-status="Address Reset"
                                            >
                                                Reset Address
                                            </button>
                                        </form>

                                        <form method="POST" action="/admin/users/{{ $user->id }}/reset-trading-password" class="action-form">
                                            @csrf
                                            <button
                                                type="button"
                                                class="btn reset open-action-modal"
                                                data-action="reset"
                                                data-title="Reset Trading Password"
                                                data-text="You are about to remove the trading password for this user. The user will need to set a new trading password again."
                                                data-user="{{ $user->name }}"
                                                data-userid="{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-status="Trading Password Reset"
                                            >
                                                Reset Trading Password
                                            </button>
                                        </form>

                                        <form method="POST" action="/admin/users/{{ $user->id }}" class="action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                class="btn delete open-action-modal"
                                                data-action="delete"
                                                data-title="Delete User"
                                                data-text="You are about to permanently delete this user account. This action is destructive and should only be used when absolutely necessary."
                                                data-user="{{ $user->name }}"
                                                data-userid="{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-status="Deleted"
                                            >
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="empty">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</div>

<div class="modal-overlay" id="actionModal">
    <div class="modal-box">
        <div class="modal-title" id="modalTitle">Confirm Action</div>
        <div class="modal-text" id="modalText">
            Please review the details before continuing.
        </div>

        <div class="modal-preview">
            <div class="modal-preview-row">
                <div class="modal-preview-label">User</div>
                <div class="modal-preview-value" id="previewUser">-</div>
            </div>
            <div class="modal-preview-row">
                <div class="modal-preview-label">User ID</div>
                <div class="modal-preview-value" id="previewUserId">-</div>
            </div>
            <div class="modal-preview-row">
                <div class="modal-preview-label">Email</div>
                <div class="modal-preview-value" id="previewEmail">-</div>
            </div>
            <div class="modal-preview-row">
                <div class="modal-preview-label">Result</div>
                <div class="modal-preview-value" id="previewStatus">-</div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="modal-btn cancel" id="cancelModalBtn">Cancel</button>
            <button type="button" class="modal-btn confirm" id="confirmModalBtn">Confirm</button>
        </div>
    </div>
</div>

<script>
    const actionModal = document.getElementById('actionModal');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const confirmModalBtn = document.getElementById('confirmModalBtn');
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const previewUser = document.getElementById('previewUser');
    const previewUserId = document.getElementById('previewUserId');
    const previewEmail = document.getElementById('previewEmail');
    const previewStatus = document.getElementById('previewStatus');

    let activeForm = null;

    document.querySelectorAll('.open-action-modal').forEach(button => {
        button.addEventListener('click', function () {
            activeForm = this.closest('form');

            const action = this.dataset.action;
            const title = this.dataset.title;
            const text = this.dataset.text;
            const user = this.dataset.user;
            const userId = this.dataset.userid;
            const email = this.dataset.email;
            const status = this.dataset.status;

            modalTitle.textContent = title || 'Confirm Action';
            modalText.textContent = text || 'Please review the details before continuing.';
            previewUser.textContent = user || '-';
            previewUserId.textContent = '#' + (userId || '-');
            previewEmail.textContent = email || '-';
            previewStatus.textContent = status || '-';

            confirmModalBtn.className = 'modal-btn confirm ' + action;

            if (action === 'activate') {
                confirmModalBtn.textContent = 'Activate Now';
            } else if (action === 'disable') {
                confirmModalBtn.textContent = 'Disable Now';
            } else if (action === 'delete') {
                confirmModalBtn.textContent = 'Delete Permanently';
            } else if (action === 'ai-cancel') {
                confirmModalBtn.textContent = 'Force Cancel AI';
            } else if (action === 'cooldown') {
                confirmModalBtn.textContent = 'Reset Cooldown';
            } else {
                confirmModalBtn.textContent = 'Confirm Action';
            }

            actionModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeActionModal() {
        actionModal.classList.remove('show');
        document.body.style.overflow = '';
        activeForm = null;
    }

    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', closeActionModal);
    }

    if (actionModal) {
        actionModal.addEventListener('click', function (e) {
            if (e.target === actionModal) {
                closeActionModal();
            }
        });
    }

    if (confirmModalBtn) {
        confirmModalBtn.addEventListener('click', function () {
            if (activeForm) {
                activeForm.submit();
            }
        });
    }
</script>

</body>
</html>