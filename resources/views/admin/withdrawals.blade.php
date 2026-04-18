<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Withdrawals</title>
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
            font-size:34px;
            font-weight:900;
            margin-bottom:8px;
        }

        .hero p{
            color:var(--muted);
            font-size:14px;
            line-height:1.75;
            max-width:700px;
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
            min-width:1500px;
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

        .amount{
            font-size:18px;
            font-weight:900;
            color:#fff;
        }

        .muted{
            color:#93a4ba;
            font-size:12px;
            line-height:1.6;
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

        .address-box{
            max-width:460px;
            word-break:break-word;
            white-space:pre-wrap;
            line-height:1.7;
            color:#e8eef7;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            border-radius:16px;
            padding:12px 14px;
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
            width:min(100%, 480px);
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

        .modal-btn.confirm.approve{
            background:linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
        }

        .modal-btn.confirm.reject{
            background:linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        }

        .modal-btn.confirm.pending{
            background:linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        }

        .modal-btn:hover{
            transform:translateY(-1px);
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

@php
    use App\Models\User;
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
            <a href="/admin/deposits">Deposits</a>
            <a href="/admin/withdrawals" class="active">Withdrawals</a>
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
                    <h2>Withdrawal Requests</h2>
                    <p>
                        Review payout requests, inspect destination details, and control each withdrawal through the approval workflow with a cleaner admin experience.
                    </p>
                </div>

                <div class="hero-badge">Payout Review</div>
            </div>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Destination Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            @php
                                $withdrawUser = User::where('name', $withdrawal->user_name)->first();
                            @endphp

                            <tr>
                                <td>
                                    <div class="user-name">{{ $withdrawal->user_name }}</div>
                                    <div class="muted">
                                        User ID: #{{ $withdrawUser?->id ?? 'N/A' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="amount">${{ number_format((float) $withdrawal->amount, 2) }}</div>
                                </td>

                                <td>
                                    <span class="method-chip">{{ $withdrawal->method }}</span>
                                </td>

                                <td>
                                    <div class="address-box">{{ $withdrawal->wallet_address }}</div>
                                </td>

                                <td>
                                    @if($withdrawal->status === 'Pending')
                                        <span class="badge pending">Pending</span>
                                    @elseif($withdrawal->status === 'Approved')
                                        <span class="badge approved">Approved</span>
                                    @else
                                        <span class="badge rejected">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-grid">
                                        @if($withdrawal->status !== 'Approved')
                                            <form method="POST" action="/admin/withdrawals/{{ $withdrawal->id }}/status" class="status-form">
                                                @csrf
                                                <input type="hidden" name="status" value="Approved">
                                                <button
                                                    type="button"
                                                    class="btn approve open-status-modal"
                                                    data-action="approve"
                                                    data-user="{{ $withdrawal->user_name }}"
                                                    data-user-id="{{ $withdrawUser?->id ?? 'N/A' }}"
                                                    data-amount="${{ number_format((float) $withdrawal->amount, 2) }}"
                                                    data-method="{{ $withdrawal->method }}"
                                                    data-status="Approved"
                                                >
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if($withdrawal->status !== 'Rejected')
                                            <form method="POST" action="/admin/withdrawals/{{ $withdrawal->id }}/status" class="status-form">
                                                @csrf
                                                <input type="hidden" name="status" value="Rejected">
                                                <button
                                                    type="button"
                                                    class="btn reject open-status-modal"
                                                    data-action="reject"
                                                    data-user="{{ $withdrawal->user_name }}"
                                                    data-user-id="{{ $withdrawUser?->id ?? 'N/A' }}"
                                                    data-amount="${{ number_format((float) $withdrawal->amount, 2) }}"
                                                    data-method="{{ $withdrawal->method }}"
                                                    data-status="Rejected"
                                                >
                                                    Reject
                                                </button>
                                            </form>
                                        @endif

                                        @if($withdrawal->status !== 'Pending')
                                            <form method="POST" action="/admin/withdrawals/{{ $withdrawal->id }}/status" class="status-form">
                                                @csrf
                                                <input type="hidden" name="status" value="Pending">
                                                <button
                                                    type="button"
                                                    class="btn pending open-status-modal"
                                                    data-action="pending"
                                                    data-user="{{ $withdrawal->user_name }}"
                                                    data-user-id="{{ $withdrawUser?->id ?? 'N/A' }}"
                                                    data-amount="${{ number_format((float) $withdrawal->amount, 2) }}"
                                                    data-method="{{ $withdrawal->method }}"
                                                    data-status="Pending"
                                                >
                                                    Set Pending
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">No withdrawals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</div>

<div class="modal-overlay" id="statusModal">
    <div class="modal-box">
        <div class="modal-title" id="modalTitle">Confirm Action</div>
        <div class="modal-text" id="modalText">
            Please review the action details before continuing.
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
                <div class="modal-preview-label">Amount</div>
                <div class="modal-preview-value" id="previewAmount">-</div>
            </div>
            <div class="modal-preview-row">
                <div class="modal-preview-label">Method</div>
                <div class="modal-preview-value" id="previewMethod">-</div>
            </div>
            <div class="modal-preview-row">
                <div class="modal-preview-label">New Status</div>
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
    const statusModal = document.getElementById('statusModal');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const confirmModalBtn = document.getElementById('confirmModalBtn');
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const previewUser = document.getElementById('previewUser');
    const previewUserId = document.getElementById('previewUserId');
    const previewAmount = document.getElementById('previewAmount');
    const previewMethod = document.getElementById('previewMethod');
    const previewStatus = document.getElementById('previewStatus');

    let activeForm = null;

    document.querySelectorAll('.open-status-modal').forEach(button => {
        button.addEventListener('click', function () {
            activeForm = this.closest('form');

            const action = this.dataset.action;
            const user = this.dataset.user;
            const userId = this.dataset.userId;
            const amount = this.dataset.amount;
            const method = this.dataset.method;
            const status = this.dataset.status;

            previewUser.textContent = user || '-';
            previewUserId.textContent = '#' + (userId || 'N/A');
            previewAmount.textContent = amount || '-';
            previewMethod.textContent = method || '-';
            previewStatus.textContent = status || '-';

            confirmModalBtn.className = 'modal-btn confirm ' + action;

            if (action === 'approve') {
                modalTitle.textContent = 'Approve Withdrawal';
                modalText.textContent = 'You are about to approve this withdrawal request. Please make sure the payout details are correct.';
                confirmModalBtn.textContent = 'Approve Now';
            } else if (action === 'reject') {
                modalTitle.textContent = 'Reject Withdrawal';
                modalText.textContent = 'You are about to reject this withdrawal request. Please confirm before continuing.';
                confirmModalBtn.textContent = 'Reject Now';
            } else {
                modalTitle.textContent = 'Set Request to Pending';
                modalText.textContent = 'You are about to move this withdrawal request back to pending status.';
                confirmModalBtn.textContent = 'Set Pending';
            }

            statusModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeStatusModal() {
        statusModal.classList.remove('show');
        document.body.style.overflow = '';
        activeForm = null;
    }

    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', closeStatusModal);
    }

    if (statusModal) {
        statusModal.addEventListener('click', function (e) {
            if (e.target === statusModal) {
                closeStatusModal();
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