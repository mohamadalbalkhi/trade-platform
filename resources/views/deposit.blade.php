@extends('layouts.app')

@section('content')

@php
    $depositAddress = $adminWallet->wallet_address ?? null;
    $depositActive = !empty($depositAddress);

    $registeredWallet = auth()->user()->withdraw_wallet_address ?? null;
    $hasRegisteredWallet = !empty($registeredWallet);

    $hasRequest = $latestDeposit && $latestDeposit->status === 'Pending';

    $qrCodeUrl = ($depositAddress && $hasRequest)
        ? 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=' . urlencode($depositAddress)
        : null;
@endphp

<style>
.deposit-page{
    display:flex;
    flex-direction:column;
    gap:18px;
    padding-bottom:130px;
}

.deposit-hero{
    position:relative;
    overflow:hidden;
    border-radius:30px;
    padding:22px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.58) 0%, rgba(5,10,7,0.82) 100%),
        radial-gradient(circle at 78% 24%, rgba(184,255,59,0.20) 0%, rgba(184,255,59,0) 30%),
        radial-gradient(circle at 18% 85%, rgba(98,255,154,0.07) 0%, rgba(98,255,154,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.12);
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.deposit-hero::before{
    content:"";
    position:absolute;
    right:-30px;
    top:-20px;
    width:180px;
    height:180px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.12) 0%, rgba(184,255,59,0) 72%);
}

.deposit-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:16px;
}

.deposit-hero-left{
    display:flex;
    flex-direction:column;
    gap:10px;
    min-width:0;
}

.deposit-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:fit-content;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    color:#07120b;
    background:#dfffab;
}

.deposit-title{
    font-size:30px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.25;
}

.deposit-sub{
    font-size:14px;
    color:#a8b8ae;
    line-height:1.8;
    max-width:400px;
}

.deposit-badge{
    width:70px;
    height:70px;
    border-radius:24px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    font-weight:900;
    color:#07120b;
    background:radial-gradient(circle,#d8ff72 0%,#b8ff3b 60%,#8dff1c 100%);
    box-shadow:0 12px 28px rgba(184,255,59,0.28);
    flex:0 0 70px;
}

.deposit-panel,
.request-panel,
.latest-panel,
.info-panel,
.wallet-panel{
    border-radius:28px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.deposit-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:18px;
}

.deposit-head-title{
    font-size:22px;
    font-weight:900;
    color:#f4fff8;
}

.deposit-status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.deposit-status.ok{
    color:#07120b;
    background:#7fffd4;
}

.deposit-status.off{
    color:#fff4c2;
    background:#6b5b20;
}

.deposit-status.wait{
    color:#fef3c7;
    background:rgba(245,158,11,0.18);
}

.deposit-stack{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.deposit-qr-box,
.deposit-address-box,
.steps-box,
.wallet-box{
    border-radius:24px;
    padding:18px;
    background:linear-gradient(180deg,#141f18 0%, #101913 100%);
    border:1px solid rgba(184,255,59,0.08);
}

.deposit-qr-box{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
}

.deposit-qr-title,
.steps-title,
.wallet-title{
    font-size:18px;
    font-weight:900;
    color:#f4fff8;
}

.deposit-qr-wrap{
    background:#ffffff;
    border-radius:22px;
    padding:14px;
    box-shadow:0 10px 20px rgba(0,0,0,0.18);
    margin-top:14px;
}

.deposit-qr-wrap img{
    width:220px;
    height:220px;
    display:block;
    max-width:100%;
}

.deposit-qr-sub{
    font-size:12px;
    color:#90a59a;
    line-height:1.8;
    margin-top:14px;
}

.deposit-label{
    font-size:12px;
    font-weight:800;
    color:#8da095;
    text-transform:uppercase;
    letter-spacing:.7px;
    margin-bottom:10px;
}

.deposit-address-wrap,
.wallet-address-wrap{
    border-radius:18px;
    padding:16px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
}

.deposit-address,
.wallet-address{
    font-size:16px;
    line-height:1.9;
    color:#f4fff8;
    word-break:break-word;
    overflow-wrap:anywhere;
    font-family:monospace;
    text-align:center;
}

.deposit-actions{
    display:flex;
    flex-direction:column;
    gap:12px;
    margin-top:16px;
}

.deposit-btn{
    border:none;
    border-radius:16px;
    padding:14px 18px;
    font-size:14px;
    font-weight:800;
    cursor:pointer;
    text-decoration:none;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.2s ease;
    width:100%;
}

.deposit-btn.copy,
.deposit-btn.primary{
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
}

.deposit-btn.secondary{
    background:#18231c;
    color:#f4fff8;
    border:1px solid rgba(184,255,59,0.08);
}

.deposit-btn.warning{
    background:linear-gradient(180deg,#f59e0b 0%, #d97706 100%);
    color:#fff;
    box-shadow:0 10px 24px rgba(245,158,11,0.18);
}

.deposit-btn:hover{
    transform:translateY(-1px);
}

.deposit-note{
    border-radius:22px;
    padding:16px;
    background:rgba(255,193,7,0.10);
    border:1px solid rgba(255,193,7,0.16);
    color:#ffe7a3;
    font-size:13px;
    line-height:1.9;
}

.deposit-empty{
    border-radius:24px;
    padding:22px;
    background:rgba(255,95,116,0.08);
    border:1px solid rgba(255,95,116,0.14);
    color:#ffb6c1;
    line-height:1.9;
}

.deposit-info-blue{
    border-radius:22px;
    padding:16px;
    background:rgba(59,130,246,0.10);
    border:1px solid rgba(59,130,246,0.18);
    color:#dbeafe;
    font-size:13px;
    line-height:1.9;
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:8px;
    margin-top:14px;
}

.form-label{
    font-size:13px;
    font-weight:800;
    color:#d7e6db;
}

.form-input{
    width:100%;
    border:none;
    outline:none;
    border-radius:16px;
    padding:14px 16px;
    background:#0d1510;
    color:#f4fff8;
    border:1px solid rgba(184,255,59,0.08);
    font-size:15px;
}

.form-input::placeholder{
    color:#82958a;
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

.request-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
    margin-top:14px;
}

.request-item{
    border-radius:18px;
    padding:14px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
}

.request-item-label{
    font-size:11px;
    color:#8da095;
    font-weight:800;
    text-transform:uppercase;
    margin-bottom:8px;
}

.request-item-value{
    font-size:15px;
    font-weight:900;
    color:#f4fff8;
    word-break:break-word;
}

.request-item-value.highlight{
    color:#dfffab;
}

.steps-list{
    display:flex;
    flex-direction:column;
    gap:12px;
    margin-top:14px;
}

.step-item{
    display:flex;
    gap:12px;
    align-items:flex-start;
    padding:14px;
    border-radius:18px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
}

.step-number{
    width:28px;
    height:28px;
    border-radius:50%;
    background:radial-gradient(circle,#dfffab 0%,#b8ff3b 100%);
    color:#07120b;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    font-weight:900;
    flex:0 0 28px;
}

.step-text{
    color:#dbe8df;
    font-size:14px;
    line-height:1.8;
}

.copy-toast{
    position:fixed;
    left:50%;
    bottom:120px;
    transform:translateX(-50%) translateY(20px);
    background:linear-gradient(180deg,#dfffab 0%, #b8ff3b 100%);
    color:#07120b;
    font-size:14px;
    font-weight:900;
    padding:14px 18px;
    border-radius:16px;
    box-shadow:0 14px 30px rgba(0,0,0,0.28);
    opacity:0;
    pointer-events:none;
    transition:.25s ease;
    z-index:99999;
    white-space:nowrap;
}

.copy-toast.show{
    opacity:1;
    transform:translateX(-50%) translateY(0);
}

@media (max-width:700px){
    .deposit-hero-inner{
        flex-direction:column;
    }

    .deposit-title{
        font-size:24px;
    }

    .request-grid{
        grid-template-columns:1fr;
    }

    .copy-toast{
        width:calc(100% - 32px);
        text-align:center;
        white-space:normal;
    }
}
</style>

<div class="deposit-page">

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="deposit-hero">
        <div class="deposit-hero-inner">
            <div class="deposit-hero-left">
                <div class="deposit-chip">USDT Deposit</div>
                <div class="deposit-title">Deposit USDT إلى المنصة عبر شبكة TRC20</div>
                <div class="deposit-sub">
                    إذا أردت إدخال أموال إلى المنصة، قم أولًا بإنشاء طلب إيداع. بعد ذلك فقط سيظهر لك عنوان التحويل، رمز QR، والمبلغ الدقيق المطلوب تحويله. يجب أن يتم الإيداع من نفس المحفظة المسجلة في حسابك.
                </div>
            </div>

            <div class="deposit-badge">₮</div>
        </div>
    </div>

    <div class="info-panel">
        <div class="deposit-head">
            <div class="deposit-head-title">How It Works</div>
            <div class="deposit-status.wait">Step by Step</div>
        </div>

        <div class="steps-box">
            <div class="steps-title">إيداع الأموال إلى المنصة</div>

            <div class="steps-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-text">
                        تأكد أولًا أن لديك <strong>عنوان محفظة سحب مسجل في الحساب</strong> لأن المنصة تعتمد نفس المحفظة المسجلة للتحقق من الإيداعات.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-text">
                        أدخل المبلغ الذي تريد إضافته إلى حسابك داخل المنصة، ثم اضغط على <strong>Create Deposit Request</strong>.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-text">
                        بعد إنشاء الطلب فقط، سيظهر لك <strong>Deposit ID</strong> و <strong>QR Code</strong> و <strong>العنوان</strong> و <strong>المبلغ الدقيق</strong> المطلوب تحويله.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-text">
                        قم بتحويل <strong>USDT فقط</strong> عبر شبكة <strong>TRC20 فقط</strong> ومن <strong>نفس المحفظة المسجلة في حسابك</strong>.
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-text">
                        بعد التحويل، ستتم مراجعة العملية، وسيظهر طلبك في حالة <strong>Pending</strong> ثم يتم اعتماده لاحقًا إذا تطابق المبلغ مع عنوان المحفظة المسجل لديك.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wallet-panel">
        <div class="deposit-head">
            <div class="deposit-head-title">Your Registered Wallet</div>

            @if($hasRegisteredWallet)
                <div class="deposit-status ok">Registered</div>
            @else
                <div class="deposit-status off">Not Registered</div>
            @endif
        </div>

        @if($hasRegisteredWallet)
            <div class="wallet-box">
                <div class="wallet-title">Registered TRC20 Wallet Address</div>

                <div class="wallet-address-wrap" style="margin-top:14px;">
                    <div class="wallet-address">{{ $registeredWallet }}</div>
                </div>

                <div class="deposit-info-blue" style="margin-top:16px;">
                    <strong>Important Notice:</strong><br>
                    يجب أن يتم تحويل الإيداع من <strong>نفس هذه المحفظة المسجلة</strong> داخل حسابك. إذا تم التحويل من محفظة أخرى، قد يتأخر التحقق أو قد تحتاج العملية إلى مراجعة إضافية.
                </div>
            </div>
        @else
            <div class="deposit-empty">
                لا يوجد لديك عنوان محفظة مسجل في الحساب حتى الآن.<br>
                يجب أولًا حفظ <strong>عنوان السحب TRC20</strong> الخاص بك قبل إنشاء طلب الإيداع.
            </div>

            <div class="deposit-actions" style="margin-top:16px;">
                <a href="/withdraw/address" class="deposit-btn warning">
                    Save Your Wallet Address First
                </a>
            </div>
        @endif
    </div>

    @if($hasRegisteredWallet)
        <div class="request-panel">
            <div class="deposit-head">
                <div class="deposit-head-title">Create Deposit Request</div>
                <div class="deposit-status ok">Professional Mode</div>
            </div>

            <form method="POST" action="/deposit/request">
                @csrf

                <div class="form-group">
                    <label class="form-label">Enter Deposit Amount (USDT)</label>
                    <input
                        type="number"
                        step="0.01"
                        min="10"
                        name="amount"
                        class="form-input"
                        placeholder="Example: 100"
                        required
                    >
                </div>

                <div class="deposit-actions">
                    <button type="submit" class="deposit-btn primary">
                        Create Deposit Request
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($hasRequest && $depositAddress && $depositActive && $hasRegisteredWallet)
        <div class="deposit-panel">
            <div class="deposit-head">
                <div class="deposit-head-title">Transfer Details</div>
                <div class="deposit-status ok">Ready to Send</div>
            </div>

            <div class="deposit-stack">

                <div class="latest-panel">
                    <div class="deposit-head" style="margin-bottom:0;">
                        <div class="deposit-head-title">Latest Deposit Request</div>

                        <div class="deposit-status wait">{{ $latestDeposit->status }}</div>
                    </div>

                    <div class="request-grid">
                        <div class="request-item">
                            <div class="request-item-label">Deposit ID</div>
                            <div class="request-item-value highlight">{{ $latestDeposit->deposit_id ?? '-' }}</div>
                        </div>

                        <div class="request-item">
                            <div class="request-item-label">Requested Amount</div>
                            <div class="request-item-value">${{ number_format((float) ($latestDeposit->requested_amount ?? 0), 2) }}</div>
                        </div>

                        <div class="request-item">
                            <div class="request-item-label">Exact Amount To Send</div>
                            <div class="request-item-value highlight">${{ number_format((float) $latestDeposit->amount, 2) }}</div>
                        </div>

                        <div class="request-item">
                            <div class="request-item-label">Status</div>
                            <div class="request-item-value">{{ $latestDeposit->status }}</div>
                        </div>
                    </div>
                </div>

                <div class="deposit-qr-box">
                    <div class="deposit-qr-title">QR Code</div>

                    <div class="deposit-qr-wrap">
                        <img src="{{ $qrCodeUrl }}" alt="Deposit QR Code">
                    </div>

                    <div class="deposit-qr-sub">
                        امسح الكود أو انسخ العنوان للتحويل
                    </div>
                </div>

                <div class="deposit-address-box">
                    <div class="deposit-label">Platform USDT TRC20 Address</div>

                    <div class="deposit-address-wrap">
                        <div class="deposit-address" id="depositAddress">{{ $depositAddress }}</div>
                    </div>

                    <div class="deposit-actions">
                        <button type="button" class="deposit-btn copy" onclick="copyDepositAddress()">
                            Copy Address
                        </button>

                        <a href="/home" class="deposit-btn secondary">
                            Back to Home
                        </a>
                    </div>
                </div>

                <div class="deposit-note">
                    <strong>Important:</strong><br>
                    - لا يظهر عنوان التحويل إلا بعد إنشاء طلب الإيداع<br>
                    - أرسل <strong>USDT فقط</strong><br>
                    - استخدم <strong>TRC20 فقط</strong><br>
                    - حوّل <strong>المبلغ الدقيق تمامًا</strong> الظاهر في طلبك<br>
                    - يجب أن يتم الإرسال من <strong>نفس المحفظة المسجلة في حسابك</strong><br>
                    - أي اختلاف في المبلغ أو في محفظة الإرسال قد يسبب تأخيرًا في المراجعة أو المطابقة
                </div>

            </div>
        </div>
    @elseif(!$depositActive)
        <div class="deposit-empty">
            لم يتم إعداد محفظة المنصة الرئيسية بعد.
            يرجى التواصل مع الإدارة.
        </div>
    @endif

</div>

<div id="copyToast" class="copy-toast">تم نسخ العنوان بنجاح</div>

<script>
function showCopyToast(message = 'تم نسخ العنوان بنجاح') {
    const toast = document.getElementById('copyToast');
    if (!toast) return;

    toast.textContent = message;
    toast.classList.add('show');

    clearTimeout(window.copyToastTimer);
    window.copyToastTimer = setTimeout(() => {
        toast.classList.remove('show');
    }, 2200);
}

function copyDepositAddress() {
    const address = document.getElementById('depositAddress');
    if (!address) return;

    navigator.clipboard.writeText(address.innerText.trim()).then(function () {
        showCopyToast('تم نسخ العنوان بنجاح');
    }).catch(function () {
        showCopyToast('فشل نسخ العنوان');
    });
}
</script>

@endsection