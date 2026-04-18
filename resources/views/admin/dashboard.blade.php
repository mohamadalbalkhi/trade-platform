<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Dashboard</title>
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
                radial-gradient(circle at bottom right, rgba(139,92,246,0.08), transparent 24%),
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
            padding:26px;
            border-radius:30px;
            background:
                radial-gradient(circle at 88% 12%, rgba(56,189,248,0.12), transparent 26%),
                radial-gradient(circle at 18% 100%, rgba(245,158,11,0.10), transparent 30%),
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
            font-size:36px;
            font-weight:900;
            margin-bottom:8px;
        }

        .hero p{
            color:var(--muted);
            font-size:14px;
            line-height:1.75;
            max-width:650px;
        }

        .hero-badge{
            padding:10px 14px;
            border-radius:16px;
            font-size:12px;
            font-weight:900;
            color:#fef3c7;
            border:1px solid rgba(245,158,11,0.22);
            background:var(--amber-soft);
            white-space:nowrap;
        }

        .stats-grid{
            display:grid;
            grid-template-columns:repeat(4, 1fr);
            gap:16px;
            margin-bottom:22px;
        }

        .stat-card{
            position:relative;
            overflow:hidden;
            border-radius:24px;
            padding:20px;
            background:linear-gradient(180deg, rgba(19,31,52,0.98) 0%, rgba(12,21,36,0.99) 100%);
            border:1px solid var(--line);
            box-shadow:0 16px 36px rgba(0,0,0,0.24);
        }

        .stat-card.cyan::before,
        .stat-card.amber::before,
        .stat-card.green::before,
        .stat-card.violet::before{
            content:"";
            position:absolute;
            right:-22px;
            top:-22px;
            width:100px;
            height:100px;
            border-radius:50%;
        }

        .stat-card.cyan::before{
            background:radial-gradient(circle, rgba(56,189,248,0.16), transparent 70%);
        }

        .stat-card.amber::before{
            background:radial-gradient(circle, rgba(245,158,11,0.16), transparent 70%);
        }

        .stat-card.green::before{
            background:radial-gradient(circle, rgba(34,197,94,0.16), transparent 70%);
        }

        .stat-card.violet::before{
            background:radial-gradient(circle, rgba(139,92,246,0.16), transparent 70%);
        }

        .stat-label{
            position:relative;
            z-index:2;
            color:#9fb0c7;
            font-size:12px;
            font-weight:800;
            letter-spacing:.6px;
            text-transform:uppercase;
            margin-bottom:12px;
        }

        .stat-value{
            position:relative;
            z-index:2;
            font-size:34px;
            font-weight:900;
            color:#fff;
            margin-bottom:8px;
        }

        .stat-foot{
            position:relative;
            z-index:2;
            font-size:12px;
            color:#8ea0b8;
            line-height:1.6;
        }

        .dashboard-grid{
            display:grid;
            grid-template-columns:1.2fr .8fr;
            gap:18px;
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
        }

        .panel-sub{
            color:var(--muted);
            font-size:13px;
            line-height:1.7;
            margin-bottom:18px;
        }

        .quick-grid{
            display:grid;
            grid-template-columns:repeat(5, 1fr);
            gap:12px;
        }

        .quick-card{
            border-radius:22px;
            padding:18px 14px;
            text-decoration:none;
            background:var(--white-soft);
            border:1px solid rgba(255,255,255,0.06);
            transition:.2s ease;
        }

        .quick-card:hover{
            transform:translateY(-2px);
            border-color:rgba(56,189,248,0.18);
            background:rgba(56,189,248,0.05);
        }

        .quick-icon{
            width:50px;
            height:50px;
            border-radius:16px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
            font-weight:900;
            margin-bottom:12px;
            background:rgba(56,189,248,0.12);
            border:1px solid rgba(56,189,248,0.14);
            color:#bae6fd;
        }

        .quick-name{
            font-size:15px;
            font-weight:900;
            color:#fff;
            margin-bottom:6px;
        }

        .quick-sub{
            color:#94a3b8;
            font-size:12px;
            line-height:1.6;
        }

        .status-list{
            display:flex;
            flex-direction:column;
            gap:12px;
        }

        .status-item{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:14px 16px;
            border-radius:20px;
            background:var(--white-soft);
            border:1px solid rgba(255,255,255,0.06);
        }

        .status-item-left{
            display:flex;
            flex-direction:column;
            gap:4px;
        }

        .status-name{
            font-size:15px;
            font-weight:900;
            color:#fff;
        }

        .status-sub{
            color:#94a3b8;
            font-size:12px;
            line-height:1.5;
        }

        .status-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:8px 12px;
            border-radius:999px;
            font-size:11px;
            font-weight:900;
            white-space:nowrap;
        }

        .status-badge.online{
            color:#bbf7d0;
            background:var(--green-soft);
            border:1px solid rgba(34,197,94,0.22);
        }

        .status-badge.monitor{
            color:#bae6fd;
            background:var(--cyan-soft);
            border:1px solid rgba(56,189,248,0.22);
        }

        .status-badge.review{
            color:#fde68a;
            background:var(--amber-soft);
            border:1px solid rgba(245,158,11,0.22);
        }

        @media (max-width:1300px){
            .quick-grid{
                grid-template-columns:repeat(3, 1fr);
            }
        }

        @media (max-width:1200px){
            .quick-grid{
                grid-template-columns:repeat(2, 1fr);
            }
        }

        @media (max-width:1100px){
            .stats-grid{
                grid-template-columns:repeat(2, 1fr);
            }

            .dashboard-grid{
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
                font-size:30px;
            }
        }

        @media (max-width:700px){
            .hero-top{
                flex-direction:column;
            }

            .stats-grid,
            .quick-grid{
                grid-template-columns:1fr;
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
            <a href="/admin" class="active">Dashboard</a>
            <a href="/admin/users">Users</a>
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
        <div class="hero">
            <div class="hero-top">
                <div>
                    <h2>Executive Dashboard</h2>
                    <p>
                        Central operations overview for account activity, deposit flow, withdrawal approvals, trading records, support workflow, and referral management across the platform.
                    </p>
                </div>

                <div class="hero-badge">Control Room Active</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card cyan">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $users }}</div>
                <div class="stat-foot">Registered platform accounts</div>
            </div>

            <div class="stat-card amber">
                <div class="stat-label">Deposits</div>
                <div class="stat-value">{{ $deposits }}</div>
                <div class="stat-foot">Deposit requests tracked in system</div>
            </div>

            <div class="stat-card green">
                <div class="stat-label">Withdrawals</div>
                <div class="stat-value">{{ $withdrawals }}</div>
                <div class="stat-foot">Withdrawal requests under management</div>
            </div>

            <div class="stat-card violet">
                <div class="stat-label">Trades</div>
                <div class="stat-value">{{ $trades }}</div>
                <div class="stat-foot">Recorded trading operations</div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="panel">
                <div class="panel-title">Quick Control</div>
                <div class="panel-sub">
                    Jump into the most critical admin areas without wasting time on decorative nonsense.
                </div>

                <div class="quick-grid">
                    <a href="/admin/users" class="quick-card">
                        <div class="quick-icon">👤</div>
                        <div class="quick-name">Users</div>
                        <div class="quick-sub">Control accounts, reset security data, and manage access.</div>
                    </a>

                    <a href="/admin/deposits" class="quick-card">
                        <div class="quick-icon">↓</div>
                        <div class="quick-name">Deposits</div>
                        <div class="quick-sub">Review incoming deposit requests and approval flow.</div>
                    </a>

                    <a href="/admin/withdrawals" class="quick-card">
                        <div class="quick-icon">↑</div>
                        <div class="quick-name">Withdrawals</div>
                        <div class="quick-sub">Inspect payout requests and finalize admin decisions.</div>
                    </a>

                    <a href="/admin/support" class="quick-card">
                        <div class="quick-icon">💬</div>
                        <div class="quick-name">Support</div>
                        <div class="quick-sub">Review user tickets, send replies, and manage support flow.</div>
                    </a>

                    <a href="/admin/referrals" class="quick-card">
                        <div class="quick-icon">🔗</div>
                        <div class="quick-name">Referrals</div>
                        <div class="quick-sub">Track inviters, qualified users, rewards, and agent progress.</div>
                    </a>
                </div>
            </div>

            <div class="panel">
                <div class="panel-title">Platform Status</div>
                <div class="panel-sub">
                    High-level operational visibility for the admin environment.
                </div>

                <div class="status-list">
                    <div class="status-item">
                        <div class="status-item-left">
                            <div class="status-name">Admin Core</div>
                            <div class="status-sub">Dashboard, routes, and management tools available.</div>
                        </div>
                        <div class="status-badge online">Online</div>
                    </div>

                    <div class="status-item">
                        <div class="status-item-left">
                            <div class="status-name">Security Controls</div>
                            <div class="status-sub">Verification, 2FA, and password reset tools active.</div>
                        </div>
                        <div class="status-badge monitor">Monitoring</div>
                    </div>

                    <div class="status-item">
                        <div class="status-item-left">
                            <div class="status-name">Finance Queue</div>
                            <div class="status-sub">Deposits and withdrawals require review workflow.</div>
                        </div>
                        <div class="status-badge review">Review Queue</div>
                    </div>

                    <div class="status-item">
                        <div class="status-item-left">
                            <div class="status-name">Support Center</div>
                            <div class="status-sub">User tickets and reply workflow available for review.</div>
                        </div>
                        <div class="status-badge online">Active</div>
                    </div>

                    <div class="status-item">
                        <div class="status-item-left">
                            <div class="status-name">Referral Engine</div>
                            <div class="status-sub">Referral tracking, rewards, and agent progression available.</div>
                        </div>
                        <div class="status-badge monitor">Running</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

</body>
</html>