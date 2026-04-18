@extends('layouts.app')

@section('content')

@php
    $aiStrategy = \App\Models\AiStrategy::where('user_name', auth()->user()->name)
        ->whereIn('status', ['executing', 'redeem_pending', 'pending'])
        ->latest()
        ->first();

    $aiHoursLeft = null;
    $aiRedeemHoursLeft = null;

    $unlockTimestamp = null;
    $redeemTimestamp = null;

    if ($aiStrategy && $aiStrategy->unlock_at) {
        $unlockTimestamp = \Carbon\Carbon::parse($aiStrategy->unlock_at)->timestamp;
    }

    if ($aiStrategy && $aiStrategy->redeem_available_at) {
        $redeemTimestamp = \Carbon\Carbon::parse($aiStrategy->redeem_available_at)->timestamp;
    }

    if ($aiStrategy && $aiStrategy->status === 'executing' && $aiStrategy->unlock_at && now()->lt($aiStrategy->unlock_at)) {
        $aiHoursLeft = (int) ceil(now()->diffInMinutes($aiStrategy->unlock_at) / 60);
    }

    if ($aiStrategy && $aiStrategy->status === 'redeem_pending' && $aiStrategy->redeem_available_at && now()->lt($aiStrategy->redeem_available_at)) {
        $aiRedeemHoursLeft = (int) ceil(now()->diffInMinutes($aiStrategy->redeem_available_at) / 60);
    }
@endphp

<style>
    body{
        background:
            radial-gradient(circle at 50% 0%, rgba(184,255,59,0.07) 0%, rgba(184,255,59,0) 30%),
            linear-gradient(180deg, #0a0f0c 0%, #0d1410 100%);
    }

    .dx-home-shell{
        width:100%;
        max-width:430px;
        margin:0 auto;
        padding:6px 14px 120px;
        display:flex;
        flex-direction:column;
        gap:16px;
    }

    .dx-card{
        position:relative;
        overflow:hidden;
        border-radius:28px;
        background:linear-gradient(180deg, rgba(19,27,22,0.98) 0%, rgba(12,17,14,0.98) 100%);
        border:1px solid rgba(184,255,59,0.09);
        box-shadow:0 16px 34px rgba(0,0,0,0.24);
    }

    .dx-hero{
        padding:22px 20px 18px;
        min-height:190px;
        background:
            linear-gradient(135deg, rgba(5,10,7,0.42) 0%, rgba(5,10,7,0.72) 100%),
            radial-gradient(circle at 78% 18%, rgba(184,255,59,0.15) 0%, rgba(184,255,59,0) 32%),
            linear-gradient(135deg,#111a14 0%, #18231c 55%, #0d120f 100%);
    }

    .dx-hero::before{
        content:"";
        position:absolute;
        width:170px;
        height:170px;
        top:-60px;
        right:-30px;
        border-radius:50%;
        background:radial-gradient(circle, rgba(184,255,59,0.10) 0%, rgba(184,255,59,0) 70%);
    }

    .dx-hero-top{
        position:relative;
        z-index:2;
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:12px;
        margin-bottom:18px;
    }

    .dx-hero-icon{
        width:56px;
        height:56px;
        border-radius:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:radial-gradient(circle,#d8ff71 0%,#a8ef2c 100%);
        color:#0c140e;
        font-weight:900;
        font-size:24px;
        box-shadow:0 10px 28px rgba(184,255,59,0.22);
    }

    .dx-chip{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 14px;
        border-radius:999px;
        background:rgba(184,255,59,0.08);
        border:1px solid rgba(184,255,59,0.14);
        color:#e5ffba;
        font-size:12px;
        font-weight:800;
    }

    .dx-hero-title{
        position:relative;
        z-index:2;
        font-size:22px;
        line-height:1.45;
        font-weight:900;
        color:#f3fff7;
        margin-bottom:10px;
        max-width:285px;
    }

    .dx-hero-text{
        position:relative;
        z-index:2;
        font-size:13px;
        line-height:1.9;
        color:#b5c5b8;
        max-width:300px;
    }

    .dx-stats{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }

    .dx-stat{
        padding:16px 18px;
        border-radius:22px;
        background:linear-gradient(180deg,#141d18 0%, #0e1511 100%);
        border:1px solid rgba(184,255,59,0.08);
        box-shadow:0 14px 28px rgba(0,0,0,0.18);
    }

    .dx-stat-label{
        font-size:11px;
        font-weight:800;
        letter-spacing:.8px;
        text-transform:uppercase;
        color:#8d9f8f;
        margin-bottom:8px;
    }

    .dx-stat-value{
        font-size:22px;
        font-weight:900;
        line-height:1.15;
        color:#f6fff8;
    }

    .dx-panel{
        padding:18px;
    }

    .dx-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-bottom:14px;
    }

    .dx-title{
        font-size:18px;
        font-weight:900;
        color:#f6fff8;
    }

    .dx-link{
        font-size:13px;
        font-weight:800;
        color:#cfff58;
        text-decoration:none;
    }

    .dx-quick-grid{
        display:grid;
        grid-template-columns:repeat(4, 1fr);
        gap:10px;
    }

    .dx-quick{
        min-height:96px;
        border-radius:22px;
        text-decoration:none;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        gap:10px;
        background:linear-gradient(180deg,#161f1a 0%, #101713 100%);
        border:1px solid rgba(184,255,59,0.07);
        transition:.18s ease;
    }

    .dx-quick:hover{
        transform:translateY(-2px);
        border-color:rgba(184,255,59,0.16);
    }

    .dx-quick-circle{
        width:42px;
        height:42px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        background:rgba(184,255,59,0.12);
        color:#dfff8f;
        font-size:18px;
        font-weight:900;
        box-shadow:0 8px 18px rgba(184,255,59,0.08);
    }

    .dx-quick-name{
        font-size:12px;
        font-weight:800;
        color:#f0f8f1;
        text-align:center;
        line-height:1.3;
    }

    .dx-ai{
        padding:18px;
    }

    .dx-ai-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-bottom:14px;
        flex-wrap:wrap;
    }

    .dx-ai-title{
        font-size:18px;
        font-weight:900;
        color:#cfff58;
    }

    .dx-ai-order{
        font-size:12px;
        color:#90a391;
        font-weight:800;
    }

    .dx-ai-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
        margin-bottom:14px;
    }

    .dx-ai-box{
        padding:13px 14px;
        border-radius:18px;
        background:rgba(255,255,255,0.03);
        border:1px solid rgba(255,255,255,0.05);
    }

    .dx-ai-label{
        font-size:12px;
        color:#90a391;
        margin-bottom:6px;
    }

    .dx-ai-value{
        font-size:17px;
        font-weight:900;
        color:#f5fff8;
    }

    .dx-ai-profit{
        color:#67f89e;
    }

    .dx-ai-note{
        padding:12px 14px;
        border-radius:16px;
        background:rgba(184,255,59,0.08);
        border:1px solid rgba(184,255,59,0.10);
        color:#e7ffc0;
        font-size:13px;
        line-height:1.75;
        margin-bottom:14px;
    }

    .dx-ai-note.pending{
        background:rgba(240,185,11,0.10);
        border:1px solid rgba(240,185,11,0.16);
        color:#ffe4a0;
    }

    .dx-ai-actions{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }

    .dx-btn-accent,
    .dx-btn-dark,
    .dx-btn-danger{
        width:100%;
        border:none;
        border-radius:18px;
        padding:14px 14px;
        font-size:14px;
        font-weight:900;
        cursor:pointer;
        text-align:center;
    }

    .dx-btn-accent{
        background:linear-gradient(180deg,#dcff66 0%, #b7ef2f 100%);
        color:#0b120d;
        box-shadow:0 10px 24px rgba(184,255,59,0.18);
    }

    .dx-btn-dark{
        background:#161f1a;
        border:1px solid rgba(184,255,59,0.08);
        color:#f5fff8;
    }

    .dx-btn-danger{
        background:linear-gradient(180deg,#ff7f90 0%, #eb546d 100%);
        color:#fff;
    }

    .dx-market-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin:4px 2px 2px;
    }

    .dx-market-list{
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .dx-market{
        text-decoration:none;
        display:grid;
        grid-template-columns:auto 1fr auto;
        align-items:center;
        gap:12px;
        padding:15px 16px;
        border-radius:24px;
        background:linear-gradient(180deg,#141d18 0%, #0e1511 100%);
        border:1px solid rgba(184,255,59,0.08);
        box-shadow:0 12px 26px rgba(0,0,0,0.18);
        transition:.18s ease;
    }

    .dx-market:hover{
        transform:translateY(-2px);
        border-color:rgba(184,255,59,0.15);
    }

    .dx-market-icon{
        width:48px;
        height:48px;
        border-radius:16px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#0b130d;
        font-size:18px;
        font-weight:900;
        box-shadow:0 10px 20px rgba(0,0,0,0.16);
    }

    .dx-market-name{
        font-size:19px;
        font-weight:900;
        color:#f5fff8;
        line-height:1.15;
        margin-bottom:4px;
    }

    .dx-market-meta{
        font-size:12px;
        font-weight:800;
        color:#8ea18f;
    }

    .dx-market-side{
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap:7px;
    }

    .dx-market-price{
        font-size:18px;
        font-weight:900;
        color:#f5fff8;
    }

    .dx-market-change{
        min-width:82px;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        text-align:center;
        color:#fff;
    }

    .dx-home-note{
        text-align:center;
        color:#819383;
        font-size:12px;
        line-height:1.8;
        padding:2px 10px 0;
    }

    .dx-bottom-nav{
        position:fixed;
        left:50%;
        transform:translateX(-50%);
        bottom:14px;
        width:calc(100% - 26px);
        max-width:430px;
        padding:14px 18px 12px;
        display:grid;
        grid-template-columns:repeat(5,1fr);
        align-items:end;
        gap:8px;
        border-radius:28px;
        background:linear-gradient(180deg, rgba(18,24,20,0.96) 0%, rgba(12,16,14,0.96) 100%);
        border:1px solid rgba(184,255,59,0.10);
        box-shadow:0 18px 40px rgba(0,0,0,0.35);
        z-index:999;
        backdrop-filter:blur(10px);
    }

    .dx-bottom-item{
        text-decoration:none;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:flex-end;
        gap:6px;
        color:#eef6ef;
        font-size:11px;
        font-weight:800;
        line-height:1.2;
        min-height:48px;
        opacity:.92;
    }

    .dx-bottom-item svg{
        width:18px;
        height:18px;
        opacity:.95;
    }

    .dx-bottom-item.active{
        color:#dbff78;
    }

    .dx-bottom-ai-wrap{
        display:flex;
        justify-content:center;
        align-items:flex-end;
    }

    .dx-bottom-ai{
        width:70px;
        height:70px;
        margin-top:-34px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        background:radial-gradient(circle,#ddff66 0%, #b6ef2f 100%);
        color:#182012;
        font-size:26px;
        font-weight:900;
        box-shadow:0 0 0 8px rgba(18,24,20,0.96), 0 10px 30px rgba(184,255,59,0.30);
    }

    .dx-bottom-ai.active{
        filter:brightness(1.02);
    }

    .dx-cancel-modal{
        position:fixed;
        inset:0;
        display:none;
        align-items:center;
        justify-content:center;
        background:rgba(0,0,0,0.68);
        z-index:10000;
        padding:20px;
    }

    .dx-cancel-box{
        width:100%;
        max-width:380px;
        border-radius:24px;
        padding:24px;
        text-align:center;
        background:linear-gradient(180deg,#131b16 0%, #0d1310 100%);
        border:1px solid rgba(184,255,59,0.14);
        box-shadow:0 24px 60px rgba(0,0,0,0.40);
    }

    .dx-cancel-box h3{
        color:#f6fff8;
        font-size:24px;
        font-weight:900;
        margin-bottom:10px;
    }

    .dx-cancel-box p{
        color:#b7c7b8;
        font-size:14px;
        line-height:1.85;
    }

    .dx-cancel-buttons{
        margin-top:18px;
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }

    .dx-back-btn{
        border:none;
        border-radius:16px;
        padding:13px;
        background:#19231c;
        color:#fff;
        font-weight:900;
        cursor:pointer;
    }

    .dx-confirm-btn{
        border:none;
        border-radius:16px;
        padding:13px;
        background:linear-gradient(180deg,#ff7f90 0%, #eb546d 100%);
        color:#fff;
        font-weight:900;
        cursor:pointer;
    }

    @media (max-width: 420px){
        .dx-home-shell{
            padding-left:12px;
            padding-right:12px;
        }

        .dx-hero{
            padding:20px 18px 18px;
        }

        .dx-hero-title{
            font-size:20px;
        }

        .dx-stats{
            gap:10px;
        }

        .dx-stat-value{
            font-size:20px;
        }

        .dx-quick-grid{
            gap:8px;
        }

        .dx-quick{
            min-height:90px;
            border-radius:18px;
        }

        .dx-ai-grid{
            grid-template-columns:1fr;
        }

        .dx-ai-actions{
            grid-template-columns:1fr;
        }

        .dx-market{
            grid-template-columns:1fr;
            text-align:center;
        }

        .dx-market-side,
        .dx-market > div:nth-child(2){
            align-items:center;
        }

        .dx-bottom-nav{
            padding-left:14px;
            padding-right:14px;
        }

        .dx-cancel-buttons{
            grid-template-columns:1fr;
        }
    }
</style>

<div class="dx-home-shell">

    <div class="dx-card dx-hero">
        <div class="dx-hero-top">
            <div class="dx-hero-icon">D</div>
            <div class="dx-chip">Smart Dashboard</div>
        </div>

        <div class="dx-hero-title">واجهة أنظف وأهدأ لإدارة الأصول والتداول</div>
        <div class="dx-hero-text">
            هذه الصفحة الرئيسية تعرض فقط ما يحتاجه المستخدم فعلًا: الرصيد، الوصول السريع، وأهم أزواج السوق، مع حالة صفقة الذكاء الاصطناعي إذا كانت نشطة.
        </div>
    </div>

    <div class="dx-stats">
        <div class="dx-stat">
            <div class="dx-stat-label">Total Est. Value</div>
            <div class="dx-stat-value">${{ number_format($totalAssets ?? 0, 2) }}</div>
        </div>

        <div class="dx-stat">
            <div class="dx-stat-label">Available Balance</div>
            <div class="dx-stat-value">${{ number_format($wallet->balance ?? 0, 2) }}</div>
        </div>
    </div>

    <div class="dx-card dx-panel">
        <div class="dx-head">
            <div class="dx-title">Quick Access</div>
            <div class="dx-link">Dashboard</div>
        </div>

        <div class="dx-quick-grid">
            <a href="/deposit" class="dx-quick">
                <div class="dx-quick-circle">+</div>
                <div class="dx-quick-name">Deposit</div>
            </a>

            <a href="/withdraw" class="dx-quick">
                <div class="dx-quick-circle">↗</div>
                <div class="dx-quick-name">Withdraw</div>
            </a>

            <a href="/ai" class="dx-quick">
                <div class="dx-quick-circle">AI</div>
                <div class="dx-quick-name">AI Vault</div>
            </a>

            <a href="/support" class="dx-quick">
                <div class="dx-quick-circle">?</div>
                <div class="dx-quick-name">Support</div>
            </a>
        </div>
    </div>

    @if($aiStrategy)
        <div class="dx-card dx-ai">
            <div class="dx-ai-top">
                <div class="dx-ai-title">AI Strategy Active</div>
                <div class="dx-ai-order">Order #{{ $aiStrategy->order_no }}</div>
            </div>

            <div class="dx-ai-grid">
                <div class="dx-ai-box">
                    <div class="dx-ai-label">Invested Amount</div>
                    <div class="dx-ai-value">${{ number_format($aiStrategy->amount, 2) }}</div>
                </div>

                <div class="dx-ai-box">
                    <div class="dx-ai-label">Current Profit</div>
                    <div
                        class="dx-ai-value dx-ai-profit"
                        id="aiProfit"
                        data-amount="{{ number_format((float)$aiStrategy->amount, 2, '.', '') }}"
                        data-percent="{{ number_format((float)$aiStrategy->target_percent, 4, '.', '') }}"
                        data-started="{{ $aiStrategy->started_at ? \Carbon\Carbon::parse($aiStrategy->started_at)->timestamp : now()->timestamp }}"
                        data-lockhours="{{ (int)$aiStrategy->lock_hours }}"
                        data-status="{{ $aiStrategy->status }}"
                    >
                        ${{ number_format($aiStrategy->current_profit, 2) }}
                    </div>
                </div>

                <div class="dx-ai-box">
                    <div class="dx-ai-label">Pair</div>
                    <div class="dx-ai-value">{{ $aiStrategy->target_pair }}</div>
                </div>

                <div class="dx-ai-box">
                    <div class="dx-ai-label">Status</div>
                    <div class="dx-ai-value">
                        @if($aiStrategy->status === 'executing')
                            Executing
                        @elseif($aiStrategy->status === 'redeem_pending')
                            Redeem Pending
                        @elseif($aiStrategy->status === 'pending')
                            Pending
                        @endif
                    </div>
                </div>
            </div>

            @if($aiStrategy->status === 'executing' || $aiStrategy->status === 'pending')
                <div class="dx-ai-note">
                    @if($aiHoursLeft)
                        لا تزال الصفقة قيد التنفيذ. الوقت المتبقي حتى انتهاء الصفقة:
                        <strong id="aiTimer"></strong>
                    @else
                        انتهت مدة الصفقة ويمكنك الآن طلب Redeem.
                    @endif
                </div>

                <div class="dx-ai-actions">
                    @if(!$aiHoursLeft || now()->gte($aiStrategy->unlock_at))
                        <form method="POST" action="/ai/redeem/{{ $aiStrategy->id }}">
                            @csrf
                            <button type="submit" class="dx-btn-accent">Redeem</button>
                        </form>
                    @else
                        <button class="dx-btn-dark" disabled>Redeem Locked</button>
                    @endif

                    <button class="dx-btn-danger" type="button" onclick="openCancelModal({{ $aiStrategy->id }})">
                        Cancel
                    </button>
                </div>
            @elseif($aiStrategy->status === 'redeem_pending')
                <div class="dx-ai-note pending">
                    تم طلب Redeem بنجاح. سيتم إضافة الأموال إلى الرصيد بعد
                    <strong id="redeemTimer"></strong>
                </div>

                <div class="dx-ai-actions">
                    <button class="dx-btn-dark" disabled>Waiting 24H</button>
                    <button class="dx-btn-danger" type="button" onclick="openCancelModal({{ $aiStrategy->id }})">
                        Cancel
                    </button>
                </div>
            @endif
        </div>
    @endif

    <div class="dx-market-head">
        <div class="dx-title">Market Directives</div>
        <a href="/markets" class="dx-link">More ›</a>
    </div>

    <div class="dx-market-list">
        @foreach($marketCards as $index => $item)
            @php
                $iconColors = ['#d7ff56', '#9bf93d', '#71ffb2', '#7cb4ff', '#d2ff7a'];
                $iconText = ['₿', '◎', 'X', '◆', '◈'];
                $bg = $iconColors[$index % count($iconColors)];
                $txt = $iconText[$index % count($iconText)];
            @endphp

            <a href="/trade/{{ $item['symbol'] }}" class="dx-market">
                <div class="dx-market-icon" style="background:{{ $bg }}">
                    {{ $txt }}
                </div>

                <div>
                    <div class="dx-market-name">{{ $item['name'] }}</div>
                    <div class="dx-market-meta">Live market pair</div>
                </div>

                <div class="dx-market-side">
                    <div class="dx-market-price">
                        {{ $item['price'] >= 1 ? number_format($item['price'], 2) : number_format($item['price'], 5) }}
                    </div>

                    @if($item['change'] >= 0)
                        <div class="dx-market-change" style="background:#39d98a;">
                            +{{ number_format($item['change'], 2) }}%
                        </div>
                    @else
                        <div class="dx-market-change" style="background:#ff657d;">
                            {{ number_format($item['change'], 2) }}%
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="dx-home-note">
        هوية DawnEX الآن صارت أقرب لشكل التطبيق الحقيقي: داكنة، ضيقة، ومريحة بصريًا بدل صفحة dashboard عريضة ومشتتة.
    </div>

</div>

<div class="dx-bottom-nav">
    <a href="/home" class="dx-bottom-item active">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M4 11.5L12 5L20 11.5V19C20 19.55 19.55 20 19 20H15V14H9V20H5C4.45 20 4 19.55 4 19V11.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        </svg>
        <span>Home</span>
    </a>

    <a href="/markets" class="dx-bottom-item">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M5 16L9 12L13 15L19 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 8H19V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Markets</span>
    </a>

    <div class="dx-bottom-ai-wrap">
        <a href="/ai" class="dx-bottom-ai">AI</a>
    </div>

    <a href="/referrals" class="dx-bottom-item">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M16 21V19C16 17.34 14.66 16 13 16H7C5.34 16 4 17.34 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M10 13C12.21 13 14 11.21 14 9C14 6.79 12.21 5 10 5C7.79 5 6 6.79 6 9C6 11.21 7.79 13 10 13Z" stroke="currentColor" stroke-width="2"/>
            <path d="M20 8V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M23 11H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span>Referrals</span>
    </a>

    <a href="/wallet" class="dx-bottom-item">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M3 7.5C3 6.12 4.12 5 5.5 5H18.5C19.88 5 21 6.12 21 7.5V16.5C21 17.88 19.88 19 18.5 19H5.5C4.12 19 3 17.88 3 16.5V7.5Z" stroke="currentColor" stroke-width="2"/>
            <path d="M16 12H21" stroke="currentColor" stroke-width="2"/>
            <circle cx="16.5" cy="12" r="1" fill="currentColor"/>
        </svg>
        <span>Assets</span>
    </a>
</div>

<div id="cancelModal" class="dx-cancel-modal">
    <div class="dx-cancel-box">
        <h3>Cancel Strategy</h3>
        <p>
            إذا قمت بإلغاء الصفقة سيتم إعادة رأس المال فقط،
            وسيتم حذف جميع الأرباح نهائيًا.
        </p>

        <div class="dx-cancel-buttons">
            <button id="cancelBack" class="dx-back-btn" type="button">Back</button>

            <form id="cancelForm" method="POST">
                @csrf
                <button type="submit" class="dx-confirm-btn">Confirm Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
    const cancelModal = document.getElementById("cancelModal");
    const cancelForm = document.getElementById("cancelForm");
    const cancelBack = document.getElementById("cancelBack");

    function openCancelModal(strategyId){
        cancelForm.action = "/ai/cancel/" + strategyId;
        cancelModal.style.display = "flex";
    }

    if (cancelBack) {
        cancelBack.addEventListener("click", function () {
            cancelModal.style.display = "none";
        });
    }

    window.addEventListener("click", function (e) {
        if (e.target === cancelModal) {
            cancelModal.style.display = "none";
        }
    });

    let unlockTime = {{ $unlockTimestamp ?? 0 }};
    let redeemTime = {{ $redeemTimestamp ?? 0 }};

    function updateAiTimers(){
        let now = Math.floor(Date.now() / 1000);

        if (unlockTime > 0) {
            let diff = unlockTime - now;

            if (diff > 0) {
                let h = Math.floor(diff / 3600);
                let m = Math.floor((diff % 3600) / 60);
                let s = diff % 60;

                const aiTimer = document.getElementById('aiTimer');
                if (aiTimer) {
                    aiTimer.innerText = h + 'h ' + m + 'm ' + s + 's';
                }
            }
        }

        if (redeemTime > 0) {
            let diff = redeemTime - now;

            if (diff > 0) {
                let h = Math.floor(diff / 3600);
                let m = Math.floor((diff % 3600) / 60);
                let s = diff % 60;

                const redeemTimer = document.getElementById('redeemTimer');
                if (redeemTimer) {
                    redeemTimer.innerText = h + 'h ' + m + 'm ' + s + 's';
                }
            }
        }
    }

    function money(v){
        return '$' + Number(v).toFixed(2);
    }

    function updateAiProfitLive(){
        const el = document.getElementById('aiProfit');
        if (!el) return;

        const status = el.dataset.status;
        if (status !== 'executing') return;

        const amount = parseFloat(el.dataset.amount || 0);
        const percent = parseFloat(el.dataset.percent || 0);
        const started = parseInt(el.dataset.started || 0);
        const lockHours = parseInt(el.dataset.lockhours || 0);

        const now = Math.floor(Date.now() / 1000);

        let elapsed = now - started;
        if (elapsed < 0) elapsed = 0;

        const maxSeconds = lockHours * 3600;

        if (elapsed >= maxSeconds) {
            elapsed = maxSeconds;
        }

        const dailyProfit = (amount * percent) / 100;
        const profitPerSecond = dailyProfit / 86400;

        let profit = profitPerSecond * elapsed;

        if (profit < 0) profit = 0;

        profit = Number(profit.toFixed(2));

        el.innerText = money(profit);
    }

    updateAiTimers();
    setInterval(updateAiTimers, 1000);

    updateAiProfitLive();
    setInterval(updateAiProfitLive, 1000);
</script>

@endsection