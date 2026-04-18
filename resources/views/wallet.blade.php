@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();

    $usdBalance = (float) ($wallet->balance ?? 0);

    $totalDeposits = \App\Models\Deposit::where('user_name', $user->name)
        ->where('status', 'Approved')
        ->sum('amount');

    $totalWithdrawals = \App\Models\Withdrawal::where('user_name', $user->name)
        ->where('status', 'Approved')
        ->sum('amount');

    $walletId = 'DX-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);

    $publicAccountId = $user->account_id ?? $user->id;

    $withdrawAddress = $user->withdraw_wallet_address ?? 'Not Set';

    if ($withdrawAddress !== 'Not Set' && strlen($withdrawAddress) > 12) {
        $maskedWithdrawAddress = substr($withdrawAddress, 0, 6) . '******' . substr($withdrawAddress, -6);
    } else {
        $maskedWithdrawAddress = $withdrawAddress;
    }

    $assetPrices = [
        'BTC' => 65000,
        'ETH' => 3200,
        'TRX' => 0.12,
        'DOGE' => 0.16,
        'BCH' => 520,
    ];

    $userAssets = \App\Models\UserAsset::where('user_id', $user->id)->get()->keyBy('asset_symbol');

    $assetList = [
        [
            'symbol' => 'BTC',
            'name' => 'Bitcoin',
            'balance' => (float) ($userAssets['BTC']->balance ?? 0),
            'price' => $assetPrices['BTC'],
        ],
        [
            'symbol' => 'ETH',
            'name' => 'Ethereum',
            'balance' => (float) ($userAssets['ETH']->balance ?? 0),
            'price' => $assetPrices['ETH'],
        ],
        [
            'symbol' => 'TRX',
            'name' => 'TRON',
            'balance' => (float) ($userAssets['TRX']->balance ?? 0),
            'price' => $assetPrices['TRX'],
        ],
        [
            'symbol' => 'DOGE',
            'name' => 'Dogecoin',
            'balance' => (float) ($userAssets['DOGE']->balance ?? 0),
            'price' => $assetPrices['DOGE'],
        ],
        [
            'symbol' => 'BCH',
            'name' => 'Bitcoin Cash',
            'balance' => (float) ($userAssets['BCH']->balance ?? 0),
            'price' => $assetPrices['BCH'],
        ],
    ];

    $convertedAssetsTotal = 0;
    foreach ($assetList as $assetItem) {
        $convertedAssetsTotal += $assetItem['balance'] * $assetItem['price'];
    }

    $netEstimatedValue = $usdBalance + $convertedAssetsTotal;

    $recentConversions = \App\Models\Trade::where('user_name', $user->name)
        ->where('type', 'BUY')
        ->latest()
        ->take(5)
        ->get();
@endphp

<style>
.wallet-page{
    max-width:480px;
    margin:0 auto;
    padding:18px 0 130px;
    display:flex;
    flex-direction:column;
    gap:16px;
}

.dx-panel{
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    border-radius:28px;
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
    overflow:hidden;
}

.account-strip{
    padding:16px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
}

.account-left{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
}

.avatar{
    width:54px;
    height:54px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(180deg,#18241c 0%, #101913 100%);
    border:1px solid rgba(184,255,59,0.18);
    box-shadow:0 10px 24px rgba(184,255,59,0.10);
    flex:0 0 54px;
}

.account-meta{
    min-width:0;
}

.account-label{
    color:#8da095;
    font-size:11px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:4px;
}

.account-name{
    color:#f4fff8;
    font-size:16px;
    font-weight:900;
    line-height:1.35;
    word-break:break-word;
}

.account-id{
    color:#9fb4a4;
    font-size:12px;
    margin-top:3px;
}

.vip-badge{
    padding:10px 14px;
    border-radius:16px;
    background:rgba(184,255,59,0.10);
    border:1px solid rgba(184,255,59,0.14);
    color:#dfffab;
    font-size:12px;
    font-weight:900;
    text-align:center;
    flex:0 0 auto;
}

.wallet-card{
    position:relative;
    overflow:hidden;
    padding:22px 18px 18px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.55) 0%, rgba(5,10,7,0.78) 100%),
        radial-gradient(circle at 80% 25%, rgba(184,255,59,0.18) 0%, rgba(184,255,59,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.10);
    border-radius:30px;
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.wallet-card::before{
    content:"";
    position:absolute;
    right:-50px;
    top:-30px;
    width:220px;
    height:220px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.10) 0%, rgba(184,255,59,0) 70%);
}

.wallet-card::after{
    content:"";
    position:absolute;
    left:-60px;
    bottom:-70px;
    width:220px;
    height:220px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(98,255,154,0.08) 0%, rgba(98,255,154,0) 70%);
}

.wallet-card-inner{
    position:relative;
    z-index:2;
}

.wallet-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
    margin-bottom:18px;
}

.wallet-top-left{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.wallet-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:fit-content;
    padding:7px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    color:#07120b;
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    box-shadow:0 8px 20px rgba(184,255,59,0.18);
}

.wallet-subtitle{
    color:#f4fff8;
    font-size:14px;
    font-weight:900;
    letter-spacing:1px;
    text-transform:uppercase;
}

.wallet-id-box{
    text-align:right;
}

.wallet-id-label{
    color:#8da095;
    font-size:11px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:5px;
}

.wallet-id-value{
    color:#eef8f1;
    font-size:14px;
    font-weight:900;
    line-height:1.35;
}

.balance-label{
    color:#8da095;
    font-size:12px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:8px;
}

.balance-value{
    color:#ffffff;
    font-size:36px;
    font-weight:900;
    line-height:1;
    letter-spacing:-0.4px;
    margin-bottom:10px;
}

.balance-sub{
    color:#b9c8bf;
    font-size:13px;
    line-height:1.8;
    margin-bottom:16px;
    max-width:350px;
}

.address-box{
    margin-bottom:16px;
    padding:12px 14px;
    border-radius:18px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
}

.address-label{
    color:#8da095;
    font-size:10px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:6px;
}

.address-value{
    color:#f4fff8;
    font-size:13px;
    font-weight:800;
    line-height:1.6;
    word-break:break-word;
}

.card-actions{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:10px;
}

.card-action{
    text-decoration:none;
    border-radius:20px;
    padding:13px 10px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:8px;
    transition:.2s ease;
}

.card-action:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.20);
    background:rgba(184,255,59,0.06);
}

.icon-wrap{
    width:40px;
    height:40px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    color:#07120b;
    box-shadow:0 8px 18px rgba(184,255,59,0.16);
}

.action-text{
    color:#f4fff8;
    font-size:12px;
    font-weight:900;
    text-align:center;
    line-height:1.3;
}

.stats-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.stat-card{
    padding:18px;
}

.stat-label{
    color:#8da095;
    font-size:11px;
    font-weight:900;
    letter-spacing:1px;
    text-transform:uppercase;
    margin-bottom:8px;
}

.stat-value{
    color:#ffffff;
    font-size:24px;
    font-weight:900;
    line-height:1.1;
}

.stat-value.green{
    color:#62ff9a;
}

.section-card{
    padding:18px;
}

.section-title{
    color:#f4fff8;
    font-size:19px;
    font-weight:900;
    margin-bottom:6px;
}

.section-sub{
    color:#8da095;
    font-size:12px;
    line-height:1.7;
    margin-bottom:14px;
}

.asset-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.asset-row{
    display:grid;
    grid-template-columns:auto 1fr auto;
    gap:12px;
    align-items:center;
    padding:14px;
    border-radius:20px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
    transition:.2s ease;
}

.asset-row:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.18);
}

.asset-symbol{
    width:48px;
    height:48px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(184,255,59,0.10);
    border:1px solid rgba(184,255,59,0.12);
    color:#dfffab;
    font-size:13px;
    font-weight:900;
    letter-spacing:.5px;
}

.asset-info{
    min-width:0;
}

.asset-name{
    color:#f4fff8;
    font-size:14px;
    font-weight:900;
    margin-bottom:4px;
}

.asset-balance{
    color:#9fb4a4;
    font-size:12px;
    line-height:1.6;
}

.asset-value{
    text-align:right;
}

.asset-usd{
    color:#ffffff;
    font-size:14px;
    font-weight:900;
    margin-bottom:4px;
}

.asset-price{
    color:#8da095;
    font-size:11px;
    line-height:1.5;
}

.empty-assets{
    padding:16px;
    border-radius:18px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
    color:#9fb4a4;
    font-size:13px;
    line-height:1.8;
}

.history-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.history-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:14px;
    border-radius:20px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
}

.history-left{
    min-width:0;
}

.history-pair{
    color:#f4fff8;
    font-size:14px;
    font-weight:900;
    margin-bottom:4px;
}

.history-meta{
    color:#8da095;
    font-size:11px;
    line-height:1.6;
}

.history-right{
    text-align:right;
}

.history-amount{
    color:#b8ff3b;
    font-size:13px;
    font-weight:900;
    margin-bottom:4px;
}

.history-type{
    color:#dfffc3;
    font-size:11px;
    font-weight:900;
}

.wallet-actions-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.wallet-btn{
    border:none;
    cursor:pointer;
    text-decoration:none;
    border-radius:20px;
    padding:14px 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
    color:#f4fff8;
    font-size:13px;
    font-weight:900;
    transition:.2s ease;
}

.wallet-btn:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.18);
    background:rgba(184,255,59,0.06);
}

.wallet-btn.primary{
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    color:#07120b;
    box-shadow:0 10px 20px rgba(184,255,59,0.18);
    border:none;
}

.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.62);
    backdrop-filter:blur(6px);
    -webkit-backdrop-filter:blur(6px);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:99999;
    padding:20px;
}

.modal-overlay.show{
    display:flex;
    animation:fadeIn .22s ease;
}

.convert-modal{
    width:100%;
    max-width:420px;
    border-radius:28px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 20px 44px rgba(0,0,0,0.35);
    overflow:hidden;
    animation:popIn .22s ease;
}

.modal-head{
    padding:18px 18px 12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
}

.modal-title{
    color:#f4fff8;
    font-size:22px;
    font-weight:900;
}

.modal-close{
    border:none;
    cursor:pointer;
    width:40px;
    height:40px;
    border-radius:14px;
    background:rgba(255,255,255,0.05);
    color:#f4fff8;
    font-size:18px;
    font-weight:900;
    border:1px solid rgba(184,255,59,0.08);
}

.modal-body{
    padding:0 18px 18px;
    display:flex;
    flex-direction:column;
    gap:12px;
}

.modal-sub{
    color:#8da095;
    font-size:12px;
    line-height:1.7;
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:7px;
}

.form-label{
    color:#dde6ef;
    font-size:12px;
    font-weight:800;
}

.form-input,
.form-select{
    width:100%;
    border:none;
    outline:none;
    border-radius:16px;
    padding:13px 14px;
    background:#0b1014;
    color:#f4f7fb;
    border:1px solid rgba(255,255,255,0.07);
    font-size:14px;
}

.preview-box{
    border-radius:16px;
    padding:13px 14px;
    background:rgba(184,255,59,0.10);
    border:1px solid rgba(184,255,59,0.12);
    color:#dfffc3;
    font-size:12px;
    line-height:1.7;
}

.modal-actions{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-top:4px;
}

.modal-btn{
    border:none;
    cursor:pointer;
    border-radius:16px;
    padding:14px 14px;
    font-size:13px;
    font-weight:900;
}

.modal-btn.cancel{
    background:#1a251c;
    color:#f4fff8;
    border:1px solid rgba(255,255,255,0.06);
}

.modal-btn.submit{
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    color:#07120b;
    box-shadow:0 10px 18px rgba(184,255,59,0.14);
}

.success-toast{
    position:fixed;
    left:50%;
    top:24px;
    transform:translateX(-50%) translateY(-20px);
    padding:15px 18px;
    border-radius:18px;
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    color:#07120b;
    font-size:14px;
    font-weight:900;
    box-shadow:0 16px 30px rgba(0,0,0,0.28);
    opacity:0;
    pointer-events:none;
    transition:.25s ease;
    z-index:100000;
}

.success-toast.show{
    opacity:1;
    transform:translateX(-50%) translateY(0);
}

@keyframes fadeIn{
    from{opacity:0;}
    to{opacity:1;}
}

@keyframes popIn{
    from{opacity:0; transform:scale(.96);}
    to{opacity:1; transform:scale(1);}
}

@media (max-width:560px){
    .balance-value{
        font-size:32px;
    }

    .card-actions,
    .wallet-actions-grid,
    .modal-actions{
        grid-template-columns:1fr;
    }

    .account-strip{
        align-items:flex-start;
    }

    .asset-row{
        grid-template-columns:auto 1fr;
    }

    .asset-value{
        grid-column:1 / -1;
        text-align:left;
        padding-top:4px;
    }

    .history-row{
        flex-direction:column;
        align-items:flex-start;
    }

    .history-right{
        text-align:left;
    }
}
</style>

<div class="wallet-page">

    @if(session('success'))
        <div id="successToast" class="success-toast">{{ session('success') }}</div>
    @else
        <div id="successToast" class="success-toast">Successfully converted</div>
    @endif

    <div class="dx-panel account-strip">
        <div class="account-left">
            <div class="avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="#b8ff3b"/>
                    <path d="M4 21C4 17.6863 7.13401 15 11 15H13C16.866 15 20 17.6863 20 21" stroke="#b8ff3b" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="account-meta">
                <div class="account-label">Account</div>
                <div class="account-name">{{ $user->name }}</div>
                <div class="account-id">#{{ $publicAccountId }}</div>
            </div>
        </div>

        <div class="vip-badge">VIP 1</div>
    </div>

    <div class="wallet-card">
        <div class="wallet-card-inner">
            <div class="wallet-top">
                <div class="wallet-top-left">
                    <div class="wallet-chip">DawnEX Wallet</div>
                    <div class="wallet-subtitle">Main Funding Card</div>
                </div>

                <div class="wallet-id-box">
                    <div class="wallet-id-label">Wallet ID</div>
                    <div class="wallet-id-value">{{ $walletId }}</div>
                </div>
            </div>

            <div class="balance-label">Wallet Balance</div>
            <div class="balance-value">${{ number_format($usdBalance, 2) }}</div>
            <div class="balance-sub">
                رصيدك الأساسي الجاهز للإيداع والسحب والتحويل الداخلي وعمليات التداول داخل المنصة.
            </div>

            <div class="address-box">
                <div class="address-label">USDT Withdraw Address</div>
                <div class="address-value">{{ $maskedWithdrawAddress }}</div>
            </div>

            <div class="card-actions">
                <a href="/deposit" class="card-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V19" stroke="#07120b" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M7 10L12 5L17 10" stroke="#07120b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="action-text">Deposit</div>
                </a>

                <a href="/withdraw" class="card-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V19" stroke="#07120b" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M17 14L12 19L7 14" stroke="#07120b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="action-text">Withdraw</div>
                </a>

                <a href="/history" class="card-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <rect x="6" y="4" width="12" height="16" rx="2" stroke="#07120b" stroke-width="2"/>
                            <path d="M9 9H15" stroke="#07120b" stroke-width="2" stroke-linecap="round"/>
                            <path d="M9 13H15" stroke="#07120b" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="action-text">History</div>
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="dx-panel stat-card">
            <div class="stat-label">Total Deposit</div>
            <div class="stat-value green">${{ number_format($totalDeposits, 2) }}</div>
        </div>

        <div class="dx-panel stat-card">
            <div class="stat-label">Total Withdraw</div>
            <div class="stat-value">${{ number_format($totalWithdrawals, 2) }}</div>
        </div>
    </div>

    <div class="dx-panel section-card">
        <div class="section-title">Converted Assets</div>
        <div class="section-sub">
            الأصول التي استلمتها بعد تحويل USDT داخل المنصة.
        </div>

        @if(collect($assetList)->sum('balance') > 0)
            <div class="asset-list">
                @foreach($assetList as $asset)
                    @if($asset['balance'] > 0)
                        <div class="asset-row">
                            <div class="asset-symbol">{{ $asset['symbol'] }}</div>

                            <div class="asset-info">
                                <div class="asset-name">{{ $asset['name'] }}</div>
                                <div class="asset-balance">
                                    {{ number_format($asset['balance'], 8) }} {{ $asset['symbol'] }}
                                </div>
                            </div>

                            <div class="asset-value">
                                <div class="asset-usd">${{ number_format($asset['balance'] * $asset['price'], 2) }}</div>
                                <div class="asset-price">1 {{ $asset['symbol'] }} = ${{ number_format($asset['price'], 2) }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="empty-assets">
                لا توجد أصول محوّلة بعد. عندما تحوّل USDT إلى BTC أو ETH أو TRX أو DOGE أو BCH ستظهر هنا.
            </div>
        @endif
    </div>

    <div class="dx-panel stat-card">
        <div class="stat-label">Net Estimated Value</div>
        <div class="stat-value">${{ number_format($netEstimatedValue, 2) }}</div>
    </div>

    <div class="dx-panel section-card">
        <div class="section-title">Recent Conversion Activity</div>
        <div class="section-sub">
            آخر عمليات التحويل والشراء داخل المنصة.
        </div>

        @if($recentConversions->count() > 0)
            <div class="history-list">
                @foreach($recentConversions as $conversion)
                    <div class="history-row">
                        <div class="history-left">
                            <div class="history-pair">{{ $conversion->pair }}</div>
                            <div class="history-meta">
                                {{ \Carbon\Carbon::parse($conversion->created_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>

                        <div class="history-right">
                            <div class="history-amount">{{ number_format((float) $conversion->amount, 8) }}</div>
                            <div class="history-type">{{ $conversion->type }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-assets">
                لا توجد أي حركة تحويل مسجلة حتى الآن.
            </div>
        @endif
    </div>

    <div class="dx-panel section-card">
        <div class="section-title">Wallet Actions</div>
        <div class="section-sub">
            إدارة الرصيد والتحويل الداخلي والانتقال إلى التداول من نفس هوية DawnEX.
        </div>

        <div class="wallet-actions-grid">
            <button type="button" class="wallet-btn primary" onclick="openConvertModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M7 7H17V17" stroke="#07120b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 7L7 17" stroke="#07120b" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
                Convert USDT
            </button>

            <a href="/trade" class="wallet-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M5 15L9 11L13 14L19 8" stroke="#f4fff8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 8H19V11" stroke="#f4fff8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Open Trade
            </a>
        </div>
    </div>

</div>

<div id="convertModalOverlay" class="modal-overlay">
    <div class="convert-modal">
        <div class="modal-head">
            <div class="modal-title">Convert USDT</div>
            <button type="button" class="modal-close" onclick="closeConvertModal()">×</button>
        </div>

        <form method="POST" action="/wallet/convert">
            @csrf

            <div class="modal-body">
                <div class="modal-sub">
                    حول رصيد USDT إلى الأصول المدعومة داخل المنصة فقط: BTC, ETH, TRX, DOGE, BCH.
                </div>

                <div class="form-group">
                    <label class="form-label">From Asset</label>
                    <select class="form-select" name="from_asset">
                        <option value="USDT">USDT</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">To Asset</label>
                    <select class="form-select" name="to_asset" id="targetAsset" onchange="updateConvertPreview()">
                        <option value="BTC">BTC</option>
                        <option value="ETH">ETH</option>
                        <option value="TRX">TRX</option>
                        <option value="DOGE">DOGE</option>
                        <option value="BCH">BCH</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">USDT Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="amount"
                        id="convertAmount"
                        class="form-input"
                        placeholder="Example: 100"
                        oninput="updateConvertPreview()"
                        required
                    >
                </div>

                <div class="preview-box" id="convertPreview">
                    Enter an amount to preview the estimated conversion.
                </div>

                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel" onclick="closeConvertModal()">Cancel</button>
                    <button type="submit" class="modal-btn submit">Convert</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openConvertModal() {
    document.getElementById('convertModalOverlay').classList.add('show');
}

function closeConvertModal() {
    document.getElementById('convertModalOverlay').classList.remove('show');
}

function updateConvertPreview() {
    const amount = parseFloat(document.getElementById('convertAmount').value || 0);
    const target = document.getElementById('targetAsset').value;
    const preview = document.getElementById('convertPreview');

    const prices = {
        BTC: 65000,
        ETH: 3200,
        TRX: 0.12,
        DOGE: 0.16,
        BCH: 520
    };

    if (!amount || amount <= 0) {
        preview.innerHTML = 'Enter an amount to preview the estimated conversion.';
        return;
    }

    const estimated = amount / prices[target];

    preview.innerHTML =
        'Estimated conversion: <strong>' + amount.toFixed(2) + ' USDT</strong> ≈ <strong>' +
        estimated.toFixed(8) + ' ' + target +
        '</strong><br>Reference price: 1 ' + target + ' = ' + prices[target].toLocaleString() + ' USDT';
}

window.addEventListener('load', function () {
    const toast = document.getElementById('successToast');
    @if(session('success'))
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    @endif
});

document.getElementById('convertModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConvertModal();
    }
});
</script>

@endsection