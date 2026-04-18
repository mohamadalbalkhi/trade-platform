@extends('layouts.app')

@section('content')

@php
    $base = str_replace('USDT', '', strtoupper($symbol));
    $pairName = $base . ' / USDT';
@endphp

<style>
.trade-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.trade-hero{
    position:relative;
    overflow:hidden;
    border-radius:30px;
    padding:22px;
    min-height:180px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.50) 0%, rgba(5,10,7,0.76) 100%),
        radial-gradient(circle at 72% 35%, rgba(184,255,59,0.18) 0%, rgba(184,255,59,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.trade-hero::before{
    content:"";
    position:absolute;
    right:-50px;
    top:-30px;
    width:220px;
    height:220px;
    border-radius:50%;
    background:radial-gradient(circle, rgba(184,255,59,0.10) 0%, rgba(184,255,59,0) 70%);
}

.trade-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    flex-direction:column;
    gap:16px;
}

.trade-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:14px;
}

.trade-logo{
    width:58px;
    height:58px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    font-weight:800;
    color:#07120b;
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    box-shadow:0 10px 28px rgba(184,255,59,0.28);
}

.trade-chip{
    padding:8px 12px;
    border-radius:14px;
    font-size:12px;
    font-weight:700;
    color:#dfffc3;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.12);
}

.trade-title{
    font-size:28px;
    line-height:1.25;
    font-weight:800;
    color:#f4fff8;
}

.trade-sub{
    font-size:14px;
    line-height:1.9;
    color:#b9c8bf;
    max-width:320px;
}

.trade-summary{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    gap:14px;
}

.summary-card{
    border-radius:24px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
    min-width:0;
}

.summary-label{
    font-size:12px;
    font-weight:700;
    letter-spacing:1px;
    text-transform:uppercase;
    color:#8da095;
    margin-bottom:8px;
}

.summary-value{
    font-size:24px;
    font-weight:800;
    color:#f4fff8;
    line-height:1.2;
    min-width:0;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
}

.chart-panel,
.controls-panel,
.orderbook-panel,
.recenttrades-panel{
    border-radius:28px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.panel-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:16px;
    flex-wrap:wrap;
}

.panel-title{
    font-size:23px;
    font-weight:800;
    color:#f4fff8;
}

.panel-link{
    font-size:14px;
    font-weight:700;
    color:#b8ff3b;
}

.timeframes{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.tf-btn{
    border:none;
    cursor:pointer;
    padding:10px 14px;
    border-radius:14px;
    background:#141f18;
    color:#c8d8cf;
    border:1px solid rgba(184,255,59,0.08);
    font-size:13px;
    font-weight:800;
    transition:.2s ease;
}

.tf-btn:hover{
    transform:translateY(-1px);
    border-color:rgba(184,255,59,0.18);
}

.tf-btn.active{
    background:rgba(184,255,59,0.14);
    color:#e8ffd0;
    border-color:rgba(184,255,59,0.18);
    box-shadow:0 0 0 1px rgba(184,255,59,0.08);
}

#chartContainer{
    width:100%;
    height:420px;
    border-radius:22px;
    overflow:hidden;
    border:1px solid rgba(184,255,59,0.06);
    background:#0a120d;
}

.meta-grid{
    margin-top:14px;
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:10px;
    width:100%;
}

.meta-card{
    border-radius:18px;
    padding:12px 10px;
    background:#141f18;
    border:1px solid rgba(184,255,59,0.06);
    min-width:0;
    overflow:hidden;
    text-align:center;
}

.meta-label{
    color:#8da095;
    font-size:11px;
    font-weight:700;
    margin-bottom:6px;
    white-space:nowrap;
}

.meta-value{
    color:#f4fff8;
    font-size:16px;
    font-weight:900;
    line-height:1.2;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.orderbook-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.orderbook-side{
    min-width:0;
}

.ob-title{
    font-weight:800;
    font-size:14px;
    margin-bottom:10px;
}

.ob-title.sell{
    color:#ff5f74;
}

.ob-title.buy{
    color:#62ff9a;
}

.order-row{
    display:flex;
    justify-content:space-between;
    gap:10px;
    padding:8px 0;
    font-size:13px;
    color:#c8d8cf;
    border-bottom:1px solid rgba(184,255,59,0.04);
}

.order-row:last-child{
    border-bottom:none;
}

.trades-header,
.trade-item{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    gap:10px;
    align-items:center;
}

.trades-header{
    padding-bottom:10px;
    margin-bottom:10px;
    border-bottom:1px solid rgba(184,255,59,0.06);
    color:#8da095;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.trade-item{
    padding:10px 0;
    border-bottom:1px solid rgba(184,255,59,0.04);
    font-size:13px;
    color:#d6e2db;
}

.trade-item:last-child{
    border-bottom:none;
}

.trade-price.buy{
    color:#62ff9a;
    font-weight:800;
}

.trade-price.sell{
    color:#ff5f74;
    font-weight:800;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.form-label{
    color:#d8e3dc;
    font-size:14px;
    font-weight:700;
}

.form-input{
    width:100%;
    height:54px;
    border-radius:18px;
    border:1px solid rgba(184,255,59,0.08);
    background:#0d1711;
    padding:0 16px;
    font-size:16px;
    color:#f4fff8;
    outline:none;
}

.quick-amount{
    display:flex;
    gap:8px;
    margin-top:8px;
}

.quick-btn{
    flex:1;
    padding:8px;
    border-radius:10px;
    border:1px solid rgba(184,255,59,0.10);
    background:#141f18;
    color:#c8d8cf;
    font-size:12px;
    font-weight:700;
    cursor:pointer;
    transition:.15s ease;
}

.quick-btn:hover{
    border-color:rgba(184,255,59,0.25);
    background:#1a261e;
}

.trade-preview{
    margin-top:14px;
    padding:12px;
    border-radius:14px;
    background:#141f18;
    border:1px solid rgba(184,255,59,0.06);
    font-size:14px;
    color:#a9b8ae;
}

.trade-preview strong{
    color:#f4fff8;
}

.actions{
    margin-top:14px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.btn-buy,
.btn-sell{
    border:none;
    border-radius:18px;
    padding:16px;
    font-size:16px;
    font-weight:800;
    cursor:pointer;
    transition:.2s ease;
}

.btn-buy{
    background:linear-gradient(180deg,#3dd598 0%, #25b67a 100%);
    color:#04110a;
}

.btn-buy:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(61,213,152,0.35);
}

.btn-sell{
    background:linear-gradient(180deg,#ff7b8e 0%, #ef546d 100%);
    color:#ffffff;
}

.btn-sell:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(255,95,116,0.35);
}

.notice{
    margin-top:12px;
    color:#8da095;
    font-size:13px;
    line-height:1.8;
}

@media (max-width:700px){
    .trade-title{
        font-size:24px;
    }

    .trade-summary{
        grid-template-columns:1fr;
    }

    .meta-grid{
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .orderbook-grid{
        grid-template-columns:1fr;
    }

    .form-grid,
    .actions{
        grid-template-columns:1fr;
    }

    #chartContainer{
        height:360px;
    }
}
</style>

<div class="trade-page">

    <div class="trade-hero">
        <div class="trade-hero-inner">
            <div class="trade-top">
                <div class="trade-logo">{{ $base[0] ?? 'D' }}</div>
                <div class="trade-chip" id="liveBadge">LIVE MARKET</div>
            </div>

            <div>
                <div class="trade-title">{{ $pairName }}</div>
                <div class="trade-sub">
                    شارت حي، شموع حقيقية، ودفتر أوامر وصفقات مباشرة من السوق. هكذا تبدو الصفحة عندما تكون مبنية لتبعث الثقة لا الشك.
                </div>
            </div>
        </div>
    </div>

    <div class="trade-summary">
        <div class="summary-card">
            <div class="summary-label">Last Price</div>
            <div class="summary-value" id="lastPrice">...</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">24H Change</div>
            <div class="summary-value" id="change24h">...</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Main Balance</div>
            <div class="summary-value">${{ number_format($wallet->balance ?? 0, 2) }}</div>
        </div>
    </div>

    <div class="chart-panel">
        <div class="panel-head">
            <div class="panel-title">Live Chart</div>

            <div class="timeframes">
                <button class="tf-btn active" data-interval="1m">1m</button>
                <button class="tf-btn" data-interval="5m">5m</button>
                <button class="tf-btn" data-interval="15m">15m</button>
                <button class="tf-btn" data-interval="1h">1h</button>
            </div>
        </div>

        <div id="chartContainer"></div>

        <div class="meta-grid">
            <div class="meta-card">
                <div class="meta-label">Open</div>
                <div class="meta-value" id="metaOpen">...</div>
            </div>

            <div class="meta-card">
                <div class="meta-label">High</div>
                <div class="meta-value" id="metaHigh">...</div>
            </div>

            <div class="meta-card">
                <div class="meta-label">Low</div>
                <div class="meta-value" id="metaLow">...</div>
            </div>

            <div class="meta-card">
                <div class="meta-label">Close</div>
                <div class="meta-value" id="metaClose">...</div>
            </div>
        </div>
    </div>

    <div class="orderbook-panel">
        <div class="panel-head">
            <div class="panel-title">Order Book</div>
            <div class="panel-link">Live Depth</div>
        </div>

        <div class="orderbook-grid">

            <div class="orderbook-side">
                <div class="ob-title sell">Sell Orders</div>
                <div id="sellOrders"></div>
            </div>

            <div class="orderbook-side">
                <div class="ob-title buy">Buy Orders</div>
                <div id="buyOrders"></div>
            </div>

        </div>
    </div>

    <div class="recenttrades-panel">
        <div class="panel-head">
            <div class="panel-title">Recent Trades</div>
            <div class="panel-link">Live Tape</div>
        </div>

        <div class="trades-header">
            <div>Price</div>
            <div>Amount</div>
            <div>Time</div>
        </div>

        <div id="recentTrades"></div>
    </div>

    <div class="controls-panel">
        <div class="panel-head">
            <div class="panel-title">Trade Controls</div>
            <div class="panel-link">{{ $pairName }}</div>
        </div>

        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Amount ($)</label>

                <input
                    type="number"
                    id="tradeAmount"
                    class="form-input"
                    placeholder="0.00"
                >

                <div class="quick-amount">
                    <button type="button" class="quick-btn" onclick="setPercent(0.25)">25%</button>
                    <button type="button" class="quick-btn" onclick="setPercent(0.50)">50%</button>
                    <button type="button" class="quick-btn" onclick="setPercent(0.75)">75%</button>
                    <button type="button" class="quick-btn" onclick="setPercent(1)">MAX</button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Execution Type</label>
                <input type="text" class="form-input" value="Market Order" readonly>
            </div>

        </div>

        <div class="trade-preview">
            Estimated Value:
            <strong id="tradeValue">$0.00</strong>
        </div>

        <div class="actions">
            <button class="btn-buy">Buy</button>
            <button class="btn-sell">Sell</button>
        </div>

        <div class="notice">
            هذه الصفحة تستخدم بيانات سوق مباشرة لعرض الشموع ودفتر الأوامر والصفقات الأخيرة. هذا هو النوع من الشفافية الذي يطمئن المستخدم.
        </div>
    </div>

</div>

<script src="https://unpkg.com/lightweight-charts@4.2.0/dist/lightweight-charts.standalone.production.js"></script>
<script>
const symbol = "{{ strtoupper($symbol) }}";
let currentInterval = "1m";

let chart;
let candleSeries;
let volumeSeries;

let wsKline = null;
let wsTicker = null;
let wsDepth = null;
let wsTrades = null;

const chartContainer = document.getElementById("chartContainer");
const lastPriceEl = document.getElementById("lastPrice");
const change24hEl = document.getElementById("change24h");
const metaOpen = document.getElementById("metaOpen");
const metaHigh = document.getElementById("metaHigh");
const metaLow = document.getElementById("metaLow");
const metaClose = document.getElementById("metaClose");

const sellOrdersBox = document.getElementById("sellOrders");
const buyOrdersBox = document.getElementById("buyOrders");
const recentTradesBox = document.getElementById("recentTrades");

const balance = {{ $wallet->balance ?? 0 }};
const amountInput = document.getElementById("tradeAmount");
const valuePreview = document.getElementById("tradeValue");

function formatPrice(value) {
    const num = parseFloat(value || 0);
    if (num >= 1000) return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (num >= 1) return num.toFixed(4);
    return num.toFixed(6);
}

function setPercent(p){
    const value = balance * p;
    amountInput.value = value.toFixed(2);
    updatePreview();
}

function updatePreview(){
    const val = parseFloat(amountInput.value || 0);
    valuePreview.textContent = "$" + val.toFixed(2);
}

amountInput.addEventListener("input", updatePreview);

function initChart() {
    chartContainer.innerHTML = "";

    chart = LightweightCharts.createChart(chartContainer, {
        layout: {
            background: { color: '#0a120d' },
            textColor: '#a9b8ae',
        },
        grid: {
            vertLines: { color: 'rgba(184,255,59,0.05)' },
            horzLines: { color: 'rgba(184,255,59,0.05)' },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
            vertLine: {
                color: 'rgba(184,255,59,0.20)',
                width: 1,
                style: 2,
                labelBackgroundColor: '#1a2a1f',
            },
            horzLine: {
                color: 'rgba(184,255,59,0.20)',
                width: 1,
                style: 2,
                labelBackgroundColor: '#1a2a1f',
            },
        },
        rightPriceScale: {
            borderColor: 'rgba(184,255,59,0.10)',
        },
        timeScale: {
            borderColor: 'rgba(184,255,59,0.10)',
            timeVisible: true,
            secondsVisible: false,
        },
        width: chartContainer.clientWidth,
        height: 420,
    });

    candleSeries = chart.addCandlestickSeries({
        upColor: '#3dd598',
        downColor: '#ff5f74',
        borderDownColor: '#ff5f74',
        borderUpColor: '#3dd598',
        wickDownColor: '#ff5f74',
        wickUpColor: '#3dd598',
        priceLineVisible: true,
        lastValueVisible: true,
        priceLineColor: '#b8ff3b',
    });

    volumeSeries = chart.addHistogramSeries({
        priceFormat: { type: 'volume' },
        priceScaleId: '',
        scaleMargins: {
            top: 0.82,
            bottom: 0,
        },
    });

    window.addEventListener('resize', () => {
        if (chart) {
            chart.applyOptions({ width: chartContainer.clientWidth });
        }
    });
}

async function loadHistory() {
    const url = `https://api.binance.com/api/v3/klines?symbol=${symbol}&interval=${currentInterval}&limit=120`;
    const response = await fetch(url);
    const data = await response.json();

    const candles = data.map(k => ({
        time: Math.floor(k[0] / 1000),
        open: parseFloat(k[1]),
        high: parseFloat(k[2]),
        low: parseFloat(k[3]),
        close: parseFloat(k[4]),
    }));

    const volumes = data.map(k => ({
        time: Math.floor(k[0] / 1000),
        value: parseFloat(k[5]),
        color: parseFloat(k[4]) >= parseFloat(k[1]) ? 'rgba(61,213,152,0.55)' : 'rgba(255,95,116,0.55)'
    }));

    candleSeries.setData(candles);
    volumeSeries.setData(volumes);
    chart.timeScale().fitContent();

    const last = candles[candles.length - 1];
    if (last) {
        metaOpen.textContent = formatPrice(last.open);
        metaHigh.textContent = formatPrice(last.high);
        metaLow.textContent = formatPrice(last.low);
        metaClose.textContent = formatPrice(last.close);
        metaClose.style.color = last.close >= last.open ? '#62ff9a' : '#ff5f74';
    }
}

function connectTicker() {
    if (wsTicker) wsTicker.close();

    wsTicker = new WebSocket(`wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@ticker`);

    wsTicker.onmessage = (event) => {
        const data = JSON.parse(event.data);
        const lastPrice = parseFloat(data.c);
        const change = parseFloat(data.P);

        lastPriceEl.textContent = formatPrice(lastPrice);
        change24hEl.textContent = (change >= 0 ? '+' : '') + change.toFixed(2) + '%';
        change24hEl.style.color = change >= 0 ? '#62ff9a' : '#ff5f74';
    };

    wsTicker.onclose = () => {
        setTimeout(connectTicker, 1500);
    };
}

function connectKline() {
    if (wsKline) wsKline.close();

    wsKline = new WebSocket(`wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@kline_${currentInterval}`);

    wsKline.onmessage = (event) => {
        const data = JSON.parse(event.data);
        const k = data.k;

        const candle = {
            time: Math.floor(k.t / 1000),
            open: parseFloat(k.o),
            high: parseFloat(k.h),
            low: parseFloat(k.l),
            close: parseFloat(k.c),
        };

        candleSeries.update(candle);
        volumeSeries.update({
            time: Math.floor(k.t / 1000),
            value: parseFloat(k.v),
            color: parseFloat(k.c) >= parseFloat(k.o)
                ? 'rgba(61,213,152,0.55)'
                : 'rgba(255,95,116,0.55)'
        });

        metaOpen.textContent = formatPrice(k.o);
        metaHigh.textContent = formatPrice(k.h);
        metaLow.textContent = formatPrice(k.l);
        metaClose.textContent = formatPrice(k.c);
        metaClose.style.color = parseFloat(k.c) >= parseFloat(k.o) ? '#62ff9a' : '#ff5f74';
    };

    wsKline.onclose = () => {
        setTimeout(connectKline, 1500);
    };
}

function connectDepth(){
    if(wsDepth) wsDepth.close();

    wsDepth = new WebSocket(`wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@depth10@100ms`);

    wsDepth.onmessage = (event) => {
        const data = JSON.parse(event.data);

        sellOrdersBox.innerHTML = "";
        buyOrdersBox.innerHTML = "";

        if (data.asks && Array.isArray(data.asks)) {
            data.asks.forEach(a => {
                sellOrdersBox.innerHTML += `
                    <div class="order-row">
                        <span style="color:#ff5f74">${formatPrice(a[0])}</span>
                        <span>${parseFloat(a[1]).toFixed(4)}</span>
                    </div>
                `;
            });
        }

        if (data.bids && Array.isArray(data.bids)) {
            data.bids.forEach(b => {
                buyOrdersBox.innerHTML += `
                    <div class="order-row">
                        <span style="color:#62ff9a">${formatPrice(b[0])}</span>
                        <span>${parseFloat(b[1]).toFixed(4)}</span>
                    </div>
                `;
            });
        }
    };

    wsDepth.onclose = () => {
        setTimeout(connectDepth, 1500);
    };
}

function connectTrades(){
    if(wsTrades) wsTrades.close();

    wsTrades = new WebSocket(`wss://stream.binance.com:9443/ws/${symbol.toLowerCase()}@trade`);

    wsTrades.onmessage = (event) => {
        const trade = JSON.parse(event.data);

        const price = parseFloat(trade.p);
        const qty = parseFloat(trade.q);
        const time = new Date(trade.T).toLocaleTimeString([], {
            hour: '2-digit',
            minute:'2-digit',
            second:'2-digit'
        });
        const side = trade.m ? "sell" : "buy";

        const row = `
            <div class="trade-item">
                <div class="trade-price ${side}">${formatPrice(price)}</div>
                <div>${qty.toFixed(4)}</div>
                <div>${time}</div>
            </div>
        `;

        recentTradesBox.innerHTML = row + recentTradesBox.innerHTML;

        const rows = recentTradesBox.querySelectorAll(".trade-item");
        if(rows.length > 15){
            rows[rows.length - 1].remove();
        }
    };

    wsTrades.onclose = () => {
        setTimeout(connectTrades, 1500);
    };
}

document.querySelectorAll('.tf-btn').forEach(btn => {
    btn.addEventListener('click', async function () {
        document.querySelectorAll('.tf-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentInterval = this.dataset.interval;

        await loadHistory();
        connectKline();
    });
});

(async function boot() {
    initChart();
    await loadHistory();
    connectTicker();
    connectKline();
    connectDepth();
    connectTrades();
    updatePreview();
})();
</script>

@endsection