@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $hasTradingPassword = !empty($user->trading_password);
@endphp

<style>
.trading-password-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.trading-password-hero{
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

.trading-password-hero::before{
    content:"";
    position:absolute;
    right:-30px;
    top:-20px;
    width:170px;
    height:170px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.12) 0%, rgba(184,255,59,0) 72%);
}

.trading-password-hero::after{
    content:"";
    position:absolute;
    left:-55px;
    bottom:-55px;
    width:190px;
    height:190px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(98,255,154,0.07) 0%, rgba(98,255,154,0) 72%);
}

.trading-password-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
}

.trading-password-hero-left{
    min-width:0;
}

.trading-password-hero-title{
    font-size:28px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.2;
    margin-bottom:8px;
}

.trading-password-hero-sub{
    font-size:13px;
    color:#9fb4a4;
    line-height:1.7;
    max-width:290px;
}

.trading-password-icon{
    width:78px;
    height:78px;
    border-radius:24px;
    flex:0 0 78px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(180deg,#d8ff72 0%, #b8ff3b 100%);
    box-shadow:0 14px 28px rgba(184,255,59,0.18);
    border:1px solid rgba(255,255,255,0.10);
    color:#08120c;
    font-size:34px;
    font-weight:900;
}

.trading-password-card{
    border-radius:28px;
    padding:22px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.trading-password-card-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    margin-bottom:18px;
}

.trading-password-card-title{
    font-size:22px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.2;
    margin-bottom:6px;
}

.trading-password-card-sub{
    font-size:12px;
    color:#8da095;
    line-height:1.7;
    max-width:320px;
}

.password-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:9px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
}

.password-chip.set{
    color:#07120b;
    background:#7fffd4;
}

.password-chip.setup{
    color:#07120b;
    background:#ffd76a;
}

.input-group{
    margin-bottom:16px;
}

.input-label{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:8px;
    font-size:14px;
    font-weight:800;
    color:#eef4ef;
}

.input-note{
    font-size:11px;
    color:#8da095;
    font-weight:700;
}

.input{
    width:100%;
    border:none;
    outline:none;
    border-radius:18px;
    padding:15px 16px;
    background:#131a16;
    color:#fff;
    font-size:15px;
    border:1px solid rgba(255,255,255,0.06);
    transition:.2s ease;
}

.input::placeholder{
    color:#728178;
}

.input:focus{
    border-color:rgba(184,255,59,0.35);
    box-shadow:0 0 0 3px rgba(184,255,59,0.08);
}

.password-status{
    margin-top:8px;
    font-size:12px;
    font-weight:800;
    display:none;
}

.password-status.show{
    display:block;
}

.password-status.ok{
    color:#7fffd4;
}

.password-status.bad{
    color:#ff8798;
}

.saved-box{
    border-radius:22px;
    padding:18px;
    background:rgba(184,255,59,0.06);
    border:1px solid rgba(184,255,59,0.12);
    margin-bottom:18px;
}

.saved-box-title{
    font-size:14px;
    font-weight:900;
    color:#f4fff8;
    margin-bottom:10px;
}

.saved-note{
    font-size:13px;
    color:#dfffc3;
    line-height:1.8;
}

.support-box{
    border-radius:22px;
    padding:16px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.05);
    margin-bottom:18px;
}

.support-title{
    font-size:14px;
    font-weight:900;
    color:#fff;
    margin-bottom:8px;
}

.support-box ul{
    padding-left:18px;
}

.support-box li{
    color:#d8e1db;
    font-size:13px;
    line-height:1.8;
    margin-bottom:3px;
}

.submit-btn{
    width:100%;
    border:none;
    cursor:pointer;
    border-radius:22px;
    padding:17px 16px;
    background:linear-gradient(180deg, #dbff7b 0%, #b8ff3b 100%);
    color:#0c120d;
    font-size:17px;
    font-weight:900;
    box-shadow:
        0 12px 24px rgba(184,255,59,0.18),
        inset 0 1px 0 rgba(255,255,255,0.35);
    transition:.22s ease;
}

.submit-btn:hover{
    transform:translateY(-1px);
    box-shadow:
        0 16px 28px rgba(184,255,59,0.22),
        inset 0 1px 0 rgba(255,255,255,0.40);
}

.action-links{
    display:grid;
    grid-template-columns:1fr;
    gap:12px;
    margin-top:16px;
}

.action-link{
    text-decoration:none;
    text-align:center;
    border-radius:18px;
    padding:14px 12px;
    font-size:14px;
    font-weight:800;
    transition:.2s ease;
    border:1px solid rgba(255,255,255,0.06);
    background:rgba(255,255,255,0.03);
    color:#f3f6f3;
}

.action-link:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.20);
}

@media (max-width:700px){
    .trading-password-hero-inner{
        align-items:flex-start;
    }

    .trading-password-hero-title{
        font-size:24px;
    }

    .trading-password-icon{
        width:68px;
        height:68px;
        flex:0 0 68px;
        border-radius:20px;
        font-size:30px;
    }

    .trading-password-card-head{
        flex-direction:column;
    }
}
</style>

<div class="trading-password-page">

    @if(session('success'))
        <div style="border-radius:18px;padding:14px 16px;background:rgba(46,204,113,0.12);color:#8ff0b3;border:1px solid rgba(46,204,113,0.18);font-size:14px;font-weight:700;line-height:1.6;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="border-radius:18px;padding:14px 16px;background:rgba(255,95,116,0.10);color:#ff9eab;border:1px solid rgba(255,95,116,0.20);font-size:14px;font-weight:700;line-height:1.6;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="border-radius:18px;padding:14px 16px;background:rgba(255,95,116,0.10);color:#ff9eab;border:1px solid rgba(255,95,116,0.20);font-size:14px;font-weight:700;line-height:1.6;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="trading-password-hero">
        <div class="trading-password-hero-inner">
            <div class="trading-password-hero-left">
                <div class="trading-password-hero-title">Trading Password</div>
                <div class="trading-password-hero-sub">
                    Set a password مخصوص للتداول والسحب. هذا الباسورد منفصل عن كلمة مرور تسجيل الدخول لزيادة الحماية.
                </div>
            </div>

            <div class="trading-password-icon">✱</div>
        </div>
    </div>

    <div class="trading-password-card">
        <div class="trading-password-card-head">
            <div>
                <div class="trading-password-card-title">Security Setup</div>
                <div class="trading-password-card-sub">
                    Enter your trading password twice for confirmation. This password is used for sensitive actions like withdrawals.
                </div>
            </div>

            <div class="password-chip {{ $hasTradingPassword ? 'set' : 'setup' }}">
                {{ $hasTradingPassword ? 'Already Set' : 'Setup Required' }}
            </div>
        </div>

        @if($hasTradingPassword)
            <div class="saved-box">
                <div class="saved-box-title">Trading Password Active</div>
                <div class="saved-note">
                    Your trading password is already configured for this account.<br>
                    If you want to change it or if you face any problem, please contact support or ask admin to reset it.
                </div>
            </div>
        @else
            <form method="POST" action="/trading-password" id="tradingPasswordForm" novalidate>
                @csrf

                <div class="input-group">
                    <label class="input-label">
                        <span>New Trading Password</span>
                        <span class="input-note">Minimum 6 characters</span>
                    </label>
                    <input
                        type="password"
                        name="trading_password"
                        id="tradingPasswordInput"
                        class="input"
                        placeholder="Enter new trading password"
                        required
                    >
                </div>

                <div class="input-group">
                    <label class="input-label">
                        <span>Confirm Trading Password</span>
                        <span class="input-note">Repeat the same password</span>
                    </label>
                    <input
                        type="password"
                        name="trading_password_confirmation"
                        id="tradingPasswordConfirmInput"
                        class="input"
                        placeholder="Confirm trading password"
                        required
                    >
                    <div id="passwordMatchStatus" class="password-status">
                        Match status
                    </div>
                </div>

                <div class="support-box">
                    <div class="support-title">Important Notice</div>
                    <ul>
                        <li>Your trading password is different from your login password.</li>
                        <li>You must enter it correctly during withdrawal requests.</li>
                        <li>If you want to change it later or face any issue, please contact support or ask admin to reset it.</li>
                        <li>For better security, use a password you do not reuse anywhere else.</li>
                    </ul>
                </div>

                <button type="submit" class="submit-btn">
                    Save Trading Password
                </button>
            </form>
        @endif

        <div class="action-links">
            @if($hasTradingPassword)
                <a href="/withdraw" class="action-link">Continue to Withdraw</a>
            @else
                <a href="/profile" class="action-link">Back to Profile</a>
            @endif
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('tradingPasswordInput');
    const confirmInput = document.getElementById('tradingPasswordConfirmInput');
    const statusBox = document.getElementById('passwordMatchStatus');
    const form = document.getElementById('tradingPasswordForm');

    function validatePasswords() {
        if (!passwordInput || !confirmInput || !statusBox) return true;

        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (password === '' && confirm === '') {
            statusBox.className = 'password-status';
            statusBox.textContent = '';
            return false;
        }

        statusBox.classList.add('show');

        if (password.length > 0 && password.length < 6) {
            statusBox.classList.remove('ok');
            statusBox.classList.add('bad');
            statusBox.textContent = 'Trading password must be at least 6 characters';
            return false;
        }

        if (confirm === '') {
            statusBox.classList.remove('ok');
            statusBox.classList.add('bad');
            statusBox.textContent = 'Please confirm your trading password';
            return false;
        }

        if (password !== confirm) {
            statusBox.classList.remove('ok');
            statusBox.classList.add('bad');
            statusBox.textContent = 'Trading password confirmation does not match';
            return false;
        }

        statusBox.classList.remove('bad');
        statusBox.classList.add('ok');
        statusBox.textContent = 'Trading passwords match';
        return true;
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', validatePasswords);
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', validatePasswords);
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            const isValid = validatePasswords();

            if (!isValid) {
                e.preventDefault();
                alert('Please enter a valid trading password and confirm it correctly.');
            }
        });
    }
});
</script>

@endsection