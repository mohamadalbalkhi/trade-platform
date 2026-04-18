@extends('layouts.app')

@section('content')

@php
    $tickets = $tickets ?? collect();
@endphp

<style>
.support-page{
    display:flex;
    flex-direction:column;
    gap:18px;
    padding-bottom:140px;
}

.support-hero{
    position:relative;
    overflow:hidden;
    border-radius:28px;
    padding:20px;
    background:
        linear-gradient(135deg, rgba(6,10,8,0.58) 0%, rgba(6,10,8,0.84) 100%),
        radial-gradient(circle at 80% 18%, rgba(184,255,59,0.18) 0%, rgba(184,255,59,0) 32%),
        linear-gradient(135deg,#0b1410 0%, #13211a 50%, #08100b 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.support-hero-inner{
    display:grid;
    grid-template-columns:1.1fr .9fr;
    gap:18px;
    align-items:center;
}

.support-hero-left{
    min-width:0;
}

.support-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:fit-content;
    padding:8px 13px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    color:#07120b;
    background:linear-gradient(180deg,#efffc3 0%, #b8ff3b 100%);
    margin-bottom:14px;
}

.support-title{
    font-size:30px;
    line-height:1.25;
    font-weight:900;
    color:#f5fff7;
    margin-bottom:10px;
}

.support-sub{
    color:#a8b8ae;
    font-size:14px;
    line-height:1.9;
    margin-bottom:16px;
}

.support-stats{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.support-stat{
    min-width:110px;
    padding:12px 14px;
    border-radius:18px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
}

.support-stat-number{
    color:#f4fff8;
    font-size:18px;
    font-weight:900;
    margin-bottom:4px;
}

.support-stat-label{
    color:#95a89b;
    font-size:12px;
    font-weight:800;
}

.support-agent-card{
    border-radius:24px;
    padding:16px;
    background:linear-gradient(180deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.02) 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 14px 28px rgba(0,0,0,0.18);
}

.agent-top{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:14px;
}

.agent-avatar{
    width:64px;
    height:64px;
    border-radius:50%;
    flex:0 0 64px;
    background:radial-gradient(circle,#f0ffc3 0%, #b8ff3b 65%, #8dff1c 100%);
    color:#07120b;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    font-weight:900;
    position:relative;
    box-shadow:0 10px 22px rgba(184,255,59,0.20);
}

.agent-avatar::after{
    content:"";
    position:absolute;
    right:3px;
    bottom:3px;
    width:12px;
    height:12px;
    border-radius:50%;
    background:#56ff8c;
    box-shadow:0 0 0 3px #122018;
}

.agent-info{
    min-width:0;
}

.agent-name{
    color:#f5fff7;
    font-size:18px;
    font-weight:900;
}

.agent-role{
    color:#9fb2a3;
    font-size:12px;
    font-weight:700;
    margin-top:2px;
}

.agent-status{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-top:8px;
    padding:8px 12px;
    border-radius:999px;
    background:rgba(184,255,59,0.10);
    border:1px solid rgba(184,255,59,0.12);
    color:#efffc7;
    font-size:11px;
    font-weight:900;
}

.agent-status-dot{
    width:8px;
    height:8px;
    border-radius:50%;
    background:#9cff38;
    box-shadow:0 0 10px rgba(156,255,56,.9);
}

.agent-chat-demo{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.agent-bubble,
.user-bubble-demo{
    padding:12px 14px;
    border-radius:18px;
    font-size:12px;
    line-height:1.7;
}

.agent-bubble{
    width:85%;
    background:linear-gradient(180deg,#18231c 0%, #111913 100%);
    color:#eef7f0;
    border:1px solid rgba(184,255,59,0.08);
}

.user-bubble-demo{
    width:68%;
    align-self:flex-end;
    background:radial-gradient(circle,#efffc3 0%, #b8ff3b 100%);
    color:#07120b;
    font-weight:800;
}

.support-panel,
.tickets-panel{
    border-radius:28px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0b130f 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.panel-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:18px;
}

.panel-title{
    font-size:22px;
    font-weight:900;
    color:#f4fff8;
}

.panel-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.panel-badge.live{
    color:#07120b;
    background:linear-gradient(180deg,#efffc3 0%, #b8ff3b 100%);
}

.panel-badge.history{
    color:#dce8df;
    background:#18231c;
    border:1px solid rgba(184,255,59,0.08);
}

.alert{
    border-radius:18px;
    padding:14px 16px;
    font-size:14px;
    font-weight:800;
    line-height:1.7;
}

.alert.success{
    background:rgba(46,204,113,0.12);
    color:#9df0b9;
    border:1px solid rgba(46,204,113,0.16);
}

.alert.error{
    background:rgba(255,95,116,0.10);
    color:#ffb6c1;
    border:1px solid rgba(255,95,116,0.14);
}

.support-form{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.form-label{
    color:#dceadf;
    font-size:13px;
    font-weight:900;
}

.form-input,
.form-textarea{
    width:100%;
    border:none;
    outline:none;
    border-radius:18px;
    padding:15px 16px;
    background:#0d1510;
    color:#f4fff8;
    border:1px solid rgba(184,255,59,0.08);
    font-size:15px;
}

.form-input::placeholder,
.form-textarea::placeholder{
    color:#82958a;
}

.form-textarea{
    min-height:160px;
    resize:vertical;
    line-height:1.8;
}

.form-note{
    border-radius:20px;
    padding:15px 16px;
    background:rgba(184,255,59,0.06);
    border:1px solid rgba(184,255,59,0.10);
    color:#dceada;
    font-size:13px;
    line-height:1.85;
}

.form-actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.support-btn{
    border:none;
    border-radius:16px;
    padding:14px 18px;
    font-size:14px;
    font-weight:900;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:.2s ease;
}

.support-btn.primary{
    color:#07120b;
    background:radial-gradient(circle,#efffc3 0%, #b8ff3b 60%, #8dff1c 100%);
    box-shadow:0 14px 26px rgba(184,255,59,0.20);
}

.support-btn.secondary{
    background:#18231c;
    color:#f4fff8;
    border:1px solid rgba(184,255,59,0.08);
}

.support-btn:hover{
    transform:translateY(-1px);
}

.tickets-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.ticket-card{
    border-radius:24px;
    padding:18px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
}

.ticket-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:14px;
}

.ticket-subject{
    font-size:18px;
    font-weight:900;
    color:#f5fff6;
    line-height:1.5;
}

.ticket-meta{
    color:#9db09f;
    font-size:12px;
    line-height:1.75;
    margin-top:4px;
}

.ticket-status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.ticket-status.pending{
    color:#fff4c2;
    background:#6b5b20;
}

.ticket-status.replied{
    color:#07120b;
    background:#7fffd4;
}

.ticket-status.closed{
    color:#d7e8dc;
    background:#253328;
}

.ticket-body{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.ticket-message,
.ticket-reply{
    border-radius:20px;
    padding:16px;
    line-height:1.9;
    font-size:14px;
    word-break:break-word;
}

.ticket-message{
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.06);
    color:#edf8ef;
}

.ticket-reply{
    background:linear-gradient(180deg, rgba(184,255,59,0.08) 0%, rgba(184,255,59,0.04) 100%);
    border:1px solid rgba(184,255,59,0.10);
    color:#f5fff7;
}

.ticket-reply::before{
    content:"Support Reply";
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-bottom:10px;
    padding:6px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    color:#07120b;
    background:linear-gradient(180deg,#efffc3 0%, #b8ff3b 100%);
}

.ticket-waiting{
    border-radius:18px;
    padding:14px 16px;
    background:rgba(255,193,7,0.10);
    border:1px solid rgba(255,193,7,0.16);
    color:#ffe7a3;
    font-size:13px;
    line-height:1.8;
}

.empty-state{
    border-radius:24px;
    padding:24px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
    color:#dbe8df;
    line-height:2;
    text-align:center;
}

.empty-emoji{
    font-size:38px;
    margin-bottom:10px;
    display:block;
}

.floating-support{
    position:fixed;
    right:16px;
    bottom:108px;
    z-index:999;
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:999px;
    background:rgba(11,18,14,0.92);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 14px 30px rgba(0,0,0,0.26);
}

.floating-support-avatar{
    width:42px;
    height:42px;
    border-radius:50%;
    background:radial-gradient(circle,#efffc3 0%, #b8ff3b 65%, #8dff1c 100%);
    color:#061009;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    font-weight:900;
    position:relative;
}

.floating-support-avatar::after{
    content:"";
    position:absolute;
    right:2px;
    bottom:2px;
    width:10px;
    height:10px;
    border-radius:50%;
    background:#56ff8c;
    box-shadow:0 0 0 2px #122018;
}

.floating-support-text{
    display:flex;
    flex-direction:column;
    gap:2px;
}

.floating-support-name{
    color:#f4fff8;
    font-size:12px;
    font-weight:900;
}

.floating-support-sub{
    color:#b3c3b6;
    font-size:11px;
    font-weight:700;
}

@media (max-width:900px){
    .support-hero-inner{
        grid-template-columns:1fr;
    }

    .support-title{
        font-size:28px;
    }
}

@media (max-width:700px){
    .support-page{
        padding-bottom:170px;
    }

    .support-hero{
        padding:18px;
    }

    .support-title{
        font-size:24px;
    }

    .support-sub{
        font-size:14px;
        line-height:1.8;
    }

    .support-stat{
        flex:1 1 100%;
        min-width:0;
    }

    .agent-profile{
        flex-direction:column;
        align-items:flex-start;
    }

    .agent-bubble,
    .user-bubble-demo{
        width:100%;
    }

    .form-actions{
        flex-direction:column;
    }

    .support-btn{
        width:100%;
    }

    .ticket-top{
        flex-direction:column;
    }

    .panel-title{
        font-size:20px;
    }

    .ticket-subject{
        font-size:17px;
    }

    .floating-support{
        right:12px;
        left:12px;
        justify-content:center;
        bottom:104px;
    }
}
</style>

<div class="support-page">

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="support-hero">
        <div class="support-hero-inner">
            <div class="support-hero-left">
                <div class="support-chip">PREMIUM SUPPORT</div>
                <div class="support-title">Need help? نحن هنا لدعمك بسرعة واحتراف</div>
                <div class="support-sub">
                    اشرح مشكلتك بوضوح، وسيصل طلبك مباشرة إلى فريق الدعم داخل المنصة. سيتم مراجعة تذكرتك والرد عليك هنا بأسرع وقت ممكن بطريقة منظمة، واضحة، وآمنة.
                </div>

                <div class="support-stats">
                    <div class="support-stat">
                        <div class="support-stat-number">24/7</div>
                        <div class="support-stat-label">Ticket Receiving</div>
                    </div>

                    <div class="support-stat">
                        <div class="support-stat-number">Fast</div>
                        <div class="support-stat-label">Support Review</div>
                    </div>

                    <div class="support-stat">
                        <div class="support-stat-number">Secure</div>
                        <div class="support-stat-label">Private Follow-up</div>
                    </div>
                </div>
            </div>

            <div class="support-agent-card">
                <div class="agent-top">
                    <div class="agent-avatar">👩🏻</div>

                    <div class="agent-info">
                        <div class="agent-name">Lina</div>
                        <div class="agent-role">Support Specialist</div>
                        <div class="agent-status">
                            <span class="agent-status-dot"></span>
                            Support team is active
                        </div>
                    </div>
                </div>

                <div class="agent-chat-demo">
                    <div class="agent-bubble">
                        Hello 👋 We received your request and our support team is reviewing the details now.
                    </div>

                    <div class="user-bubble-demo">
                        My deposit is still pending
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="support-panel">
        <div class="panel-head">
            <div class="panel-title">Create New Support Ticket</div>
            <div class="panel-badge live">Support Team Review</div>
        </div>

        <form method="POST" action="/support/send" class="support-form">
            @csrf

            <div class="form-group">
                <label class="form-label">Subject</label>
                <input
                    type="text"
                    name="subject"
                    class="form-input"
                    placeholder="Example: My withdrawal is still pending"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Describe Your Problem</label>
                <textarea
                    name="message"
                    class="form-textarea"
                    placeholder="Write your issue clearly. Include any useful details like amount, deposit issue, withdrawal issue, account problem, or anything important..."
                    required
                ></textarea>
            </div>

            <div class="form-note">
                <strong>Important:</strong><br>
                يرجى كتابة المشكلة بشكل واضح ومختصر. بعد إرسال التذكرة، ستظهر هنا مباشرة داخل سجل الدعم، وسيقوم فريق الدعم بالرد عليك داخل نفس الصفحة.
            </div>

            <div class="form-actions">
                <button type="submit" class="support-btn primary">Submit Support Ticket</button>
                <a href="/home" class="support-btn secondary">Back to Home</a>
            </div>
        </form>
    </div>

    <div class="tickets-panel">
        <div class="panel-head">
            <div class="panel-title">Your Support Tickets</div>
            <div class="panel-badge history">Ticket History</div>
        </div>

        @if(isset($tickets) && count($tickets) > 0)
            <div class="tickets-list">
                @foreach($tickets as $ticket)
                    <div class="ticket-card">
                        <div class="ticket-top">
                            <div>
                                <div class="ticket-subject">{{ $ticket->subject }}</div>
                                <div class="ticket-meta">
                                    Ticket ID: #{{ $ticket->id }}<br>
                                    Created: {{ $ticket->created_at }}
                                </div>
                            </div>

                            @php
                                $status = strtolower($ticket->status ?? 'pending');
                            @endphp

                            @if($status === 'replied')
                                <div class="ticket-status replied">Replied</div>
                            @elseif($status === 'closed')
                                <div class="ticket-status closed">Closed</div>
                            @else
                                <div class="ticket-status pending">Pending Reply</div>
                            @endif
                        </div>

                        <div class="ticket-body">
                            <div class="ticket-message">
                                {{ $ticket->message }}
                            </div>

                            @if(!empty($ticket->admin_reply))
                                <div class="ticket-reply">
                                    {{ $ticket->admin_reply }}
                                </div>
                            @else
                                <div class="ticket-waiting">
                                    Your ticket has been received successfully. Our support team will reply as soon as possible.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <span class="empty-emoji">💬</span>
                You do not have any support tickets yet.<br>
                When you submit your first ticket, it will appear here immediately with its status and support reply.
            </div>
        @endif
    </div>

</div>

<div class="floating-support">
    <div class="floating-support-avatar">🧑🏻</div>
    <div class="floating-support-text">
        <div class="floating-support-name">Support is active</div>
        <div class="floating-support-sub">Our support team will reply as soon as possible</div>
    </div>
</div>

@endsection