@extends('layouts.app')

@section('content')

@php
    $usdBalance = (float) ($wallet->balance ?? 0);
    $btcBalance = (float) ($wallet->btc_balance ?? 0);

    $pairPrice = (float) ($selectedPrice ?? 0);
    $pairName = $selectedName ?? ($symbol ?? 'BTC / USDT');
    $pairSymbol = strtoupper($symbol ?? 'BTCUSDT');
    $pairChange = (float) ($selectedChange ?? 0);

    $btcReferencePrice = 65000;
    if (isset($marketTable['BTCUSDT']['price'])) {
        $btcReferencePrice = (float) $marketTable['BTCUSDT']['price'];
    }

    $btcValueInUsd = $btcBalance * $btcReferencePrice;
    $totalEstimated = $usdBalance + $btcValueInUsd;

    $orders = \App\Models\Order::where('user_name', auth()->user()->name)
        ->latest()
        ->take(10)
        ->get();

    $tvSymbol = 'BINANCE:' . $pairSymbol;
@endphp

<style>
.trade-page{
    display:flex;
    flex-direction:column;
    gap:16px;
    max-width:480px;
    margin:0 auto;
    padding-bottom:122px;
}

.trade-card{
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    border-radius:28px;
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
    overflow:hidden;
}

.trade-hero{
    position:relative;
    overflow:hidden;
    padding:18px;
    min-height:170px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.55) 0%, rgba(5,10,7,0.78) 100%),
        radial-gradient(circle at 78% 25%, rgba(184,255,59,0.18) 0%, rgba(184,255,59,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.10);
    border-radius:30px;
}

.trade-hero::before{
    content:"";
    position:absolute;
    right:-45px;
    top:-35px;
    width:220px;
    height:220px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.10) 0%, rgba(184,255,59,0) 70%);
}

.trade-hero::after{
    content:"";
    position:absolute;
    left:-50px;
    bottom:-70px;
    width:220px;
    height:220px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(98,255,154,0.08) 0%, rgba(98,255,154,0) 70%);
}

.trade-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    flex-direction:column;
    gap:14px;
}

.trade-hero-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
}

.trade-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:7px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    color:#07120b;
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    box-shadow:0 8px 20px rgba(184,255,59,0.18);
}

.trade-symbol-box{
    width:54px;
    height:54px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:900;
    color:#07120b;
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    box-shadow:0 12px 28px rgba(184,255,59,0.24);
    flex:0 0 54px;
}

.trade-title{
    font-size:18px;
    font-weight:900;
    color:#f4fff8;
    line-height:1.3;
    margin-top:4px;
}

.trade-sub{
    color:#b9c8bf;
    font-size:13px;
    line-height:1.8;
}

.trade-stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
}

.trade-stat{
    border-radius:20px;
    padding:14px 12px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
    text-align:center;
}

.trade-stat-label{
    color:#9aac9f;
    font-size:10px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:6px;
}

.trade-stat-value{
    color:#f4fff8;
    font-size:16px;
    font-weight:900;
    line-height:1.2;
}

.trade-stat-value.up{
    color:#62ff9a;
}

.trade-stat-value.down{
    color:#ff7f92;
}

.card-pad{
    padding:18px;
}

.section-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:14px;
}

.section-title{
    font-size:16px;
    font-weight:900;
    color:#f4fff8;
}

.section-chip{
    font-size:11px;
    font-weight:900;
    color:#b8ff3b;
}

.timeframe-row{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    margin-bottom:12px;
}

.tf-btn{
    border:none;
    cursor:pointer;
    height:34px;
    padding:0 14px;
    border-radius:14px;
    background:rgba(255,255,255,0.05);
    border:1px solid rgba(184,255,59,0.08);
    color:#f4fff8;
    font-size:12px;
    font-weight:800;
    transition:.2s ease;
}

.tf-btn:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.20);
}

.tf-btn.active{
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    color:#07120b;
    border-color:rgba(184,255,59,0.22);
    box-shadow:0 0 10px rgba(184,255,59,0.30);
}

.chart-wrap{
    border-radius:22px;
    overflow:hidden;
    border:1px solid rgba(184,255,59,0.08);
    background:#0a110d;
}

#tvChart{
    width:100%;
    height:380px;
}

.chart-mini-stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:10px;
    margin-top:12px;
}

.chart-mini-box{
    border-radius:16px;
    padding:10px 8px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
    text-align:center;
}

.chart-mini-label{
    color:#9aac9f;
    font-size:10px;
    font-weight:900;
    margin-bottom:5px;
}

.chart-mini-value{
    color:#f4fff8;
    font-size:14px;
    font-weight:900;
}

.chart-mini-value.up{
    color:#62ff9a;
}

.chart-mini-value.down{
    color:#ff7f92;
}

.stack-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:16px;
}

.depth-table,
.trades-table{
    width:100%;
    border-collapse:collapse;
}

.depth-table th,
.depth-table td,
.trades-table th,
.trades-table td{
    padding:9px 7px;
    font-size:12px;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,0.05);
}

.depth-table th,
.trades-table th{
    color:#d8e3da;
    font-size:11px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.8px;
}

.depth-table td,
.trades-table td{
    font-weight:800;
}

.depth-buy{
    color:#62ff9a !important;
}

.depth-sell{
    color:#ff7f92 !important;
}

.panel-inner{
    border-radius:22px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
    overflow:hidden;
}

.controls-shell{
    border-radius:24px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(184,255,59,0.08);
    padding:14px;
}

.controls-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:14px;
}

.controls-pair{
    color:#b8ff3b;
    font-size:13px;
    font-weight:900;
}

.controls-title{
    color:#f4fff8;
    font-size:16px;
    font-weight:900;
}

.controls-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.control-box{
    border-radius:20px;
    padding:14px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(184,255,59,0.08);
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:7px;
    margin-bottom:12px;
}

.form-label{
    color:#dce5ee;
    font-size:12px;
    font-weight:800;
}

.trade-input,
.trade-select{
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

.percent-row{
    display:flex;
    gap:8px;
    margin-bottom:12px;
}

.percent-btn{
    flex:1;
    height:34px;
    border:none;
    cursor:pointer;
    border-radius:12px;
    background:rgba(255,255,255,0.05);
    border:1px solid rgba(184,255,59,0.08);
    color:#f4fff8;
    font-size:11px;
    font-weight:900;
    transition:.2s ease;
}

.percent-btn:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.20);
}

.percent-btn.active{
    background:rgba(184,255,59,0.18);
    color:#07120b;
    border-color:rgba(184,255,59,0.25);
    box-shadow:0 0 10px rgba(184,255,59,0.25);
}

.estimate-box{
    border-radius:14px;
    padding:12px 14px;
    background:#121c16;
    border:1px solid rgba(184,255,59,0.06);
    color:#d9e6db;
    font-size:12px;
    font-weight:800;
    margin-bottom:12px;
    display:flex;
    justify-content:space-between;
    gap:12px;
}

.action-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.trade-btn{
    border:none;
    cursor:pointer;
    border-radius:16px;
    padding:14px;
    font-size:14px;
    font-weight:900;
    transition:.2s ease;
    width:100%;
}

.trade-btn:hover{
    transform:translateY(-1px);
}

.trade-btn.sell{
    background:linear-gradient(180deg,#ff7a8a 0%, #e8485d 100%);
    color:#ffffff;
    box-shadow:0 8px 18px rgba(255,95,116,0.25);
}

.trade-btn.buy{
    background:linear-gradient(180deg,#7effb5 0%, #3dd598 100%);
    color:#07120b;
    box-shadow:0 8px 18px rgba(61,213,152,0.25);
}

.trade-btn.limit{
    background:radial-gradient(circle,#d9ff79 0%,#b8ff3b 100%);
    color:#07120b;
    box-shadow:0 8px 18px rgba(184,255,59,0.25);
}

.helper-text{
    color:#8da095;
    font-size:11px;
    line-height:1.7;
    margin-top:10px;
    text-align:center;
}

.orders-card{
    padding:18px;
}

.orders-head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    margin-bottom:14px;
}

.orders-head-left{
    display:flex;
    flex-direction:column;
    gap:5px;
}

.orders-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.order-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:14px;
    border-radius:20px;
    background:linear-gradient(180deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.025) 100%);
    border:1px solid rgba(184,255,59,0.08);
}

.order-left{
    min-width:0;
}

.order-pair{
    color:#f4f7fb;
    font-size:14px;
    font-weight:900;
    margin-bottom:5px;
}

.order-meta{
    color:#aab7af;
    font-size:11px;
    line-height:1.75;
}

.order-right{
    text-align:right;
    flex:0 0 auto;
}

.order-type{
    font-size:11px;
    font-weight:900;
    margin-bottom:7px;
    text-transform:uppercase;
    letter-spacing:.7px;
}

.order-type.buy{
    color:#62ff9a;
}

.order-type.sell{
    color:#ff7f92;
}

.order-status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 10px;
    border-radius:999px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,255,255,0.06);
    color:#dfe7ef;
    font-size:10px;
    font-weight:900;
}

.cancel-form{
    margin-top:9px;
}

.cancel-btn{
    border:none;
    cursor:pointer;
    border-radius:12px;
    padding:10px 12px;
    background:rgba(255,95,116,0.12);
    border:1px solid rgba(255,95,116,0.18);
    color:#ffd0d7;
    font-size:11px;
    font-weight:900;
    transition:.2s ease;
}

.cancel-btn:hover{
    transform:translateY(-1px);
    background:rgba(255,95,116,0.18);
}

.empty-box{
    padding:16px;
    border-radius:18px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(255,255,255,0.06);
    color:#99a3b1;
    font-size:13px;
    line-height:1.8;
}

.alert{
    border-radius:18px;
    padding:14px 16px;
    font-size:13px;
    font-weight:800;
    line-height:1.7;
}

.alert.success{
    background:rgba(98,255,154,0.12);
    color:#c7ffdc;
    border:1px solid rgba(98,255,154,0.16);
}

.alert.error{
    background:rgba(255,91,112,0.10);
    color:#ffc0ca;
    border:1px solid rgba(255,91,112,0.14);
}

@media (max-width:700px){
    .controls-grid{
        grid-template-columns:1fr;
    }

    .chart-mini-stats{
        grid-template-columns:repeat(2,1fr);
    }

    #tvChart{
        height:340px;
    }
}

@media (max-width:520px){
    .trade-page{
        gap:14px;
    }

    .trade-title{
        font-size:17px;
    }

    .trade-sub{
        font-size:12px;
    }

    .trade-stat-value{
        font-size:15px;
    }

    .trade-stats{
        grid-template-columns:1fr 1fr 1fr;
    }

    #tvChart{
        height:300px;
    }

    .order-row{
        flex-direction:column;
        align-items:flex-start;
    }

    .order-right{
        text-align:left;
    }
}
</style>

<div class="trade-page">

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="trade-hero">
        <div class="trade-hero-inner">
            <div class="trade-hero-top">
                <div>
                    <div class="trade-badge">LIVE MARKET</div>
                    <div class="trade-title">{{ $pairName }}</div>
                    <div class="trade-sub">
                        واجهة تداول DawnEX بلمسة أسرع وأنظف، مع شارت مباشر ودفتر أوامر وتحكم واضح بنفس هوية المنصة.
                    </div>
                </div>

                <div class="trade-symbol-box">
                    {{ substr($pairSymbol, 0, 1) }}
                </div>
            </div>

            <div class="trade-stats">
                <div class="trade-stat">
                    <div class="trade-stat-label">Main Balance</div>
                    <div class="trade-stat-value">${{ number_format($usdBalance, 2) }}</div>
                </div>

                <div class="trade-stat">
                    <div class="trade-stat-label">24H Change</div>
                    <div class="trade-stat-value {{ $pairChange >= 0 ? 'up' : 'down' }}">
                        {{ $pairChange >= 0 ? '+' : '' }}{{ number_format($pairChange, 2) }}%
                    </div>
                </div>

                <div class="trade-stat">
                    <div class="trade-stat-label">Last Price</div>
                    <div class="trade-stat-value">
                        ${{ $pairPrice >= 1 ? number_format($pairPrice, 2) : number_format($pairPrice, 5) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="trade-card card-pad">
        <div class="section-head">
            <div class="timeframe-row">
                <button class="tf-btn" data-interval="60">1h</button>
                <button class="tf-btn" data-interval="15">15m</button>
                <button class="tf-btn" data-interval="5">5m</button>
                <button class="tf-btn active" data-interval="1">1m</button>
            </div>
            <div class="section-title">Live Chart</div>
        </div>

        <div class="chart-wrap">
            <div id="tvChart"></div>
        </div>

        <div class="chart-mini-stats">
            <div class="chart-mini-box">
                <div class="chart-mini-label">Close</div>
                <div class="chart-mini-value" id="klineClose">--</div>
            </div>
            <div class="chart-mini-box">
                <div class="chart-mini-label">Low</div>
                <div class="chart-mini-value" id="klineLow">--</div>
            </div>
            <div class="chart-mini-box">
                <div class="chart-mini-label">High</div>
                <div class="chart-mini-value" id="klineHigh">--</div>
            </div>
            <div class="chart-mini-box">
                <div class="chart-mini-label">Open</div>
                <div class="chart-mini-value" id="klineOpen">--</div>
            </div>
        </div>
    </div>

    <div class="stack-grid">

        <div class="trade-card card-pad">
            <div class="section-head">
                <div class="section-chip">Live Depth</div>
                <div class="section-title">Order Book</div>
            </div>

            <div class="panel-inner">
                <table class="depth-table">
                    <thead>
                        <tr>
                            <th>Qty</th>
                            <th class="depth-buy">Buy Orders</th>
                            <th class="depth-sell">Sell Orders</th>
                        </tr>
                    </thead>
                    <tbody id="orderBookRows">
                        <tr><td colspan="3">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="trade-card card-pad">
            <div class="section-head">
                <div class="section-chip">Live Tape</div>
                <div class="section-title">Recent Trades</div>
            </div>

            <div class="panel-inner">
                <table class="trades-table">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody id="recentTradesRows">
                        <tr><td colspan="2">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="trade-card card-pad">
        <div class="controls-shell">
            <div class="controls-head">
                <div class="controls-pair">{{ $pairName }}</div>
                <div class="controls-title">Trade Controls</div>
            </div>

            <div class="controls-grid">

                <div class="control-box">
                    <form method="POST" action="/trade/buy" id="marketBuyForm">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Execution Type</label>
                            <select class="trade-select" disabled>
                                <option>Market Order</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">BTC Amount</label>
                            <input
                                type="number"
                                step="0.00000001"
                                min="0.00000001"
                                name="btc_amount"
                                id="marketAmount"
                                class="trade-input"
                                placeholder="0.00000000"
                                required
                            >
                        </div>

                        <div class="percent-row">
                            <button type="button" class="percent-btn" data-percent="100">MAX</button>
                            <button type="button" class="percent-btn" data-percent="75">75%</button>
                            <button type="button" class="percent-btn" data-percent="50">50%</button>
                            <button type="button" class="percent-btn" data-percent="25">25%</button>
                        </div>

                        <div class="estimate-box">
                            <span>Estimated Value:</span>
                            <span id="marketEstimate">$0.00</span>
                        </div>

                        <div class="action-row">
                            <button type="submit" formaction="/trade/sell" class="trade-btn sell">Sell</button>
                            <button type="submit" formaction="/trade/buy" class="trade-btn buy">Buy</button>
                        </div>

                        <div class="helper-text">
                            اضغط على النسبة ليتم تعبئة الكمية تلقائيًا حسب رصيدك.
                        </div>
                    </form>
                </div>

                <div class="control-box">
                    <form method="POST" action="/trade/limit-order">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Order Type</label>
                            <select name="type" class="trade-select" required>
                                <option value="BUY">BUY</option>
                                <option value="SELL">SELL</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">BTC Amount</label>
                            <input
                                type="number"
                                step="0.00000001"
                                min="0.00000001"
                                name="btc_amount"
                                class="trade-input"
                                placeholder="0.00000000"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Target Price (USDT)</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="price"
                                class="trade-input"
                                value="{{ $pairPrice > 0 ? number_format($pairPrice, 2, '.', '') : '' }}"
                                placeholder="0.00"
                                required
                            >
                        </div>

                        <button type="submit" class="trade-btn limit">Create Limit Order</button>

                        <div class="helper-text">
                            سينزل طلبك إلى قائمة الأوامر المفتوحة ويمكنك إلغاؤه من الأسفل.
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="trade-card orders-card">
        <div class="orders-head">
            <div class="orders-head-left">
                <div class="section-title">Open & Recent Orders</div>
                <div class="section-chip">Your latest activity</div>
            </div>
        </div>

        @if(isset($orders) && count($orders) > 0)
            <div class="orders-list">
                @foreach($orders as $order)
                    <div class="order-row">
                        <div class="order-left">
                            <div class="order-pair">{{ $order->pair }}</div>
                            <div class="order-meta">
                                Amount: {{ number_format((float) $order->btc_amount, 8) }} BTC<br>
                                Price: ${{ number_format((float) $order->price, 2) }}<br>
                                {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>

                        <div class="order-right">
                            <div class="order-type {{ strtolower($order->type) }}">{{ $order->type }}</div>
                            <div class="order-status">{{ $order->status }}</div>

                            @if($order->status === 'Open')
                                <form method="POST" action="/trade/order/{{ $order->id }}/cancel" class="cancel-form">
                                    @csrf
                                    <button type="submit" class="cancel-btn">Cancel Order</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box">
                لا يوجد لديك أي أمر مفتوح بعد. عندما تنشئ Limit Order أو أي طلب جديد سيظهر هنا بشكل واضح ويمكنك إلغاؤه بسهولة.
            </div>
        @endif
    </div>

</div>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
const pairSymbol = @json($pairSymbol);
const tvSymbol = @json($tvSymbol);
const currentPairPrice = {{ $pairPrice > 0 ? $pairPrice : 0 }};
const usdBalance = {{ $usdBalance }};
let currentInterval = "1";
let tvWidget = null;

function loadTradingView(interval = "1") {
    if (tvWidget && typeof tvWidget.setSymbol === "function") {
        tvWidget.setSymbol(tvSymbol, interval);
        return;
    }

    tvWidget = new TradingView.widget({
        autosize: true,
        symbol: tvSymbol,
        interval: interval,
        timezone: "Etc/UTC",
        theme: "dark",
        style: "1",
        locale: "en",
        toolbar_bg: "#0b1410",
        enable_publishing: false,
        allow_symbol_change: false,
        hide_top_toolbar: true,
        hide_legend: false,
        save_image: false,
        container_id: "tvChart",
        overrides: {
            "paneProperties.background": "#0a110d",
            "paneProperties.vertGridProperties.color": "rgba(184,255,59,0.06)",
            "paneProperties.horzGridProperties.color": "rgba(184,255,59,0.06)",
            "paneProperties.crossHairProperties.color": "rgba(184,255,59,0.35)",
            "scalesProperties.textColor": "#dce5ee",
            "mainSeriesProperties.candleStyle.upColor": "#62ff9a",
            "mainSeriesProperties.candleStyle.downColor": "#ff7f92",
            "mainSeriesProperties.candleStyle.borderUpColor": "#62ff9a",
            "mainSeriesProperties.candleStyle.borderDownColor": "#ff7f92",
            "mainSeriesProperties.candleStyle.wickUpColor": "#62ff9a",
            "mainSeriesProperties.candleStyle.wickDownColor": "#ff7f92",
            "mainSeriesProperties.priceLineColor": "#b8ff3b",
            "mainSeriesProperties.priceLineWidth": 2,
            "mainSeriesProperties.showPriceLine": true
        },
        studies_overrides: {
            "volume.volume.color.0": "#ff7f92",
            "volume.volume.color.1": "#62ff9a"
        }
    });
}

document.querySelectorAll(".tf-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        document.querySelectorAll(".tf-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        currentInterval = this.dataset.interval || "1";
        loadTradingView(currentInterval);
        loadKlineStats();
    });
});

async function loadOrderBook() {
    try {
        const response = await fetch(`https://api.binance.com/api/v3/depth?symbol=${pairSymbol}&limit=10`);
        const data = await response.json();

        const tbody = document.getElementById("orderBookRows");
        if (!tbody) return;

        const bids = data.bids || [];
        const asks = data.asks || [];

        let html = "";

        for (let i = 0; i < 10; i++) {
            const bid = bids[i] || ["-", "-"];
            const ask = asks[i] || ["-", "-"];

            html += `
                <tr>
                    <td>${parseFloat(bid[1] || 0).toFixed(4)}</td>
                    <td class="depth-buy">${bid[0] || "-"}</td>
                    <td class="depth-sell">${ask[0] || "-"}</td>
                </tr>
            `;
        }

        tbody.innerHTML = html;
    } catch (e) {
        const tbody = document.getElementById("orderBookRows");
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="3">Failed to load order book</td></tr>`;
        }
    }
}

async function loadRecentTrades() {
    try {
        const response = await fetch(`https://api.binance.com/api/v3/trades?symbol=${pairSymbol}&limit=10`);
        const data = await response.json();

        const tbody = document.getElementById("recentTradesRows");
        if (!tbody) return;

        let html = "";

        (data || []).forEach(trade => {
            const priceFormatted = parseFloat(trade.price) >= 1
                ? parseFloat(trade.price).toFixed(2)
                : parseFloat(trade.price).toFixed(5);

            html += `
                <tr>
                    <td>${parseFloat(trade.qty).toFixed(4)}</td>
                    <td class="${trade.isBuyerMaker ? 'depth-sell' : 'depth-buy'}">${priceFormatted}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    } catch (e) {
        const tbody = document.getElementById("recentTradesRows");
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="2">Failed to load recent trades</td></tr>`;
        }
    }
}

function getBinanceInterval() {
    switch (currentInterval) {
        case "60":
            return "1h";
        case "15":
            return "15m";
        case "5":
            return "5m";
        default:
            return "1m";
    }
}

async function loadKlineStats() {
    try {
        const interval = getBinanceInterval();
        const response = await fetch(`https://api.binance.com/api/v3/klines?symbol=${pairSymbol}&interval=${interval}&limit=1`);
        const data = await response.json();

        if (!Array.isArray(data) || !data.length) return;

        const k = data[0];
        const open = parseFloat(k[1] || 0);
        const high = parseFloat(k[2] || 0);
        const low = parseFloat(k[3] || 0);
        const close = parseFloat(k[4] || 0);

        const closeEl = document.getElementById("klineClose");
        const lowEl = document.getElementById("klineLow");
        const highEl = document.getElementById("klineHigh");
        const openEl = document.getElementById("klineOpen");

        const formatPrice = (value) => value >= 1 ? value.toFixed(2) : value.toFixed(5);

        if (closeEl) {
            closeEl.textContent = formatPrice(close);
            closeEl.className = "chart-mini-value " + (close >= open ? "up" : "down");
        }

        if (lowEl) {
            lowEl.textContent = formatPrice(low);
            lowEl.className = "chart-mini-value";
        }

        if (highEl) {
            highEl.textContent = formatPrice(high);
            highEl.className = "chart-mini-value";
        }

        if (openEl) {
            openEl.textContent = formatPrice(open);
            openEl.className = "chart-mini-value";
        }
    } catch (e) {}
}

const marketAmountInput = document.getElementById("marketAmount");
const marketEstimate = document.getElementById("marketEstimate");

function updateEstimate() {
    if (!marketAmountInput || !marketEstimate) return;

    const amount = parseFloat(marketAmountInput.value || 0);
    const total = amount * currentPairPrice;
    marketEstimate.textContent = '$' + total.toFixed(2);
}

if (marketAmountInput) {
    marketAmountInput.addEventListener("input", updateEstimate);
}

document.querySelectorAll(".percent-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        document.querySelectorAll(".percent-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");

        const percent = parseInt(this.dataset.percent || "0");
        if (!percent || currentPairPrice <= 0 || !marketAmountInput) return;

        const maxBtc = usdBalance / currentPairPrice;
        const amount = (maxBtc * percent) / 100;

        marketAmountInput.value = amount.toFixed(8);
        updateEstimate();
    });
});

loadTradingView("1");
loadOrderBook();
loadRecentTrades();
loadKlineStats();
updateEstimate();

setInterval(loadOrderBook, 3000);
setInterval(loadRecentTrades, 3000);
setInterval(loadKlineStats, 5000);
</script>

@endsection