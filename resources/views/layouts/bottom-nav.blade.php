@php
    $isHome = request()->is('home') || request()->is('/') || request()->is('dashboard');
    $isMarkets = request()->is('markets') || request()->is('trade') || request()->is('trade/*');
    $isAi = request()->is('ai') || request()->is('ai/*');
    $isReferrals = request()->is('referrals');
    $isAssets = request()->is('wallet') || request()->is('wallet/*') || request()->is('assets');
@endphp

<style>
    .dx-bottom-nav{
        position:fixed;
        left:50%;
        transform:translateX(-50%);
        bottom:14px;
        width:calc(100% - 24px);
        max-width:430px;
        padding:14px 16px 12px;
        display:grid;
        grid-template-columns:repeat(5,1fr);
        align-items:end;
        gap:8px;
        border-radius:28px;
        background:
            linear-gradient(180deg, rgba(21,28,23,0.97) 0%, rgba(12,17,14,0.97) 100%);
        border:1px solid rgba(184,255,59,0.12);
        box-shadow:
            0 18px 40px rgba(0,0,0,0.38),
            inset 0 1px 0 rgba(255,255,255,0.04);
        backdrop-filter:blur(12px);
        z-index:9999;
    }

    .dx-bottom-item{
        text-decoration:none;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:flex-end;
        gap:6px;
        min-height:46px;
        color:#eef6ef;
        font-size:11px;
        font-weight:800;
        line-height:1.2;
        opacity:.92;
        transition:.16s ease;
        -webkit-tap-highlight-color:transparent;
    }

    .dx-bottom-item svg{
        width:18px;
        height:18px;
        transition:.16s ease;
    }

    .dx-bottom-item:hover{
        color:#dfff78;
        transform:translateY(-1px);
    }

    .dx-bottom-item:active{
        transform:scale(.96);
    }

    .dx-bottom-item.active{
        color:#dfff78;
        text-shadow:0 0 10px rgba(184,255,59,0.18);
    }

    .dx-bottom-item.active svg{
        filter:drop-shadow(0 0 6px rgba(184,255,59,0.25));
    }

    .dx-bottom-ai-wrap{
        display:flex;
        justify-content:center;
        align-items:flex-end;
    }

    .dx-bottom-ai{
        width:72px;
        height:72px;
        margin-top:-36px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        background:radial-gradient(circle,#e4ff70 0%, #b8ef2f 100%);
        color:#172014;
        font-size:26px;
        font-weight:900;
        box-shadow:
            0 0 0 8px rgba(18,24,20,0.97),
            0 0 24px rgba(184,255,59,0.28),
            0 12px 30px rgba(184,255,59,0.24);
        transition:.16s ease;
        position:relative;
        -webkit-tap-highlight-color:transparent;
    }

    .dx-bottom-ai:hover{
        transform:translateY(-2px) scale(1.02);
    }

    .dx-bottom-ai:active{
        transform:scale(.95);
    }

    .dx-bottom-ai.active{
        box-shadow:
            0 0 0 8px rgba(18,24,20,0.97),
            0 0 34px rgba(184,255,59,0.48),
            0 14px 34px rgba(184,255,59,0.30);
        filter:brightness(1.04);
    }

    @media (max-width:420px){
        .dx-bottom-nav{
            width:calc(100% - 18px);
            padding:13px 12px 11px;
            gap:6px;
        }

        .dx-bottom-item{
            font-size:10px;
        }

        .dx-bottom-ai{
            width:68px;
            height:68px;
            font-size:24px;
        }
    }
</style>

<div class="dx-bottom-nav">
    <a href="/home" class="dx-bottom-item {{ $isHome ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M4 11.5L12 5L20 11.5V19C20 19.55 19.55 20 19 20H15V14H9V20H5C4.45 20 4 19.55 4 19V11.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        </svg>
        <span>Home</span>
    </a>

    <a href="/markets" class="dx-bottom-item {{ $isMarkets ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M5 16L9 12L13 15L19 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 8H19V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Markets</span>
    </a>

    <div class="dx-bottom-ai-wrap">
        <a href="/ai" class="dx-bottom-ai {{ $isAi ? 'active' : '' }}">AI</a>
    </div>

    <a href="/referrals" class="dx-bottom-item {{ $isReferrals ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M16 21V19C16 17.34 14.66 16 13 16H7C5.34 16 4 17.34 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M10 13C12.21 13 14 11.21 14 9C14 6.79 12.21 5 10 5C7.79 5 6 6.79 6 9C6 11.21 7.79 13 10 13Z" stroke="currentColor" stroke-width="2"/>
            <path d="M20 8V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M23 11H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span>Referrals</span>
    </a>

    <a href="/wallet" class="dx-bottom-item {{ $isAssets ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M3 7.5C3 6.12 4.12 5 5.5 5H18.5C19.88 5 21 6.12 21 7.5V16.5C21 17.88 19.88 19 18.5 19H5.5C4.12 19 3 17.88 3 16.5V7.5Z" stroke="currentColor" stroke-width="2"/>
            <path d="M16 12H21" stroke="currentColor" stroke-width="2"/>
            <circle cx="16.5" cy="12" r="1" fill="currentColor"/>
        </svg>
        <span>Assets</span>
    </a>
</div>