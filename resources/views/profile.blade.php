@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();

    $accountId = $user->account_id ?? '----';
    $displayName = $user->name . '(' . $accountId . ')';

    $nameParts = preg_split('/\s+/', trim($user->name));
    $avatarText = '';

    if (count($nameParts) >= 2) {
        $avatarText = strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1));
    } else {
        $avatarText = strtoupper(mb_substr($user->name, 0, 2));
    }

    $verificationStatus = $user->verification_status ?? 'unverified';

    $verificationMap = [
        'unverified' => [
            'label' => 'Not Verified',
            'class' => 'status-unverified',
            'icon' => '!',
            'desc' => 'Please upload front and back ID photos and a selfie with your ID to start verification.'
        ],
        'pending' => [
            'label' => 'Pending Review',
            'class' => 'status-pending',
            'icon' => '…',
            'desc' => 'Your documents were submitted and are currently under admin review.'
        ],
        'verified' => [
            'label' => 'Verified',
            'class' => 'status-verified',
            'icon' => '✓',
            'desc' => 'Your account has been successfully verified and full platform features are available.'
        ],
        'rejected' => [
            'label' => 'Rejected',
            'class' => 'status-rejected',
            'icon' => '×',
            'desc' => 'Verification was rejected. Please review your images and submit them correctly again.'
        ],
    ];

    $currentVerification = $verificationMap[$verificationStatus] ?? $verificationMap['unverified'];

    $languageCode = 'en';
    $currentLanguage = 'English';
    $isRtl = false;

    $withdrawAddressStatus = !empty($user->withdraw_wallet_address) ? 'Saved & Locked' : 'Not Set';
    $tradingPasswordStatus = !empty($user->trading_password) ? 'Set' : 'Not Set';
    $google2faStatus = !empty($user->google2fa_enabled) ? 'Enabled' : 'Not Enabled';
@endphp

<style>
.profile-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.profile-page.rtl{
    direction:rtl;
}

.profile-page.rtl .profile-hero-inner,
.profile-page.rtl .profile-left,
.profile-page.rtl .profile-item-left{
    direction:rtl;
}

.profile-page.rtl .profile-arrow,
.profile-page.rtl .profile-item-arrow{
    transform:scaleX(-1);
}

.profile-hero{
    position:relative;
    overflow:hidden;
    border-radius:30px;
    padding:22px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.58) 0%, rgba(5,10,7,0.80) 100%),
        radial-gradient(circle at 78% 24%, rgba(184,255,59,0.20) 0%, rgba(184,255,59,0) 30%),
        radial-gradient(circle at 20% 80%, rgba(98,255,154,0.08) 0%, rgba(98,255,154,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.12);
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.profile-hero::before{
    content:"";
    position:absolute;
    right:-30px;
    top:-20px;
    width:170px;
    height:170px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.12) 0%, rgba(184,255,59,0) 72%);
}

.profile-hero::after{
    content:"";
    position:absolute;
    left:-55px;
    bottom:-55px;
    width:190px;
    height:190px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(98,255,154,0.07) 0%, rgba(98,255,154,0) 72%);
}

.profile-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:18px;
}

.profile-left{
    display:flex;
    align-items:center;
    gap:16px;
    min-width:0;
}

.profile-avatar-wrap{
    position:relative;
    width:84px;
    height:84px;
    flex:0 0 84px;
}

.profile-avatar-ring{
    position:absolute;
    inset:0;
    border-radius:26px;
    padding:2px;
    background:linear-gradient(135deg, rgba(199,255,97,1) 0%, rgba(141,255,28,1) 100%);
    box-shadow:0 12px 30px rgba(184,255,59,0.26);
}

.profile-avatar{
    width:100%;
    height:100%;
    border-radius:24px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(180deg,#121c16 0%, #0d1510 100%);
    color:#dfffab;
    font-size:28px;
    font-weight:900;
    letter-spacing:1px;
    border:1px solid rgba(255,255,255,0.04);
}

.profile-avatar-badge{
    position:absolute;
    right:-4px;
    bottom:-4px;
    width:28px;
    height:28px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:14px;
    font-weight:900;
    border:3px solid #0d1711;
    box-shadow:0 8px 18px rgba(0,0,0,0.25);
}

.profile-avatar-badge.status-verified{
    background:#7fffd4;
    color:#07120b;
}

.profile-avatar-badge.status-pending{
    background:#ffd76a;
    color:#07120b;
}

.profile-avatar-badge.status-unverified{
    background:#8da095;
    color:#07120b;
}

.profile-avatar-badge.status-rejected{
    background:#ff8798;
    color:#07120b;
}

.profile-meta{
    min-width:0;
    display:flex;
    flex-direction:column;
    gap:6px;
}

.profile-name{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
    font-size:24px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.2;
    word-break:break-word;
}

.profile-status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:5px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    box-shadow:0 8px 18px rgba(0,0,0,0.18);
}

.profile-status.status-verified{
    color:#07120b;
    background:#7fffd4;
}

.profile-status.status-pending{
    color:#07120b;
    background:#ffd76a;
}

.profile-status.status-unverified{
    color:#f4fff8;
    background:#3b4a3f;
}

.profile-status.status-rejected{
    color:#fff;
    background:#ff5f74;
}

.profile-sub{
    font-size:13px;
    color:#9fb4a4;
    line-height:1.7;
}

.profile-id-chip{
    margin-top:4px;
    display:inline-flex;
    align-items:center;
    gap:8px;
    width:fit-content;
    padding:8px 12px;
    border-radius:14px;
    font-size:12px;
    font-weight:800;
    color:#dfffc3;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.12);
}

.profile-arrow{
    width:42px;
    height:42px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:900;
    color:#f4fff8;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.10);
    flex:0 0 42px;
}

.profile-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;
}

.profile-shortcut{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    padding:18px 14px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:10px;
    min-height:120px;
    text-align:center;
    transition:.22s ease;
    text-decoration:none;
}

.profile-shortcut::before{
    content:"";
    position:absolute;
    inset:auto auto -30px -25px;
    width:90px;
    height:90px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.10) 0%, rgba(184,255,59,0) 72%);
}

.profile-shortcut:hover{
    transform:translateY(-2px);
    border-color:rgba(184,255,59,0.18);
}

.profile-shortcut-icon{
    position:relative;
    z-index:2;
    width:52px;
    height:52px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:900;
    color:#dfffab;
    background:rgba(184,255,59,0.10);
    border:1px solid rgba(184,255,59,0.14);
    box-shadow:0 10px 18px rgba(184,255,59,0.08);
}

.profile-shortcut-name{
    position:relative;
    z-index:2;
    font-size:15px;
    font-weight:800;
    color:#f4fff8;
    line-height:1.4;
}

.profile-shortcut-sub{
    position:relative;
    z-index:2;
    font-size:12px;
    color:#93a69a;
    line-height:1.4;
}

.profile-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.profile-item{
    border-radius:22px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    transition:.22s ease;
    text-decoration:none;
}

button.profile-item{
    width:100%;
    cursor:pointer;
    border:none;
}

.profile-item:hover{
    transform:translateY(-2px);
    border-color:rgba(184,255,59,0.18);
}

.profile-item-left{
    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;
}

.profile-item-icon{
    width:46px;
    height:46px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    font-weight:900;
    color:#dfffab;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.10);
    flex:0 0 46px;
}

.profile-item-text-wrap{
    display:flex;
    flex-direction:column;
    gap:4px;
    text-align:start;
}

.profile-item-text{
    font-size:17px;
    font-weight:800;
    color:#f4fff8;
    line-height:1.35;
}

.profile-item-sub{
    font-size:12px;
    color:#8da095;
    line-height:1.5;
}

.profile-item-arrow{
    font-size:24px;
    color:#cfe1d2;
    font-weight:900;
    flex:0 0 auto;
}

.logout-btn{
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:22px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(255,95,116,0.28);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
    color:#ff8798;
    font-size:18px;
    font-weight:900;
    transition:.22s ease;
    text-decoration:none;
}

.logout-btn:hover{
    transform:translateY(-2px);
    border-color:rgba(255,95,116,0.42);
    color:#ff9dac;
}

.language-modal{
    position:fixed;
    inset:0;
    background:rgba(2,5,3,0.72);
    backdrop-filter:blur(8px);
    -webkit-backdrop-filter:blur(8px);
    z-index:2000;
    display:none;
    align-items:flex-end;
    justify-content:center;
    padding:18px;
}

.language-modal.show{
    display:flex;
}

.language-sheet{
    width:min(100%, 560px);
    border-radius:28px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.12);
    box-shadow:0 22px 50px rgba(0,0,0,0.40);
    overflow:hidden;
    animation:sheetUp .22s ease;
}

@keyframes sheetUp{
    from{
        transform:translateY(24px);
        opacity:0;
    }
    to{
        transform:translateY(0);
        opacity:1;
    }
}

.language-sheet-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:18px 20px;
    border-bottom:1px solid rgba(184,255,59,0.08);
}

.language-sheet-title{
    font-size:18px;
    font-weight:900;
    color:#f4fff8;
}

.language-close{
    width:42px;
    height:42px;
    border:none;
    border-radius:14px;
    cursor:pointer;
    font-size:20px;
    font-weight:900;
    color:#f4fff8;
    background:rgba(255,255,255,0.05);
    border:1px solid rgba(184,255,59,0.10);
}

.language-list{
    padding:14px;
    display:flex;
    flex-direction:column;
    gap:10px;
    max-height:70vh;
    overflow:auto;
}

.language-option{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:16px 18px;
    border-radius:18px;
    background:rgba(255,255,255,0.02);
    border:1px solid rgba(184,255,59,0.08);
    transition:.2s ease;
}

.language-option.active{
    background:rgba(184,255,59,0.08);
    border-color:rgba(184,255,59,0.22);
}

.language-option.soon{
    opacity:.88;
}

.language-option-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.language-flag{
    width:42px;
    height:42px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.10);
}

.language-name{
    font-size:16px;
    font-weight:800;
    color:#f4fff8;
}

.language-check{
    font-size:16px;
    font-weight:900;
    color:#dfffab;
}

.language-soon{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    color:#ffe49a;
    background:rgba(240,185,11,0.12);
    border:1px solid rgba(240,185,11,0.18);
    white-space:nowrap;
}

@media (max-width:700px){
    .profile-hero-inner{
        align-items:flex-start;
    }

    .profile-grid{
        grid-template-columns:1fr;
    }

    .profile-name{
        font-size:21px;
    }

    .profile-avatar-wrap{
        width:74px;
        height:74px;
        flex:0 0 74px;
    }

    .profile-avatar{
        font-size:24px;
        border-radius:22px;
    }

    .language-sheet{
        width:100%;
        border-radius:24px;
    }
}
</style>

<div class="profile-page {{ $isRtl ? 'rtl' : '' }}">

    <div class="profile-hero">
        <div class="profile-hero-inner">
            <div class="profile-left">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar-ring">
                        <div class="profile-avatar">{{ $avatarText }}</div>
                    </div>
                    <div class="profile-avatar-badge {{ $currentVerification['class'] }}">
                        {{ $currentVerification['icon'] }}
                    </div>
                </div>

                <div class="profile-meta">
                    <div class="profile-name">
                        {{ $displayName }}
                        <span class="profile-status {{ $currentVerification['class'] }}">
                            {{ $currentVerification['label'] }}
                        </span>
                    </div>

                    <div class="profile-sub">
                        {{ $currentVerification['desc'] }}
                    </div>

                    <div class="profile-id-chip">
                        <span>UID</span>
                        <span>#{{ $accountId }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-arrow">›</div>
        </div>
    </div>

    <div class="profile-grid">

        <a href="/withdraw/address" class="profile-shortcut">
            <div class="profile-shortcut-icon">⌁</div>
            <div class="profile-shortcut-name">Withdrawal Address</div>
            <div class="profile-shortcut-sub">{{ $withdrawAddressStatus }}</div>
        </a>

        <a href="/password" class="profile-shortcut">
            <div class="profile-shortcut-icon">🔒</div>
            <div class="profile-shortcut-name">Login Password</div>
            <div class="profile-shortcut-sub">Account access</div>
        </a>

        <a href="/trading-password" class="profile-shortcut">
            <div class="profile-shortcut-icon">₿</div>
            <div class="profile-shortcut-name">Trading Password</div>
            <div class="profile-shortcut-sub">{{ $tradingPasswordStatus }}</div>
        </a>

    </div>

    <div class="profile-list">

        <a href="/verification" class="profile-item">
            <div class="profile-item-left">
                <div class="profile-item-icon">ID</div>
                <div class="profile-item-text-wrap">
                    <div class="profile-item-text">Identity Verification</div>
                    <div class="profile-item-sub">{{ $currentVerification['label'] }}</div>
                </div>
            </div>
            <div class="profile-item-arrow">›</div>
        </a>

        <a href="/google-auth" class="profile-item">
            <div class="profile-item-left">
                <div class="profile-item-icon">G</div>
                <div class="profile-item-text-wrap">
                    <div class="profile-item-text">Google Authenticator</div>
                    <div class="profile-item-sub">{{ $google2faStatus }}</div>
                </div>
            </div>
            <div class="profile-item-arrow">›</div>
        </a>

        <button type="button" class="profile-item" id="openLanguageModal">
            <div class="profile-item-left">
                <div class="profile-item-icon">文</div>
                <div class="profile-item-text-wrap">
                    <div class="profile-item-text">Language</div>
                    <div class="profile-item-sub">{{ $currentLanguage }}</div>
                </div>
            </div>
            <div class="profile-item-arrow">›</div>
        </button>

    </div>

    <a href="/logout" class="logout-btn">
        Logout
    </a>

</div>

<div class="language-modal" id="languageModal">
    <div class="language-sheet">
        <div class="language-sheet-header">
            <div class="language-sheet-title">Language</div>
            <button type="button" class="language-close" id="closeLanguageModal">×</button>
        </div>

        <div class="language-list">
            <div class="language-option active">
                <div class="language-option-left">
                    <div class="language-flag">🇬🇧</div>
                    <div class="language-name">English</div>
                </div>
                <div class="language-check">✓</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇸🇦</div>
                    <div class="language-name">العربية</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇩🇪</div>
                    <div class="language-name">Deutsch</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇪🇸</div>
                    <div class="language-name">Español</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇸🇪</div>
                    <div class="language-name">Svenska</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇹🇷</div>
                    <div class="language-name">Türkçe</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇫🇷</div>
                    <div class="language-name">Français</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇷🇺</div>
                    <div class="language-name">Русский</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇵🇹</div>
                    <div class="language-name">Português</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>

            <div class="language-option soon">
                <div class="language-option-left">
                    <div class="language-flag">🇮🇹</div>
                    <div class="language-name">Italiano</div>
                </div>
                <div class="language-soon">Coming Soon</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('openLanguageModal');
    const closeBtn = document.getElementById('closeLanguageModal');
    const modal = document.getElementById('languageModal');

    if (openBtn && modal) {
        openBtn.addEventListener('click', function () {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function () {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    }
});
</script>

@endsection