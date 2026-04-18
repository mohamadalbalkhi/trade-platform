@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $hasSavedAddress = !empty($user->withdraw_wallet_address);
@endphp

<style>
.withdraw-address-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.withdraw-hero{
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

.withdraw-hero::before{
    content:"";
    position:absolute;
    right:-30px;
    top:-20px;
    width:170px;
    height:170px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.12) 0%, rgba(184,255,59,0) 72%);
}

.withdraw-hero::after{
    content:"";
    position:absolute;
    left:-55px;
    bottom:-55px;
    width:190px;
    height:190px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(98,255,154,0.07) 0%, rgba(98,255,154,0) 72%);
}

.withdraw-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
}

.withdraw-hero-left{
    min-width:0;
}

.withdraw-hero-title{
    font-size:28px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.2;
    margin-bottom:8px;
}

.withdraw-hero-sub{
    font-size:13px;
    color:#9fb4a4;
    line-height:1.7;
    max-width:280px;
}

.withdraw-coin{
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
}

.withdraw-coin svg{
    width:46px;
    height:46px;
    display:block;
}

.withdraw-card{
    border-radius:28px;
    padding:22px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.withdraw-card-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    margin-bottom:18px;
}

.withdraw-card-title{
    font-size:22px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.2;
    margin-bottom:6px;
}

.withdraw-card-sub{
    font-size:12px;
    color:#8da095;
    line-height:1.7;
    max-width:320px;
}

.address-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:9px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
}

.address-chip.locked{
    color:#07120b;
    background:#7fffd4;
}

.address-chip.setup{
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

.validation-box{
    margin-top:8px;
    font-size:12px;
    font-weight:800;
    display:none;
}

.validation-box.show{
    display:block;
}

.validation-box.ok{
    color:#7fffd4;
}

.validation-box.bad{
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

.saved-address{
    border-radius:16px;
    padding:14px;
    background:rgba(0,0,0,0.18);
    border:1px solid rgba(255,255,255,0.05);
    color:#dfffc3;
    font-size:14px;
    line-height:1.8;
    word-break:break-all;
}

.saved-note{
    margin-top:10px;
    font-size:12px;
    color:#9fb4a4;
    line-height:1.7;
}

.notice-box{
    border-radius:22px;
    padding:16px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.05);
    margin-bottom:18px;
}

.notice-title{
    font-size:14px;
    font-weight:900;
    color:#fff;
    margin-bottom:8px;
}

.notice-box ul{
    padding-left:18px;
}

.notice-box li{
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

@media (max-width:700px){
    .withdraw-hero-inner{
        align-items:flex-start;
    }

    .withdraw-hero-title{
        font-size:24px;
    }

    .withdraw-coin{
        width:68px;
        height:68px;
        flex:0 0 68px;
        border-radius:20px;
    }

    .withdraw-coin svg{
        width:40px;
        height:40px;
    }

    .withdraw-card-head{
        flex-direction:column;
    }
}
</style>

<div class="withdraw-address-page">

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

    <div class="withdraw-hero">
        <div class="withdraw-hero-inner">
            <div class="withdraw-hero-left">
                <div class="withdraw-hero-title">USDT TRC20 Address</div>
                <div class="withdraw-hero-sub">
                    Save your personal withdrawal address once. After saving, the address becomes locked and cannot be changed unless admin resets it.
                </div>
            </div>

            <div class="withdraw-coin" aria-label="USDT">
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="30" fill="#26A17B"/>
                    <path d="M36.45 28.2V24.3H46V18.35H18V24.3H27.55V28.17C19.79 28.53 14 30.06 14 31.9C14 33.74 19.79 35.27 27.55 35.63V48H36.45V35.6C44.16 35.25 49.9 33.72 49.9 31.9C49.9 30.08 44.16 28.56 36.45 28.2ZM32 34.31C22.62 34.31 15.02 33.22 15.02 31.88C15.02 30.67 21.2 29.67 29.46 29.49V33.45C30.28 33.49 31.12 33.51 32 33.51C32.88 33.51 33.72 33.49 34.54 33.45V29.5C42.74 29.68 48.88 30.68 48.88 31.88C48.88 33.22 41.31 34.31 32 34.31Z" fill="white"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="withdraw-card">
        <div class="withdraw-card-head">
            <div>
                <div class="withdraw-card-title">Secure Address Setup</div>
                <div class="withdraw-card-sub">
                    Only valid TRC20 addresses are allowed. Duplicate withdrawal addresses between different user accounts are blocked by the backend.
                </div>
            </div>

            <div class="address-chip {{ $hasSavedAddress ? 'locked' : 'setup' }}">
                {{ $hasSavedAddress ? 'Locked' : 'Setup Required' }}
            </div>
        </div>

        @if($hasSavedAddress)
            <div class="saved-box">
                <div class="saved-box-title">Saved Withdraw Address</div>
                <div class="saved-address">{{ $user->withdraw_wallet_address }}</div>
                <div class="saved-note">
                    Network: <strong>{{ $user->withdraw_wallet_network ?? 'TRC20' }}</strong><br>
                    This address is locked. If you need to change it, please contact support or ask admin to reset it.
                </div>
            </div>
        @else
            <form method="POST" action="/withdraw/address" id="withdrawAddressForm" novalidate>
                @csrf

                <div class="input-group">
                    <label class="input-label">
                        <span>USDT TRC20 Address</span>
                        <span class="input-note">Must start with T</span>
                    </label>

                    <input
                        type="text"
                        name="withdraw_wallet_address"
                        id="withdrawWalletAddressInput"
                        class="input"
                        placeholder="Enter your valid USDT TRC20 address"
                        autocomplete="off"
                        spellcheck="false"
                        required
                    >

                    <div id="addressValidation" class="validation-box">
                        Validation status
                    </div>
                </div>

                <div class="notice-box">
                    <div class="notice-title">Important Notice</div>
                    <ul>
                        <li>Only <strong>USDT on TRC20</strong> is accepted here.</li>
                        <li>Once saved, your address becomes <strong>locked</strong>.</li>
                        <li>You cannot change it yourself later.</li>
                        <li>If the address is wrong or duplicated, the backend will reject it.</li>
                        <li>After saving the address successfully, you will continue to the withdrawal flow.</li>
                    </ul>
                </div>

                <button type="submit" class="submit-btn" id="saveAddressBtn">
                    Save Address
                </button>
            </form>
        @endif

        <div class="action-links">
            @if($hasSavedAddress)
                <a href="/withdraw" class="action-link">Continue to Withdraw</a>
            @else
                <a href="/profile" class="action-link">Back to Profile</a>
            @endif
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addressInput = document.getElementById('withdrawWalletAddressInput');
    const validationBox = document.getElementById('addressValidation');
    const form = document.getElementById('withdrawAddressForm');
    const tronRegex = /^T[1-9A-HJ-NP-Za-km-z]{33}$/;

    function validateAddress() {
        if (!addressInput || !validationBox) return true;

        const value = addressInput.value.trim();

        if (value === '') {
            validationBox.className = 'validation-box';
            validationBox.textContent = '';
            return false;
        }

        validationBox.classList.add('show');

        if (tronRegex.test(value)) {
            validationBox.classList.remove('bad');
            validationBox.classList.add('ok');
            validationBox.textContent = 'Valid TRC20 address format';
            return true;
        } else {
            validationBox.classList.remove('ok');
            validationBox.classList.add('bad');
            validationBox.textContent = 'Invalid TRC20 address format';
            return false;
        }
    }

    if (addressInput) {
        addressInput.addEventListener('input', validateAddress);
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            const isValid = validateAddress();

            if (!isValid) {
                e.preventDefault();
                alert('Please enter a valid USDT TRC20 address.');
            }
        });
    }
});
</script>

@endsection