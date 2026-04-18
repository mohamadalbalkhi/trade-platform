@extends('layouts.app')

@section('content')

@php
    $availableBalance = $wallet ? (float) $wallet->balance : 0;
    $feeRate = isset($feeRate) ? (float) $feeRate : 0.15;
    $minWithdraw = isset($minWithdraw) ? (float) $minWithdraw : 10;
    $googleEnabled = isset($user) && $user->google2fa_enabled;
    $hasLockedAddress = isset($user) && !empty($user->withdraw_wallet_address);
@endphp

<style>
.withdraw-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.alert{
    border-radius:18px;
    padding:14px 16px;
    font-size:14px;
    font-weight:700;
    line-height:1.65;
    border:1px solid transparent;
}

.alert.success{
    background:rgba(46,204,113,0.12);
    color:#8ff0b3;
    border-color:rgba(46,204,113,0.18);
}

.alert.error{
    background:rgba(255,127,144,0.10);
    color:#ffadb7;
    border-color:rgba(255,127,144,0.22);
}

.card{
    background:linear-gradient(180deg, rgba(20,28,24,0.94) 0%, rgba(13,20,16,0.98) 100%);
    border:1px solid rgba(255,255,255,0.06);
    border-radius:30px;
    box-shadow:
        0 14px 34px rgba(0,0,0,0.34),
        inset 0 1px 0 rgba(255,255,255,0.03);
}

.hero-card{
    position:relative;
    overflow:hidden;
    padding:24px 22px 20px;
}

.hero-card::before{
    content:"";
    position:absolute;
    width:190px;
    height:190px;
    right:-45px;
    top:-55px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.18) 0%, transparent 72%);
}

.hero-card::after{
    content:"";
    position:absolute;
    width:120px;
    height:120px;
    left:-35px;
    bottom:-45px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.08) 0%, transparent 72%);
}

.hero-top{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
}

.hero-left h2{
    font-size:31px;
    font-weight:900;
    margin-bottom:6px;
    color:#fff;
}

.hero-left p{
    color:#bac6be;
    font-size:14px;
    line-height:1.65;
    max-width:320px;
}

.coin-badge{
    width:84px;
    height:84px;
    border-radius:26px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex:0 0 84px;
    background:
        radial-gradient(circle at 35% 30%, #faffca 0%, #dfff72 22%, #b8ff3b 58%, #96d91a 100%);
    box-shadow:
        0 0 22px rgba(184,255,59,0.22),
        0 12px 24px rgba(0,0,0,0.24);
    border:1px solid rgba(255,255,255,0.18);
    overflow:hidden;
}

.coin-badge svg{
    width:48px;
    height:48px;
    display:block;
}

.stats-grid{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:12px;
    margin-top:18px;
}

.stat-box{
    border-radius:22px;
    padding:15px 12px;
    text-align:center;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.05);
}

.stat-title{
    font-size:11px;
    color:#a6b2aa;
    text-transform:uppercase;
    letter-spacing:.7px;
    font-weight:700;
    margin-bottom:8px;
}

.stat-value{
    font-size:20px;
    font-weight:900;
    color:#fff;
}

.stat-value.lime{
    color:#cfff43;
}

.form-card{
    padding:22px;
}

.section-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:16px;
    margin-bottom:18px;
}

.section-title{
    font-size:23px;
    font-weight:900;
    margin-bottom:6px;
    color:#fff;
}

.section-sub{
    color:#aeb9b2;
    font-size:13px;
    line-height:1.65;
    max-width:350px;
}

.security-chip{
    padding:10px 12px;
    border-radius:16px;
    font-size:12px;
    font-weight:800;
    white-space:nowrap;
    border:1px solid rgba(184,255,59,0.14);
    background:rgba(184,255,59,0.07);
    color:#cfff43;
}

.setup-box{
    border-radius:22px;
    padding:18px;
    background:rgba(255,210,125,0.10);
    border:1px solid rgba(255,210,125,0.18);
}

.setup-title{
    font-size:15px;
    font-weight:900;
    color:#ffe2a6;
    margin-bottom:8px;
}

.setup-text{
    font-size:13px;
    color:#f3e2ba;
    line-height:1.7;
    margin-bottom:12px;
}

.setup-action{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    padding:12px 14px;
    border-radius:16px;
    background:linear-gradient(180deg, #ffe39f 0%, #ffd27d 100%);
    color:#16120b;
    font-size:13px;
    font-weight:900;
}

.locked-address-box{
    border-radius:22px;
    padding:16px;
    background:linear-gradient(180deg, rgba(184,255,59,0.06) 0%, rgba(184,255,59,0.03) 100%);
    border:1px solid rgba(184,255,59,0.12);
    margin-bottom:16px;
}

.locked-address-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:10px;
}

.locked-title{
    font-size:14px;
    font-weight:900;
    color:#fff;
}

.locked-chip{
    padding:8px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    color:#eaffb3;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.16);
}

.locked-address{
    font-size:14px;
    line-height:1.7;
    color:#e9f2ec;
    word-break:break-all;
    background:rgba(0,0,0,0.14);
    border:1px solid rgba(255,255,255,0.04);
    border-radius:16px;
    padding:14px;
}

.locked-sub{
    margin-top:10px;
    font-size:12px;
    line-height:1.6;
    color:#aab5ae;
}

.info-strip{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
    margin-bottom:16px;
}

.mini-box{
    border-radius:18px;
    padding:14px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.05);
}

.mini-title{
    color:#a7b2aa;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    margin-bottom:7px;
    letter-spacing:.6px;
}

.mini-value{
    font-size:16px;
    font-weight:900;
    color:#cfff43;
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
    font-weight:700;
    color:#8ea098;
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

.calc-card{
    margin:6px 0 18px;
    border-radius:22px;
    padding:16px;
    background:linear-gradient(180deg, rgba(184,255,59,0.06) 0%, rgba(184,255,59,0.03) 100%);
    border:1px solid rgba(184,255,59,0.12);
}

.calc-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    padding:9px 0;
    font-size:14px;
    color:#dce6df;
    border-bottom:1px solid rgba(255,255,255,0.05);
}

.calc-row:last-child{
    border-bottom:none;
    padding-bottom:0;
}

.calc-row:first-child{
    padding-top:0;
}

.calc-label{
    color:#aeb8b1;
    font-weight:700;
}

.calc-value{
    font-weight:900;
    color:#fff;
}

.calc-value.fee{
    color:#ffd27d;
}

.calc-value.net{
    color:#cfff43;
    font-size:18px;
}

.warning-box{
    margin-top:8px;
    margin-bottom:18px;
    border-radius:22px;
    padding:16px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.05);
}

.warning-title{
    font-size:14px;
    font-weight:900;
    color:#fff;
    margin-bottom:8px;
}

.warning-box ul{
    padding-left:18px;
}

.warning-box li{
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

.submit-btn:disabled{
    opacity:.55;
    cursor:not-allowed;
    transform:none;
    box-shadow:none;
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

@media (max-width:600px){
    .hero-top{
        align-items:flex-start;
    }

    .hero-left h2{
        font-size:27px;
    }

    .coin-badge{
        width:72px;
        height:72px;
        flex-basis:72px;
        border-radius:22px;
    }

    .coin-badge svg{
        width:40px;
        height:40px;
    }

    .stats-grid,
    .info-strip{
        grid-template-columns:1fr;
    }

    .section-head{
        flex-direction:column;
    }
}
</style>

<div class="withdraw-page">

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

    @if($errors->any())
        <div class="alert error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card hero-card">
        <div class="hero-top">
            <div class="hero-left">
                <h2>USDT Withdraw</h2>
                <p>
                    Secure withdrawal through your locked TRC20 address with trading password and Google Authenticator confirmation.
                </p>
            </div>

            <div class="coin-badge" aria-label="USDT">
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="30" fill="#26A17B"/>
                    <path d="M36.45 28.2V24.3H46V18.35H18V24.3H27.55V28.17C19.79 28.53 14 30.06 14 31.9C14 33.74 19.79 35.27 27.55 35.63V48H36.45V35.6C44.16 35.25 49.9 33.72 49.9 31.9C49.9 30.08 44.16 28.56 36.45 28.2ZM32 34.31C22.62 34.31 15.02 33.22 15.02 31.88C15.02 30.67 21.2 29.67 29.46 29.49V33.45C30.28 33.49 31.12 33.51 32 33.51C32.88 33.51 33.72 33.49 34.54 33.45V29.5C42.74 29.68 48.88 30.68 48.88 31.88C48.88 33.22 41.31 34.31 32 34.31Z" fill="white"/>
                </svg>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-title">Available Balance</div>
                <div class="stat-value">${{ number_format($availableBalance, 2) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Asset</div>
                <div class="stat-value lime">USDT</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Network</div>
                <div class="stat-value">TRC20</div>
            </div>
        </div>
    </div>

    @if(!$hasLockedAddress)
        <div class="setup-box">
            <div class="setup-title">Address Setup Required</div>
            <div class="setup-text">
                You must save your USDT TRC20 withdrawal address first before you can continue.
            </div>
            <a href="/withdraw/address" class="setup-action">Set USDT TRC20 Address</a>
        </div>
    @endif

    @if(!$googleEnabled)
        <div class="setup-box">
            <div class="setup-title">Google Authenticator Required</div>
            <div class="setup-text">
                For withdrawal security, you must enable Google Authenticator first. Withdrawals are blocked until 2FA is activated correctly.
            </div>
            <a href="/google-auth" class="setup-action">Enable Google Authenticator</a>
        </div>
    @endif

    <div class="card form-card">
        <div class="section-head">
            <div>
                <div class="section-title">Withdrawal Request</div>
                <div class="section-sub">
                    Your withdrawal will be sent only to the saved address below. You must enter the correct trading password and the correct 6-digit Google Authenticator code.
                </div>
            </div>

            <div class="security-chip">
                {{ $googleEnabled ? '2FA Required' : '2FA Not Enabled' }}
            </div>
        </div>

        @if($hasLockedAddress)
            <div class="locked-address-box">
                <div class="locked-address-top">
                    <div class="locked-title">Saved Withdraw Address</div>
                    <div class="locked-chip">Locked</div>
                </div>

                <div class="locked-address">
                    {{ $user->withdraw_wallet_address }}
                </div>

                <div class="locked-sub">
                    Network: <strong>{{ $user->withdraw_wallet_network ?? 'TRC20' }}</strong><br>
                    This address is locked and cannot be changed by the user.
                </div>
            </div>
        @endif

        <form method="POST" action="/withdraw" id="withdrawForm" novalidate>
            @csrf

            <div class="info-strip">
                <div class="mini-box">
                    <div class="mini-title">Withdraw Type</div>
                    <div class="mini-value">USDT</div>
                </div>

                <div class="mini-box">
                    <div class="mini-title">Transfer Network</div>
                    <div class="mini-value">TRC20</div>
                </div>
            </div>

            <div class="input-group">
                <label class="input-label">
                    <span>Amount</span>
                    <span class="input-note">Minimum: ${{ number_format($minWithdraw, 2) }}</span>
                </label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="amount"
                    id="amountInput"
                    class="input"
                    placeholder="Enter withdrawal amount"
                    required
                    {{ (!$hasLockedAddress || !$googleEnabled) ? 'disabled' : '' }}
                >
            </div>

            <div class="calc-card">
                <div class="calc-row">
                    <div class="calc-label">Requested Amount</div>
                    <div class="calc-value" id="requestedAmount">$0.00</div>
                </div>
                <div class="calc-row">
                    <div class="calc-label">Withdrawal Fee (15%)</div>
                    <div class="calc-value fee" id="feeAmount">$0.00</div>
                </div>
                <div class="calc-row">
                    <div class="calc-label">Net Amount You Receive</div>
                    <div class="calc-value net" id="netAmount">$0.00</div>
                </div>
            </div>

            <div class="input-group">
                <label class="input-label">
                    <span>Receiving Platform</span>
                    <span class="input-note">Example: Binance / KuCoin / Bybit</span>
                </label>
                <input
                    type="text"
                    name="platform_name"
                    class="input"
                    placeholder="Enter receiving platform name"
                    required
                    {{ (!$hasLockedAddress || !$googleEnabled) ? 'disabled' : '' }}
                >
            </div>

            <div class="input-group">
                <label class="input-label">
                    <span>Trading Password</span>
                    <span class="input-note">Required for every withdrawal</span>
                </label>
                <input
                    type="password"
                    name="trading_password"
                    class="input"
                    placeholder="Enter trading password"
                    required
                    {{ (!$hasLockedAddress || !$googleEnabled) ? 'disabled' : '' }}
                >
            </div>

            <div class="input-group">
                <label class="input-label">
                    <span>Google Authenticator Code</span>
                    <span class="input-note">Must be the correct 6-digit code</span>
                </label>
                <input
                    type="text"
                    name="google_code"
                    id="googleCodeInput"
                    maxlength="6"
                    class="input"
                    placeholder="Enter 6-digit security code"
                    autocomplete="off"
                    inputmode="numeric"
                    required
                    {{ (!$hasLockedAddress || !$googleEnabled) ? 'disabled' : '' }}
                >
            </div>

            <div class="warning-box">
                <div class="warning-title">Important Notice</div>
                <ul>
                    <li>Only your <strong>saved and locked USDT TRC20 address</strong> is used for withdrawals.</li>
                    <li>Your <strong>trading password</strong> must be entered correctly.</li>
                    <li>Your <strong>Google Authenticator code</strong> must be valid and current.</li>
                    <li>Wrong 2FA code or wrong trading password will cause the request to be rejected.</li>
                    <li>Withdrawal fee is fixed at <strong>15%</strong> of the requested amount.</li>
                </ul>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn" {{ (!$hasLockedAddress || !$googleEnabled) ? 'disabled' : '' }}>
                Submit Withdrawal Request
            </button>
        </form>

        <div class="action-links">
            @if(!$hasLockedAddress)
                <a href="/withdraw/address" class="action-link">Set USDT TRC20 Address</a>
            @elseif(!$googleEnabled)
                <a href="/google-auth" class="action-link">Enable Google Authenticator</a>
            @else
                <a href="/wallet" class="action-link">Back to Wallet</a>
            @endif
        </div>
    </div>
</div>

<script>
const amountInput = document.getElementById('amountInput');
const requestedAmount = document.getElementById('requestedAmount');
const feeAmount = document.getElementById('feeAmount');
const netAmount = document.getElementById('netAmount');
const withdrawForm = document.getElementById('withdrawForm');
const googleCodeInput = document.getElementById('googleCodeInput');

const feeRate = 0.15;

function formatUsd(value) {
    return '$' + Number(value).toFixed(2);
}

function updateCalculation() {
    if (!amountInput) return;

    const amount = parseFloat(amountInput.value) || 0;
    const fee = amount * feeRate;
    const net = amount - fee;

    requestedAmount.textContent = formatUsd(amount);
    feeAmount.textContent = formatUsd(fee);
    netAmount.textContent = formatUsd(net > 0 ? net : 0);
}

if (amountInput) {
    amountInput.addEventListener('input', updateCalculation);
}

if (withdrawForm) {
    withdrawForm.addEventListener('submit', function (e) {
        if (!amountInput || amountInput.disabled) {
            e.preventDefault();
            alert('Please complete address setup and Google Authenticator setup first.');
            return;
        }

        const amount = parseFloat(amountInput.value) || 0;
        const googleCode = googleCodeInput ? googleCodeInput.value.trim() : '';

        if (amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid withdrawal amount.');
            return;
        }

        if (!/^\d{6}$/.test(googleCode)) {
            e.preventDefault();
            alert('Google Authenticator code must be exactly 6 digits.');
        }
    });
}

updateCalculation();
</script>

@endsection