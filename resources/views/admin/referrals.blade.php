<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Referrals</title>
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
            --lime:#cbff47;
            --lime-soft:rgba(203,255,71,0.14);
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
                radial-gradient(circle at 88% 12%, rgba(203,255,71,0.12), transparent 26%),
                radial-gradient(circle at 18% 100%, rgba(56,189,248,0.08), transparent 30%),
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
            color:#111827;
            border:1px solid rgba(203,255,71,0.18);
            background:linear-gradient(180deg,#eaff9a 0%, #cbff47 100%);
            white-space:nowrap;
            box-shadow:0 10px 20px rgba(203,255,71,0.14);
        }

        .alert{
            border-radius:18px;
            padding:14px 16px;
            font-size:14px;
            font-weight:800;
            line-height:1.7;
            margin-bottom:18px;
        }

        .alert.success{
            background:rgba(34,197,94,0.12);
            color:#9df0b9;
            border:1px solid rgba(34,197,94,0.16);
        }

        .alert.error{
            background:rgba(239,68,68,0.12);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.16);
        }

        .stats-grid{
            display:grid;
            grid-template-columns:repeat(5, 1fr);
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
            font-size:32px;
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

        .stat-card.total::before,
        .stat-card.verified::before,
        .stat-card.deposited::before,
        .stat-card.qualified::before,
        .stat-card.agents::before{
            content:"";
            position:absolute;
            right:-22px;
            top:-22px;
            width:100px;
            height:100px;
            border-radius:50%;
        }

        .stat-card.total::before{
            background:radial-gradient(circle, rgba(56,189,248,0.16), transparent 70%);
        }

        .stat-card.verified::before{
            background:radial-gradient(circle, rgba(34,197,94,0.16), transparent 70%);
        }

        .stat-card.deposited::before{
            background:radial-gradient(circle, rgba(245,158,11,0.16), transparent 70%);
        }

        .stat-card.qualified::before{
            background:radial-gradient(circle, rgba(203,255,71,0.18), transparent 70%);
        }

        .stat-card.agents::before{
            background:radial-gradient(circle, rgba(139,92,246,0.18), transparent 70%);
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

        .search-bar{
            display:flex;
            gap:12px;
            margin-bottom:18px;
            flex-wrap:wrap;
        }

        .search-input{
            flex:1;
            min-width:260px;
            padding:14px 16px;
            border-radius:16px;
            border:1px solid rgba(255,255,255,0.08);
            background:rgba(255,255,255,0.03);
            color:#fff;
            font-size:14px;
            outline:none;
        }

        .search-input::placeholder{
            color:#8ea0b8;
        }

        .search-note{
            color:#8ea0b8;
            font-size:12px;
            line-height:1.6;
            margin-bottom:16px;
        }

        .table-wrap{
            overflow-x:auto;
            border-radius:22px;
            border:1px solid rgba(255,255,255,0.06);
            background:rgba(255,255,255,0.02);
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:1700px;
        }

        th, td{
            padding:16px 14px;
            text-align:left;
            border-bottom:1px solid rgba(255,255,255,0.06);
        }

        th{
            color:#9fb0c7;
            font-size:12px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.7px;
            background:rgba(255,255,255,0.02);
        }

        td{
            color:#edf2f7;
            font-size:14px;
            font-weight:700;
            vertical-align:middle;
        }

        tr:last-child td{
            border-bottom:none;
        }

        .user-box{
            display:flex;
            flex-direction:column;
            gap:4px;
        }

        .user-name{
            font-size:14px;
            font-weight:900;
            color:#fff;
        }

        .user-email{
            font-size:12px;
            color:#94a3b8;
            line-height:1.5;
            word-break:break-word;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:8px 12px;
            border-radius:999px;
            font-size:11px;
            font-weight:900;
            white-space:nowrap;
        }

        .badge.yes{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.22);
        }

        .badge.no{
            background:rgba(255,255,255,0.05);
            color:#cbd5e1;
            border:1px solid rgba(255,255,255,0.08);
        }

        .badge.qualified{
            background:var(--lime-soft);
            color:#ecfccb;
            border:1px solid rgba(203,255,71,0.18);
        }

        .badge.agent{
            background:var(--violet-soft);
            color:#ddd6fe;
            border:1px solid rgba(139,92,246,0.22);
        }

        .badge.level{
            background:var(--amber-soft);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.22);
        }

        .badge.reward-ready{
            background:var(--lime-soft);
            color:#ecfccb;
            border:1px solid rgba(203,255,71,0.18);
        }

        .badge.reward-done{
            background:var(--cyan-soft);
            color:#bae6fd;
            border:1px solid rgba(56,189,248,0.22);
        }

        .reward-form{
            margin:0;
        }

        .reward-btn{
            border:none;
            cursor:pointer;
            padding:10px 14px;
            border-radius:12px;
            font-size:12px;
            font-weight:900;
            color:#111827;
            background:linear-gradient(180deg,#eaff9a 0%, #cbff47 100%);
            box-shadow:0 10px 18px rgba(203,255,71,0.14);
            transition:.2s ease;
        }

        .reward-btn:hover{
            transform:translateY(-1px);
            box-shadow:0 14px 24px rgba(203,255,71,0.18);
        }

        .empty-state{
            border-radius:22px;
            padding:22px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            color:#cbd5e1;
            line-height:1.9;
        }

        .hidden-row{
            display:none;
        }

        @media (max-width:1200px){
            .stats-grid{
                grid-template-columns:repeat(2, 1fr);
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

            .stats-grid{
                grid-template-columns:1fr;
            }

            .search-input{
                min-width:100%;
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
            <a href="/admin/users">Users</a>
            <a href="/admin/deposits">Deposits</a>
            <a href="/admin/withdrawals">Withdrawals</a>
            <a href="/admin/support">Support</a>
            <a href="/admin/referrals" class="active">Referrals</a>
        </nav>

        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </aside>

    <main class="content">

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @php
            $totalReferrals = isset($referrals) ? $referrals->count() : 0;
            $verifiedReferrals = isset($referrals) ? $referrals->where('verification_status', 'Yes')->count() : 0;
            $depositedReferrals = isset($referrals) ? $referrals->where('deposit_status', 'Yes')->count() : 0;
            $qualifiedReferrals = isset($referrals) ? $referrals->where('qualified_status', 'Yes')->count() : 0;
            $agentCount = isset($referrals) ? $referrals->where('is_agent', 'Yes')->count() : 0;
        @endphp

        <div class="hero">
            <div class="hero-top">
                <div>
                    <h2>Referral Control Center</h2>
                    <p>
                        Full admin visibility over invited users, inviter chains, verification state, funding activity, reward levels, agent activation, weekly profit eligibility, and reward readiness. Since one boring table was apparently not enough.
                    </p>
                </div>

                <div class="hero-badge">Referral Network Active</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-label">Total Referrals</div>
                <div class="stat-value">{{ $totalReferrals }}</div>
                <div class="stat-foot">All users who joined using referral links</div>
            </div>

            <div class="stat-card verified">
                <div class="stat-label">Verified</div>
                <div class="stat-value">{{ $verifiedReferrals }}</div>
                <div class="stat-foot">Users with verified status</div>
            </div>

            <div class="stat-card deposited">
                <div class="stat-label">Deposited</div>
                <div class="stat-value">{{ $depositedReferrals }}</div>
                <div class="stat-foot">Users with approved deposit or funded wallet</div>
            </div>

            <div class="stat-card qualified">
                <div class="stat-label">Qualified</div>
                <div class="stat-value">{{ $qualifiedReferrals }}</div>
                <div class="stat-foot">Users meeting verification and funding conditions</div>
            </div>

            <div class="stat-card agents">
                <div class="stat-label">Agents</div>
                <div class="stat-value">{{ $agentCount }}</div>
                <div class="stat-foot">Users promoted into agent mode</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title">Referral Records</div>
            <div class="panel-sub">
                Search by name, email, invitee account, inviter account, level, or invited count. The reward button only appears when the user is actually eligible. Miracles do happen.
            </div>

            <div class="search-bar">
                <input
                    type="text"
                    id="referralSearch"
                    class="search-input"
                    placeholder="Search by name, email, invitee account, inviter account, level, invited count..."
                    onkeyup="filterReferralTable()"
                >
            </div>

            <div class="search-note">
                Live search works instantly inside the page without reload.
            </div>

            @if(isset($referrals) && count($referrals) > 0)
                <div class="table-wrap">
                    <table id="referralTable">
                        <thead>
                            <tr>
                                <th>Invitee</th>
                                <th>Invitee Account</th>
                                <th>Inviter Account</th>
                                <th>Invited Count</th>
                                <th>Joined Date</th>
                                <th>Verified</th>
                                <th>Deposited</th>
                                <th>Qualified</th>
                                <th>Reward Level</th>
                                <th>Agent</th>
                                <th>Weekly Profit</th>
                                <th>Reward Status</th>
                                <th>Reward Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals as $row)
                                @php
                                    $invitedCount = (int) ($row->invited_users_count ?? 0);
                                    $currentLevel = (int) ($row->referral_reward_level ?? 0);

                                    $nextRewardLevel = 0;
                                    $nextRewardText = 'Not Eligible';

                                    if ($invitedCount >= 30 && $currentLevel < 3) {
                                        $nextRewardLevel = 3;
                                        $nextRewardText = 'Eligible for Level 3';
                                    } elseif ($invitedCount >= 15 && $currentLevel < 2) {
                                        $nextRewardLevel = 2;
                                        $nextRewardText = 'Eligible for Level 2';
                                    } elseif ($invitedCount >= 5 && $currentLevel < 1) {
                                        $nextRewardLevel = 1;
                                        $nextRewardText = 'Eligible for Level 1';
                                    } elseif ($currentLevel >= 3) {
                                        $nextRewardText = 'Reward Granted';
                                    } elseif ($currentLevel >= 2 && $invitedCount < 30) {
                                        $nextRewardText = 'Reward Granted';
                                    } elseif ($currentLevel >= 1 && $invitedCount < 15) {
                                        $nextRewardText = 'Reward Granted';
                                    }
                                @endphp

                                <tr class="referral-row">
                                    <td data-search="{{ strtolower(($row->name ?? '') . ' ' . ($row->email ?? '')) }}">
                                        <div class="user-box">
                                            <div class="user-name">{{ $row->name ?? '-' }}</div>
                                            <div class="user-email">{{ $row->email ?? '-' }}</div>
                                        </div>
                                    </td>

                                    <td data-search="{{ strtolower((string) ($row->account_id ?? '')) }}">
                                        #{{ $row->account_id ?? '-' }}
                                    </td>

                                    <td data-search="{{ strtolower((string) ($row->inviter_account_id ?? '')) }}">
                                        #{{ $row->inviter_account_id ?? '-' }}
                                    </td>

                                    <td data-search="{{ strtolower((string) $invitedCount) }}">
                                        {{ $invitedCount }}
                                    </td>

                                    <td data-search="{{ strtolower(\Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i')) }}">
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}
                                    </td>

                                    <td data-search="{{ strtolower($row->verification_status ?? 'no') }}">
                                        @if(($row->verification_status ?? 'No') === 'Yes')
                                            <span class="badge yes">Yes</span>
                                        @else
                                            <span class="badge no">No</span>
                                        @endif
                                    </td>

                                    <td data-search="{{ strtolower($row->deposit_status ?? 'no') }}">
                                        @if(($row->deposit_status ?? 'No') === 'Yes')
                                            <span class="badge yes">Yes</span>
                                        @else
                                            <span class="badge no">No</span>
                                        @endif
                                    </td>

                                    <td data-search="{{ strtolower($row->qualified_status ?? 'no') }}">
                                        @if(($row->qualified_status ?? 'No') === 'Yes')
                                            <span class="badge qualified">Qualified</span>
                                        @else
                                            <span class="badge no">No</span>
                                        @endif
                                    </td>

                                    <td data-search="level {{ strtolower((string) $currentLevel) }}">
                                        <span class="badge level">
                                            Level {{ $currentLevel }}
                                        </span>
                                    </td>

                                    <td data-search="{{ strtolower($row->is_agent ?? 'no') }}">
                                        @if(($row->is_agent ?? 'No') === 'Yes')
                                            <span class="badge agent">Agent</span>
                                        @else
                                            <span class="badge no">No</span>
                                        @endif
                                    </td>

                                    <td data-search="{{ strtolower($row->weekly_profit_enabled ?? 'no') }}">
                                        @if(($row->weekly_profit_enabled ?? 'No') === 'Yes')
                                            <span class="badge yes">Enabled</span>
                                        @else
                                            <span class="badge no">No</span>
                                        @endif
                                    </td>

                                    <td data-search="{{ strtolower($nextRewardText) }}">
                                        @if($nextRewardLevel > 0)
                                            <span class="badge reward-ready">{{ $nextRewardText }}</span>
                                        @elseif($nextRewardText === 'Reward Granted')
                                            <span class="badge reward-done">Reward Granted</span>
                                        @else
                                            <span class="badge no">Not Eligible</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($nextRewardLevel > 0)
                                            <form method="POST" action="/admin/referral/reward/{{ $row->id }}" class="reward-form">
                                                @csrf
                                                <button type="submit" class="reward-btn">
                                                    Give Reward
                                                </button>
                                            </form>
                                        @elseif($nextRewardText === 'Reward Granted')
                                            <span class="badge reward-done">Completed</span>
                                        @else
                                            <span class="badge no">Locked</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="noSearchResults" class="empty-state" style="display:none; margin-top:16px;">
                    No matching referral records found for your search.
                </div>
            @else
                <div class="empty-state">
                    No referral records found yet. Either nobody has invited anyone yet, or human motivation has gone on vacation.
                </div>
            @endif
        </div>

    </main>

</div>

<script>
    function filterReferralTable() {
        const input = document.getElementById('referralSearch');
        const filter = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.referral-row');
        const noResults = document.getElementById('noSearchResults');

        let visibleCount = 0;

        rows.forEach((row) => {
            const rowText = row.innerText.toLowerCase();
            const customSearch = Array.from(row.querySelectorAll('[data-search]'))
                .map(el => el.getAttribute('data-search'))
                .join(' ')
                .toLowerCase();

            const fullText = rowText + ' ' + customSearch;

            if (fullText.includes(filter)) {
                row.classList.remove('hidden-row');
                visibleCount++;
            } else {
                row.classList.add('hidden-row');
            }
        });

        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }
</script>

</body>
</html>