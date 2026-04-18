<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawnEX Admin | Support</title>
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

        .tickets-grid{
            display:flex;
            flex-direction:column;
            gap:18px;
        }

        .ticket-card{
            background:linear-gradient(180deg, rgba(18,29,49,0.98) 0%, rgba(12,20,35,0.99) 100%);
            border:1px solid var(--line);
            border-radius:28px;
            padding:20px;
            box-shadow:0 18px 40px rgba(0,0,0,0.24);
        }

        .ticket-top{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:14px;
            flex-wrap:wrap;
            margin-bottom:16px;
        }

        .ticket-user{
            font-size:20px;
            font-weight:900;
            color:#fff;
            margin-bottom:6px;
        }

        .ticket-meta{
            color:#93a4ba;
            font-size:13px;
            line-height:1.7;
        }

        .ticket-subject{
            font-size:18px;
            font-weight:900;
            color:#e2e8f0;
            margin-top:10px;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:7px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            white-space:nowrap;
        }

        .badge.pending{
            background:var(--amber-soft);
            color:#fde68a;
            border:1px solid rgba(245,158,11,0.20);
        }

        .badge.replied{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.20);
        }

        .badge.closed{
            background:var(--red-soft);
            color:#fecaca;
            border:1px solid rgba(239,68,68,0.20);
        }

        .message-box,
        .reply-box{
            border-radius:20px;
            padding:16px;
            margin-top:14px;
            line-height:1.8;
            font-size:14px;
        }

        .message-box{
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            color:#e5edf7;
        }

        .reply-box{
            background:rgba(34,197,94,0.10);
            border:1px solid rgba(34,197,94,0.16);
            color:#d1fae5;
        }

        .reply-title{
            font-size:12px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.7px;
            margin-bottom:8px;
            color:#86efac;
        }

        .form-grid{
            display:grid;
            grid-template-columns:1fr 180px;
            gap:12px;
            margin-top:16px;
        }

        .reply-textarea{
            width:100%;
            min-height:120px;
            border:none;
            outline:none;
            resize:vertical;
            border-radius:18px;
            padding:16px;
            background:#0d1525;
            color:#f8fafc;
            border:1px solid rgba(255,255,255,0.08);
            font-size:14px;
            line-height:1.7;
        }

        .reply-textarea::placeholder{
            color:#94a3b8;
        }

        .side-actions{
            display:flex;
            flex-direction:column;
            gap:10px;
        }

        .select-status{
            width:100%;
            border:none;
            outline:none;
            border-radius:16px;
            padding:14px 12px;
            background:#0d1525;
            color:#f8fafc;
            border:1px solid rgba(255,255,255,0.08);
            font-size:14px;
            font-weight:800;
        }

        .btn{
            border:none;
            cursor:pointer;
            padding:14px 16px;
            border-radius:16px;
            font-size:13px;
            font-weight:900;
            transition:.2s ease;
            width:100%;
        }

        .btn.reply{
            background:var(--green-soft);
            color:#bbf7d0;
            border:1px solid rgba(34,197,94,0.20);
        }

        .btn:hover{
            transform:translateY(-1px);
            filter:brightness(1.05);
        }

        .empty{
            padding:30px;
            text-align:center;
            color:#93a4ba;
            background:linear-gradient(180deg, rgba(18,29,49,0.98) 0%, rgba(12,20,35,0.99) 100%);
            border:1px solid var(--line);
            border-radius:28px;
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

            .form-grid{
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
            <a href="/admin">Dashboard</a>
            <a href="/admin/users">Users</a>
            <a href="/admin/deposits">Deposits</a>
            <a href="/admin/withdrawals">Withdrawals</a>
            <a href="/admin/support" class="active">Support</a>
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
                    <h2>Support Tickets</h2>
                    <p>
                        Review user support tickets, reply directly from the admin panel, and manage the full support workflow in one place.
                    </p>
                </div>

                <div class="hero-badge">Support Center</div>
            </div>
        </div>

        @if(isset($tickets) && count($tickets) > 0)
            <div class="tickets-grid">
                @foreach($tickets as $ticket)
                    <div class="ticket-card">
                        <div class="ticket-top">
                            <div>
                                <div class="ticket-user">{{ $ticket->user_name }}</div>
                                <div class="ticket-meta">
                                    Email: {{ $ticket->user_email }}<br>
                                    Ticket ID: #{{ $ticket->id }}<br>
                                    Created: {{ $ticket->created_at }}
                                </div>
                                <div class="ticket-subject">{{ $ticket->subject }}</div>
                            </div>

                            @php
                                $status = strtolower($ticket->status ?? 'pending');
                            @endphp

                            @if($status === 'replied')
                                <div class="badge replied">Replied</div>
                            @elseif($status === 'closed')
                                <div class="badge closed">Closed</div>
                            @else
                                <div class="badge pending">Pending</div>
                            @endif
                        </div>

                        <div class="message-box">
                            {{ $ticket->message }}
                        </div>

                        @if(!empty($ticket->admin_reply))
                            <div class="reply-box">
                                <div class="reply-title">Current Reply</div>
                                {{ $ticket->admin_reply }}
                            </div>
                        @endif

                        <form method="POST" action="/admin/support/{{ $ticket->id }}/reply">
                            @csrf

                            <div class="form-grid">
                                <textarea
                                    name="admin_reply"
                                    class="reply-textarea"
                                    placeholder="Write your support reply here..."
                                    required
                                >{{ $ticket->admin_reply }}</textarea>

                                <div class="side-actions">
                                    <select name="status" class="select-status">
                                        <option value="Pending" {{ ($ticket->status === 'Pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="Replied" {{ ($ticket->status === 'Replied') ? 'selected' : '' }}>Replied</option>
                                        <option value="Closed" {{ ($ticket->status === 'Closed') ? 'selected' : '' }}>Closed</option>
                                    </select>

                                    <button type="submit" class="btn reply">Send Reply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">
                No support tickets found.
            </div>
        @endif

    </main>

</div>

</body>
</html>
