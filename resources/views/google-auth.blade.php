@extends('layouts.app')

@section('content')

<style>
.google-auth-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.google-auth-card{
    border-radius:28px;
    padding:22px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 18px 40px rgba(0,0,0,0.26);
}

.google-auth-title{
    font-size:24px;
    font-weight:900;
    color:#f4fff8;
    margin-bottom:8px;
}

.google-auth-sub{
    font-size:14px;
    line-height:1.8;
    color:#9fb4a4;
}

.google-auth-status{
    margin-top:16px;
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 14px;
    border-radius:16px;
    font-size:13px;
    font-weight:800;
}

.google-auth-status.enabled{
    background:rgba(127,255,212,0.10);
    color:#7fffd4;
    border:1px solid rgba(127,255,212,0.16);
}

.google-auth-status.disabled{
    background:rgba(255,215,106,0.10);
    color:#ffd76a;
    border:1px solid rgba(255,215,106,0.16);
}

.google-auth-qr-wrap{
    margin-top:18px;
    display:flex;
    justify-content:center;
}

.google-auth-qr{
    width:220px;
    max-width:100%;
    border-radius:22px;
    padding:14px;
    background:#fff;
    box-shadow:0 12px 24px rgba(0,0,0,0.20);
}

.google-auth-secret{
    margin-top:18px;
    padding:14px 16px;
    border-radius:18px;
    background:rgba(184,255,59,0.06);
    border:1px solid rgba(184,255,59,0.10);
    color:#dfffab;
    font-size:15px;
    font-weight:800;
    line-height:1.8;
    word-break:break-all;
}

.google-auth-form{
    margin-top:18px;
    display:flex;
    flex-direction:column;
    gap:12px;
}

.google-auth-label{
    font-size:14px;
    font-weight:800;
    color:#f4fff8;
}

.google-auth-input{
    width:100%;
    border:none;
    outline:none;
    border-radius:18px;
    padding:16px 18px;
    background:#111a15;
    border:1px solid rgba(184,255,59,0.10);
    color:#f4fff8;
    font-size:16px;
    font-weight:700;
    box-sizing:border-box;
}

.google-auth-btn{
    border:none;
    cursor:pointer;
    border-radius:20px;
    padding:16px 18px;
    font-size:16px;
    font-weight:900;
    transition:.2s ease;
}

.google-auth-btn.enable{
    background:linear-gradient(180deg,#dfffab 0%, #b8ff3b 100%);
    color:#0a120d;
}

.google-auth-btn.disable{
    background:linear-gradient(180deg,#ff9dac 0%, #ff5f74 100%);
    color:#fff;
}

.google-auth-btn:hover{
    transform:translateY(-1px);
}

.google-auth-alert{
    border-radius:18px;
    padding:14px 16px;
    font-size:14px;
    font-weight:700;
    line-height:1.7;
}

.google-auth-alert.success{
    background:rgba(127,255,212,0.10);
    color:#7fffd4;
    border:1px solid rgba(127,255,212,0.14);
}

.google-auth-alert.error{
    background:rgba(255,95,116,0.10);
    color:#ff9dac;
    border:1px solid rgba(255,95,116,0.16);
}
</style>

<div class="google-auth-page">

    <div class="google-auth-card">
        <div class="google-auth-title">Google Authenticator</div>
        <div class="google-auth-sub">
            Scan the QR code using Google Authenticator or any TOTP app, then enter the 6-digit code to enable two-factor authentication for your account.
        </div>

        <div class="google-auth-status {{ $user->google2fa_enabled ? 'enabled' : 'disabled' }}">
            {{ $user->google2fa_enabled ? '✓ Enabled' : '• Not Enabled' }}
        </div>

        @if (session('success'))
            <div class="google-auth-alert success" style="margin-top:16px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="google-auth-alert error" style="margin-top:16px;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="google-auth-alert error" style="margin-top:16px;">
                {{ $errors->first() }}
            </div>
        @endif

        @if (!$user->google2fa_enabled)
            <div class="google-auth-qr-wrap">
                <img src="{{ $qrCodeUrl }}" alt="QR Code" class="google-auth-qr">
            </div>

            <div class="google-auth-secret">
                Secret Key: {{ $secret }}
            </div>

            <form method="POST" action="/google-auth/enable" class="google-auth-form">
                @csrf
                <label class="google-auth-label">Enter 6-digit code from Google Authenticator</label>
                <input type="text" name="code" maxlength="6" class="google-auth-input" placeholder="123456" required>
                <button type="submit" class="google-auth-btn enable">Enable Google Authenticator</button>
            </form>
        @else
            <div class="google-auth-secret">
                Two-factor authentication is currently active on your account.
            </div>

            <form method="POST" action="/google-auth/disable" class="google-auth-form">
                @csrf
                <label class="google-auth-label">Enter current 6-digit code to disable</label>
                <input type="text" name="code" maxlength="6" class="google-auth-input" placeholder="123456" required>
                <button type="submit" class="google-auth-btn disable">Disable Google Authenticator</button>
            </form>
        @endif
    </div>

</div>

@endsection