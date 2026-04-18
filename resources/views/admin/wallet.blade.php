<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Wallet Control</title>
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
                radial-gradient(circle at bottom right, rgba(139,92,246,0.09), transparent 24%),
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

        .hero{
            position:relative;
            overflow:hidden;
            margin-bottom:22px;
            padding:28px;
            border-radius:30px;
            background:
                radial-gradient(circle at 88% 10%, rgba(56,189,248,0.13), transparent 24%),
                radial-gradient(circle at 18% 100%, rgba(245,158,11,0.10), transparent 30%),
                linear-gradient(180deg, rgba(18,29,49,0.98) 0%, rgba(12,20,35,0.99) 100%);
            border:1px solid var(--line);
            box-shadow:0 20px 44px rgba(0,0,0,0.28);
        }

        .hero-top{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:18px;
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

        .top-grid{
            display:grid;
            grid-template-columns:1.1fr .9fr;
            gap:18px;
            margin-bottom:18px;
        }

        .panel{
            border-radius:28px;
            padding:22px;
            background:linear-gradient(180deg, rgba(18,29,49,0.98) 0%, rgba(12,20,35,0.99) 100%);
            border:1px solid var(--line);
            box-shadow:0 18px 40px rgba(0,0,0,0.24);
        }

        .panel-title{
            font-size:22px;
            font-weight:900;
            margin-bottom:8px;
            color:#fff;
        }

        .panel-sub{
            color:var(--muted);
            font-size:13px;
            line-height:1.7;
            margin-bottom:18px;
        }

        .user-card{
            display:flex;
            align-items:center;
            gap:16px;
        }

        .avatar{
            width:72px;
            height:72px;
            border-radius:22px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(180deg, #1f3358 0%, #15243c 100%);
            border:1px solid rgba(56,189,248,0.18);
            color:#e0f2fe;
            font-size:24px;
            font-weight:900;
            flex:0 0 72px;
            box-shadow:0 10px 24px rgba(0,0,0,0.20);
        }

        .user-meta{
            display:flex;
            flex-direction:column;
            gap:6px;
            min-width:0;
        }

        .user-name{
            font-size:24px;
            font-weight:900;
            color:#fff;
            word-break:break-word;
        }

        .user-line{
            color:#9eb0c7;
            font-size:13px;
            line-height:1.6;
            word-break:break-word;
        }

        .chip-row{
            margin-top:14px;
            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }

        .chip{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:9px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            white-space:nowrap;
        }

        .chip.id{
            background:var(--violet-soft);
            color:#ddd6fe;
            border:1px solid rgba(139,92,246,0.22);
        }

        .chip.wallet{
            background:var(--cyan-soft);
            color:#bae6fd;
            border:1px solid rgba(56,189,248,0.22);
        }

        .balance-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:14px;
        }

        .balance-card{
            position:relative;
            overflow:hidden;
            border-radius:24px;
            padding:18px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            box-shadow:inset 0 1px 0 rgba(255,255,255,0.02);
        }

        .balance-card::before{
            content:"";
            position:absolute;
            right:-18px;
            top:-18px;
            width:90px;
            height:90px;
            border-radius:50%;
        }

        .balance-card.usd::before{
            background:radial-gradient(circle, rgba(34,197,94,0.16), transparent 70%);
        }

        .balance-card.btc::before{
            background:radial-gradient(circle, rgba(245,158,11,0.16), transparent 70%);
        }

        .balance-label{
            position:relative;
            z-index:2;
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:.6px;
            font-weight:800;
            color:#9db0c6;
            margin-bottom:10px;
        }

        .balance-value{
            position:relative;
            z-index:2;
            font-size:30px;
            font-weight:900;
            color:#fff;
            line-height:1.2;
            word-break:break-word;
        }

        .balance-foot{
            position:relative;
            z-index:2;
            margin-top:8px;
            font-size:12px;
            color:#8ea2b8;
            line-height:1.5;
        }

        .form-panel{
            margin-top:0;
        }

        .form-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:16px;
            margin-bottom:18px;
        }

        .input-group{
            display:flex;
            flex-direction:column;
            gap:8px;
        }

        .input-label{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            font-size:14px;
            font-weight:900;
            color:#f8fafc;
        }

        .input-note{
            font-size:11px;
            color:#8ea2b8;
            font-weight:700;
        }

        .input{
            width:100%;
            border:none;
            outline:none;
            border-radius:18px;
            padding:15px 16px;
            background:#0f1729;
            color:#fff;
            font-size:15px;
            border:1px solid rgba(255,255,255,0.06);
            transition:.2s ease;
            box-shadow:inset 0 1px 0 rgba(255,255,255,0.02);
        }

        .input::placeholder{
            color:#6f839b;
        }

        .input:focus{
            border-color:rgba(56,189,248,0.35);
            box-shadow:0 0 0 3px rgba(56,189,248,0.08);
        }

        .info-box{
            border-radius:22px;
            padding:16px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            margin-bottom:18px;
        }

        .info-title{
            font-size:14px;
            font-weight:900;
            color:#fff;
            margin-bottom:8px;
        }

        .info-text{
            color:#9db0c6;
            font-size:13px;
            line-height:1.75;
        }

        .action-row{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }

        .btn{
            border:none;
            cursor:pointer;
            text-decoration:none;
            padding:14px 18px;
            border-radius:18px;
            font-size:14px;
            font-weight:900;
            transition:.2s ease;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .btn.primary{
            background:linear-gradient(180deg, #4cc9ff 0%, #38bdf8 100%);
            color:#07111f;
            box-shadow:0 12px 24px rgba(56,189,248,0.18);
        }

        .btn.primary:hover{
            transform:translateY(-1px);
            box-shadow:0 16px 28px rgba(56,189,248,0.24);
        }

        .btn.secondary{
            background:rgba(255,255,255,0.05);
            color:#e5e7eb;
            border:1px solid rgba(255,255,255,0.08);
        }

        .btn.secondary:hover{
            transform:translateY(-1px);
            border-color:rgba(56,189,248,0.20);
            color:#e0f2fe;
        }

        .wallet-preview{
            margin-top:10px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:14px;
        }

        .preview-card{
            border-radius:20px;
            padding:16px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
        }

        .preview-label{
            color:#9eb0c7;
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:.6px;
            font-weight:800;
            margin-bottom:8px;
        }

        .preview-value{
            color:#fff;
            font-size:18px;
            font-weight:900;
            word-break:break-word;
        }

        @media (max-width:1100px){
            .top-grid{
                grid-template-columns:1fr;
            }
        }

        @media (max-width:980px){
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

            .form-grid,
            .balance-grid,
            .wallet-preview{
                grid-template-columns:1fr;
            }

            .user-card{
                align-items:flex-start;
            }

            .action-row{
                flex-direction:column;
            }

            .btn{
                width:100%;
            }
        }
    </style>
</head>
<body>

@php
    $initials = strtoupper(substr($user->name ?? 'U', 0, 2));
@endphp

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
        </nav>

        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </aside>

    <main class="content">

        <div class="hero">
            <div class="hero-top">
                <div>
                    <h2>Wallet Control</h2>
                    <p>
                        Adjust user wallet balances, review holdings, and apply precise admin-side updates to USD and BTC balances from a controlled finance interface.
                    </p>
                </div>
                <div class="hero-badge">Balance Operations</div>
            </div>
        </div>

        <div class="top-grid">
            <div class="panel">
                <div class="panel-title">User Profile</div>
                <div class="panel-sub">
                    Active wallet owner details and account identity for the selected record.
                </div>

                <div class="user-card">
                    <div class="avatar">{{ $initials }}</div>

                    <div class="user-meta">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-line">{{ $user->email }}</div>
                        <div class="user-line">User ID: #{{ $user->id }}</div>
                        <div class="user-line">Account ID: {{ $user->account_id ?? '----' }}</div>
                    </div>
                </div>

                <div class="chip-row">
                    <div class="chip id">Admin Wallet Access</div>
                    <div class="chip wallet">Live Balance Record</div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-title">Current Holdings</div>
                <div class="panel-sub">
                    Snapshot of the current stored balances before any admin update.
                </div>

                <div class="balance-grid">
                    <div class="balance-card usd">
                        <div class="balance-label">USD Balance</div>
                        <div class="balance-value">${{ number_format((float) ($wallet->balance ?? 0), 2) }}</div>
                        <div class="balance-foot">Fiat wallet amount currently assigned to this user.</div>
                    </div>

                    <div class="balance-card btc">
                        <div class="balance-label">BTC Balance</div>
                        <div class="balance-value">{{ number_format((float) ($wallet->btc_balance ?? 0), 8) }}</div>
                        <div class="balance-foot">Bitcoin balance stored for the user wallet.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel form-panel">
            <div class="panel-title">Update Wallet Balances</div>
            <div class="panel-sub">
                Enter the new wallet values carefully. This action directly updates the saved balances for the selected user.
            </div>

            <form method="POST" action="/admin/wallet/{{ $user->id }}/update">
                @csrf

                <div class="form-grid">
                    <div class="input-group">
                        <label class="input-label">
                            <span>USD Balance</span>
                            <span class="input-note">2 decimal format</span>
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            name="balance"
                            value="{{ $wallet->balance }}"
                            class="input"
                            placeholder="Enter USD balance"
                            required
                        >
                    </div>

                    <div class="input-group">
                        <label class="input-label">
                            <span>BTC Balance</span>
                            <span class="input-note">8 decimal format</span>
                        </label>
                        <input
                            type="number"
                            step="0.00000001"
                            name="btc_balance"
                            value="{{ $wallet->btc_balance }}"
                            class="input"
                            placeholder="Enter BTC balance"
                            required
                        >
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-title">Admin Notice</div>
                    <div class="info-text">
                        Any values submitted here will overwrite the user wallet balances directly. Review both USD and BTC values before saving changes to avoid finance-side inconsistencies.
                    </div>
                </div>

                <div class="wallet-preview">
                    <div class="preview-card">
                        <div class="preview-label">Current USD</div>
                        <div class="preview-value">${{ number_format((float) ($wallet->balance ?? 0), 2) }}</div>
                    </div>

                    <div class="preview-card">
                        <div class="preview-label">Current BTC</div>
                        <div class="preview-value">{{ number_format((float) ($wallet->btc_balance ?? 0), 8) }}</div>
                    </div>
                </div>

                <div class="action-row" style="margin-top:20px;">
                    <button class="btn primary" type="submit">
                        Update Wallet
                    </button>

                    <a href="/admin/users" class="btn secondary">
                        Back to Users
                    </a>
                </div>
            </form>
        </div>

    </main>
</div>

</body>
</html>