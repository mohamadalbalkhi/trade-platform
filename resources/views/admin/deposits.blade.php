<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Deposits</title>
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
            --blue:#3b82f6;
            --blue-soft:rgba(59,130,246,0.14);
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
            flex-wrap:wrap;
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
            color:#fef3c7;
            border:1px solid rgba(245,158,11,0.22);
            background:var(--amber-soft);
            white-space:nowrap;
        }

        .search-box{
            margin-top:18px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }

        .search-input{
            flex:1;
            min-width:260px;
            border:none;
            outline:none;
            border-radius:16px;
            padding:14px 16px;
            background:rgba(255,255,255,0.04);
            border:1px solid rgba(255,255,255,0.08);
            color:#f8fafc;
            font-size:14px;
        }

        .search-input::placeholder{
            color:#93a4ba;
        }

        .search-btn,
        .clear-btn{
            border:none;
            cursor:pointer;
            padding:14px 16px;
            border-radius:16px;
            font-size:13px;
            font-weight:900;
            transition:.2s ease;
        }

        .search-btn{
            background:var(--cyan-soft);
            color:#e0f2fe;
            border:1px solid rgba(56,189,248,0.18);
        }

        .clear-btn{
            background:rgba(255,255,255,0.04);
            color:#e5e7eb;
            border:1px solid rgba(255,255,255,0.08);
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .search-btn:hover,
        .clear-btn:hover{
            transform:translateY(-1px);
            filter:brightness(1.05);
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
            min-width:2000px;
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

        .user-name{
            font-weight:900;
            color:#fff;
            margin-bottom:4px;
        }

        .deposit-id{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:6px 10px;
            border-radius:999px;
            background:var(--violet-soft);
            color:#ddd6fe;
            border:1px solid rgba(139,92,246,0.20);
            font-size:11px;
            font-weight:900;
            margin-top:4px;
        }

        .amount{
            font-size:18px;
            font-weight:900;
            color:#fff;
        }

        .amount.sub{
            font-size:14px;
            color:#cbd5e1;
            font-weight:800;
        }

        .muted{
            color:#93a4ba;
            font-size:12px;
            line-height:1.6;
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

        .badge.pending{
            background:var(--amber-soft);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.20);
        }

        .badge.approved{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.20);
        }

        .badge.rejected{
            background:var(--red-soft);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.20);
        }

        .method-chip{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:8px 12px;
            border-radius:14px;
            background:rgba(56,189,248,0.10);
            color:#bae6fd;
            border:1px solid rgba(56,189,248,0.16);
            font-size:12px;
            font-weight:800;
        }

        .wallet-box{
            max-width:260px;
            word-break:break-all;
            line-height:1.7;
            color:#dbe7f4;
            font-size:12px;
        }

        .registered-wallet-box{
            max-width:260px;
            word-break:break-all;
            line-height:1.7;
            color:#dbeafe;
            font-size:12px;
            background:var(--blue-soft);
            border:1px solid rgba(59,130,246,0.18);
            border-radius:16px;
            padding:10px 12px;
        }

        .not-set{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:7px 10px;
            border-radius:12px;
            background:rgba(255,255,255,0.05);
            color:#cbd5e1;
            font-size:11px;
            font-weight:800;
            border:1px solid rgba(255,255,255,0.08);
        }

        .action-grid{
            display:flex;
            flex-wrap:wrap;
            gap:8px;
        }

        .action-grid form{
            display:inline-flex;
        }

        .btn{
            border:none;
            cursor:pointer;
            padding:10px 12px;
            border-radius:14px;
            font-size:12px;
            font-weight:900;
            transition:.2s ease;
            white-space:nowrap;
        }

        .btn.approve{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.20);
        }

        .btn.reject{
            background:var(--red-soft);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.20);
        }

        .btn.pending{
            background:var(--amber-soft);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.20);
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

            .search-box{
                flex-direction:column;
            }

            .search-input,
            .search-btn,
            .clear-btn{
                width:100%;
            }
        }
    </style>
</head>
<body>

@php
    $search = request('search');
    $filteredDeposits = collect($deposits);

    if ($search) {
        $searchLower = mb_strtolower($search);

        $filteredDeposits = $filteredDeposits->filter(function ($deposit) use ($searchLower) {
            $registeredWallet = optional(\App\Models\User::find($deposit->user_id))->withdraw_wallet_address;

            return str_contains(mb_strtolower((string) ($deposit->deposit_id ?? '')), $searchLower)
                || str_contains(mb_strtolower((string) ($deposit->user_name ?? '')), $searchLower)
                || str_contains(mb_strtolower((string) ($deposit->amount ?? '')), $searchLower)
                || str_contains(mb_strtolower((string) ($deposit->requested_amount ?? '')), $searchLower)
                || str_contains(mb_strtolower((string) ($deposit->status ?? '')), $searchLower)
                || str_contains(mb_strtolower((string) ($registeredWallet ?? '')), $searchLower);
        });
    }
@endphp

<div class="page">

    <aside class="sidebar">
        <div class="brand">
            <h1>Dawn<span>EX</span></h1>
            <p>EXECUTIVE ADMIN PANEL</p>
        </div>

        <nav class="nav">
            <a href="/admin">Dashboard</a>
            <a href="/admin/users">Users</a>
            <a href="/admin/deposits" class="active">Deposits</a>
            <a href="/admin/withdrawals">Withdrawals</a>
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
                    <h2>Deposit Requests</h2>
                    <p>
                        Review incoming deposit requests, search instantly by Deposit ID, user name, amount, or registered wallet address, then match the exact amount and the user’s registered wallet with full approval control.
                    </p>
                </div>

                <div class="hero-badge">Finance Review</div>
            </div>

            <form method="GET" action="/admin/deposits" class="search-box">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Search by Deposit ID, user name, amount, status, or registered wallet"
                    value="{{ request('search') }}"
                >

                <button type="submit" class="search-btn">Search</button>

                @if(request('search'))
                    <a href="/admin/deposits" class="clear-btn">Clear</a>
                @endif
            </form>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Deposit ID</th>
                            <th>Requested Amount</th>
                            <th>Exact Amount</th>
                            <th>Method</th>
                            <th>Platform Wallet</th>
                            <th>Registered User Wallet</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredDeposits as $deposit)
                            @php
                                $depositUser = \App\Models\User::find($deposit->user_id);
                                $registeredWallet = $depositUser->withdraw_wallet_address ?? null;
                            @endphp

                            <tr>
                                <td>
                                    <div class="user-name">{{ $deposit->user_name }}</div>
                                    <div class="muted">User ID: {{ $deposit->user_id ?? '-' }}</div>
                                </td>

                                <td>
                                    <div class="deposit-id">{{ $deposit->deposit_id ?? ('DEP-' . $deposit->id) }}</div>
                                </td>

                                <td>
                                    <div class="amount sub">
                                        ${{ number_format((float) ($deposit->requested_amount ?? $deposit->amount), 2) }}
                                    </div>
                                    <div class="muted">Requested by user</div>
                                </td>

                                <td>
                                    <div class="amount">
                                        ${{ number_format((float) $deposit->amount, 2) }}
                                    </div>
                                    <div class="muted">Must match exactly</div>
                                </td>

                                <td>
                                    <span class="method-chip">{{ $deposit->method }}</span>
                                </td>

                                <td>
                                    <div class="wallet-box">
                                        {{ $deposit->wallet_address ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    @if($registeredWallet)
                                        <div class="registered-wallet-box">
                                            {{ $registeredWallet }}
                                        </div>
                                    @else
                                        <span class="not-set">Not Registered</span>
                                    @endif
                                </td>

                                <td>
                                    @if($deposit->status === 'Pending')
                                        <span class="badge pending">Pending</span>
                                    @elseif($deposit->status === 'Approved')
                                        <span class="badge approved">Approved</span>
                                    @else
                                        <span class="badge rejected">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="muted">{{ $deposit->created_at }}</div>
                                </td>

                                <td>
                                    <div class="action-grid">
                                        @if($deposit->status !== 'Approved')
                                            <form method="POST" action="/admin/deposits/{{ $deposit->id }}/status" onsubmit="return confirm('Approve this deposit request?')">
                                                @csrf
                                                <input type="hidden" name="status" value="Approved">
                                                <button class="btn approve">Approve</button>
                                            </form>
                                        @endif

                                        @if($deposit->status !== 'Rejected')
                                            <form method="POST" action="/admin/deposits/{{ $deposit->id }}/status" onsubmit="return confirm('Reject this deposit request?')">
                                                @csrf
                                                <input type="hidden" name="status" value="Rejected">
                                                <button class="btn reject">Reject</button>
                                            </form>
                                        @endif

                                        @if($deposit->status !== 'Pending')
                                            <form method="POST" action="/admin/deposits/{{ $deposit->id }}/status" onsubmit="return confirm('Set this deposit request back to pending?')">
                                                @csrf
                                                <input type="hidden" name="status" value="Pending">
                                                <button class="btn pending">Set Pending</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty">No deposits found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</div>

</body>
</html>