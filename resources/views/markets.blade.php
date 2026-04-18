@extends('layouts.app')

@section('content')

<style>

.markets-page{
display:flex;
flex-direction:column;
gap:18px;
}

.markets-hero{
position:relative;
overflow:hidden;
border-radius:30px;
padding:22px;
min-height:190px;

background:
linear-gradient(135deg, rgba(5,10,7,0.50) 0%, rgba(5,10,7,0.76) 100%),
radial-gradient(circle at 72% 35%, rgba(184,255,59,0.18) 0%, rgba(184,255,59,0) 28%),
linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);

border:1px solid rgba(184,255,59,0.10);
box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.markets-hero::before{
content:"";
position:absolute;
right:-50px;
top:-30px;
width:220px;
height:220px;
border-radius:50%;
background:radial-gradient(circle, rgba(184,255,59,0.10) 0%, rgba(184,255,59,0) 70%);
}

.markets-hero::after{
content:"";
position:absolute;
left:-60px;
bottom:-70px;
width:220px;
height:220px;
border-radius:50%;
background:radial-gradient(circle, rgba(98,255,154,0.08) 0%, rgba(98,255,154,0) 70%);
}

.markets-hero-inner{
position:relative;
z-index:2;
display:flex;
flex-direction:column;
justify-content:space-between;
height:100%;
gap:16px;
}

.markets-hero-top{
display:flex;
justify-content:space-between;
align-items:flex-start;
gap:14px;
}

.markets-logo{
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

.markets-chip{
padding:8px 12px;
border-radius:14px;
font-size:12px;
font-weight:700;
color:#dfffc3;
background:rgba(184,255,59,0.08);
border:1px solid rgba(184,255,59,0.12);
}

.markets-title{
font-size:28px;
line-height:1.35;
font-weight:800;
color:#f4fff8;
max-width:290px;
}

.markets-sub{
font-size:14px;
line-height:1.9;
color:#b9c8bf;
max-width:320px;
margin-top:8px;
}

.markets-panel{
border-radius:28px;
padding:18px;
background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
border:1px solid rgba(184,255,59,0.08);
box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.search-wrap{
position:relative;
}

.search-input{
width:100%;
height:56px;
border-radius:18px;
border:1px solid rgba(184,255,59,0.08);
background:#0d1711;
padding:0 18px;
font-size:15px;
color:#f4fff8;
outline:none;
}

.search-input::placeholder{
color:#6f8478;
}

.section-head{
display:flex;
justify-content:space-between;
align-items:center;
gap:12px;
margin-bottom:16px;
}

.section-title{
font-size:23px;
font-weight:800;
color:#f4fff8;
}

.section-link{
font-size:14px;
font-weight:700;
color:#b8ff3b;
}

.market-list{
display:flex;
flex-direction:column;
gap:14px;
}

.market-row{
display:grid;
grid-template-columns:auto 1fr auto;
align-items:center;
gap:14px;
padding:16px;
border-radius:24px;
background:linear-gradient(180deg,#141f18 0%, #101913 100%);
border:1px solid rgba(184,255,59,0.07);
transition:.22s ease;
text-decoration:none;
}

.market-row:hover{
transform:translateY(-2px);
border-color:rgba(184,255,59,0.18);
}

.coin-icon{
width:52px;
height:52px;
border-radius:18px;
display:flex;
align-items:center;
justify-content:center;
font-size:18px;
font-weight:800;
color:#061008;
box-shadow:0 10px 20px rgba(0,0,0,0.18);
}

.coin-main{
display:flex;
flex-direction:column;
gap:6px;
}

.coin-pair{
font-size:21px;
font-weight:800;
color:#f4fff8;
line-height:1.2;
}

.coin-name{
font-size:13px;
font-weight:700;
color:#8da095;
}

.coin-side{
display:flex;
flex-direction:column;
align-items:flex-end;
gap:8px;
}

.coin-tag{
padding:8px 14px;
min-width:96px;
text-align:center;
border-radius:999px;
font-size:13px;
font-weight:800;
color:#fff;
}

.coin-open{
font-size:13px;
font-weight:700;
color:#a3b5aa;
}

.empty-box{
border-radius:24px;
padding:24px;
text-align:center;
background:linear-gradient(180deg,#141f18 0%, #101913 100%);
border:1px solid rgba(184,255,59,0.07);
color:#83968a;
font-size:14px;
line-height:1.9;
}

.note{
font-size:13px;
line-height:1.8;
text-align:center;
color:#83968a;
}

@media (max-width:700px){

.markets-title{
font-size:24px;
}

.market-row{
grid-template-columns:1fr;
text-align:center;
}

.coin-main,
.coin-side{
align-items:center;
}

}
</style>

<div class="markets-page">

    <div class="markets-hero">
        <div class="markets-hero-inner">

            <div class="markets-hero-top">
                <div class="markets-logo">M</div>
                <div class="markets-chip">DawnEX Markets</div>
            </div>

            <div>
                <div class="markets-title">اختر الزوج المناسب وادخل إلى التداول مباشرة</div>
                <div class="markets-sub">
                    صفحة الأسواق هنا نظيفة وواضحة. تعرض فقط الأزواج المهمة ونسبة التغيّر، بدون ازدحام يوجع الرأس.
                </div>
            </div>

        </div>
    </div>

    <div class="markets-panel">
        <div class="section-head">
            <div class="section-title">Search Pair</div>
            <div class="section-link">Live</div>
        </div>

        <div class="search-wrap">
            <input
                id="searchInput"
                type="text"
                class="search-input"
                placeholder="ابحث عن الزوج أو اسم العملة..."
            >
        </div>
    </div>

    <div class="markets-panel">
        <div class="section-head">
            <div class="section-title">Available Pairs</div>
            <div class="section-link">Tap to open</div>
        </div>

        <div id="marketsList" class="market-list">

            <a href="/trade/BTCUSDT" class="market-row market-item" data-search="btc usdt bitcoin">
                <div class="coin-icon" style="background:#b8ff3b;">₿</div>

                <div class="coin-main">
                    <div class="coin-pair">BTC / USDT</div>
                    <div class="coin-name">Bitcoin</div>
                </div>

                <div class="coin-side">
                    <div id="BTCUSDT-change" class="coin-tag" style="background:#1f2937;">...</div>
                    <div class="coin-open">Open Pair</div>
                </div>
            </a>

            <a href="/trade/ETHUSDT" class="market-row market-item" data-search="eth usdt ethereum">
                <div class="coin-icon" style="background:#6ea8ff;">◎</div>

                <div class="coin-main">
                    <div class="coin-pair">ETH / USDT</div>
                    <div class="coin-name">Ethereum</div>
                </div>

                <div class="coin-side">
                    <div id="ETHUSDT-change" class="coin-tag" style="background:#1f2937;">...</div>
                    <div class="coin-open">Open Pair</div>
                </div>
            </a>

            <a href="/trade/TRXUSDT" class="market-row market-item" data-search="trx usdt tron">
                <div class="coin-icon" style="background:#ff6b6b;">T</div>

                <div class="coin-main">
                    <div class="coin-pair">TRX / USDT</div>
                    <div class="coin-name">Tron</div>
                </div>

                <div class="coin-side">
                    <div id="TRXUSDT-change" class="coin-tag" style="background:#1f2937;">...</div>
                    <div class="coin-open">Open Pair</div>
                </div>
            </a>

            <a href="/trade/DOGEUSDT" class="market-row market-item" data-search="doge usdt dogecoin">
                <div class="coin-icon" style="background:#ffd166;">Ð</div>

                <div class="coin-main">
                    <div class="coin-pair">DOGE / USDT</div>
                    <div class="coin-name">Dogecoin</div>
                </div>

                <div class="coin-side">
                    <div id="DOGEUSDT-change" class="coin-tag" style="background:#1f2937;">...</div>
                    <div class="coin-open">Open Pair</div>
                </div>
            </a>

            <a href="/trade/BCHUSDT" class="market-row market-item" data-search="bch usdt bitcoin cash">
                <div class="coin-icon" style="background:#8dff1c;">B</div>

                <div class="coin-main">
                    <div class="coin-pair">BCH / USDT</div>
                    <div class="coin-name">Bitcoin Cash</div>
                </div>

                <div class="coin-side">
                    <div id="BCHUSDT-change" class="coin-tag" style="background:#1f2937;">...</div>
                    <div class="coin-open">Open Pair</div>
                </div>
            </a>

        </div>

        <div id="emptyState" class="empty-box" style="display:none; margin-top:14px;">
            لا يوجد زوج مطابق للبحث الحالي.
        </div>
    </div>

    <div class="note">
        صفحة الأسواق الآن متناسقة مع DawnEX: هادئة، واضحة، وسهلة التنقل.
    </div>

</div>

<script>
const symbols = [
    "BTCUSDT",
    "ETHUSDT",
    "TRXUSDT",
    "DOGEUSDT",
    "BCHUSDT"
];

async function updateChange(symbol) {
    try {
        const response = await fetch(`https://api.binance.com/api/v3/ticker/24hr?symbol=${symbol}`);
        const data = await response.json();

        const el = document.getElementById(symbol + "-change");
        if (!el || !data.priceChangePercent) return;

        const change = parseFloat(data.priceChangePercent).toFixed(2);
        el.textContent = (change >= 0 ? "+" : "") + change + "%";

        if (parseFloat(change) >= 0) {
            el.style.background = "#3dd598";
            el.style.color = "#07120b";
        } else {
            el.style.background = "#ff5f74";
            el.style.color = "#ffffff";
        }

    } catch (error) {
        const el = document.getElementById(symbol + "-change");
        if (el) {
            el.textContent = "...";
            el.style.background = "#1f2937";
            el.style.color = "#ffffff";
        }
    }
}

async function loadAllChanges() {
    for (const symbol of symbols) {
        updateChange(symbol);
    }
}

loadAllChanges();
setInterval(loadAllChanges, 3000);

const searchInput = document.getElementById("searchInput");
const items = document.querySelectorAll(".market-item");
const emptyState = document.getElementById("emptyState");

searchInput.addEventListener("input", function () {
    const value = this.value.toLowerCase().trim();
    let visibleCount = 0;

    items.forEach(item => {
        const searchData = item.getAttribute("data-search");
        if (searchData.includes(value)) {
            item.style.display = "grid";
            visibleCount++;
        } else {
            item.style.display = "none";
        }
    });

    emptyState.style.display = visibleCount === 0 ? "block" : "none";
});
</script>

@endsection