@extends('layouts.app')

@section('content')

@php
    $strategies = $strategies ?? collect();
    $wallet = $wallet ?? null;
    $user = $user ?? auth()->user();

    $totalStrategyValue = 0;
    $closedProfit = 0;
    $activeCount = 0;
    $closedTotalValue = 0;

    $activeStrategies = $strategies->filter(function ($item) {
        return in_array($item->status, ['pending', 'executing', 'redeem_pending']);
    })->values();

    $historyStrategies = $strategies->filter(function ($item) {
        return in_array($item->status, ['closed', 'cancelled']);
    })->values();

    foreach ($strategies as $s) {
        $safeAmount = round((float) $s->amount, 2);
        $safeProfit = round(max((float) $s->current_profit, 0), 2);

        if ($s->status === 'closed') {
            $closedProfit += $safeProfit;
            $closedTotalValue += ($safeAmount + $safeProfit);
            $totalStrategyValue += ($safeAmount + $safeProfit);
        }

        if (in_array($s->status, ['pending', 'executing', 'redeem_pending'])) {
            $activeCount++;
            $totalStrategyValue += ($safeAmount + $safeProfit);
        }
    }

    $totalStrategyValue = round($totalStrategyValue, 2);
    $closedProfit = round($closedProfit, 2);
    $closedTotalValue = round($closedTotalValue, 2);
@endphp

<style>
.ai-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.ai-alert-success,
.ai-alert-error{
    padding:14px 16px;
    border-radius:18px;
    font-size:14px;
    font-weight:700;
}

.ai-alert-success{
    background:rgba(61,213,152,0.10);
    border:1px solid rgba(61,213,152,0.18);
    color:#a7f3d0;
}

.ai-alert-error{
    background:rgba(255,95,116,0.10);
    border:1px solid rgba(255,95,116,0.18);
    color:#ffd3da;
}

.ai-hero{
    position:relative;
    overflow:hidden;
    border-radius:30px;
    padding:22px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.55) 0%, rgba(5,10,7,0.78) 100%),
        radial-gradient(circle at 75% 35%, rgba(184,255,59,0.18) 0%, rgba(184,255,59,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.ai-hero-inner{
    position:relative;
    z-index:2;
    display:flex;
    flex-direction:column;
    gap:14px;
}

.ai-title{
    font-size:28px;
    line-height:1.25;
    font-weight:900;
    color:#f4fff8;
}

.ai-sub{
    color:#b9c8bf;
    font-size:14px;
    line-height:1.8;
}

.ai-actions-top{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.ai-create-btn,
.ai-history-toggle,
.ai-create-disabled{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:155px;
    padding:13px 18px;
    border-radius:18px;
    font-size:14px;
    font-weight:800;
    transition:.22s ease;
}

.ai-create-btn{
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
}

.ai-create-disabled{
    background:#141f18;
    border:1px solid rgba(184,255,59,0.08);
    color:#8da095;
    cursor:not-allowed;
}

.ai-history-toggle{
    background:#121c16;
    border:1px solid rgba(184,255,59,0.16);
    color:#dfffc3;
    cursor:pointer;
}

.ai-history-toggle.active{
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
}

.ai-stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
}

.ai-stat-card{
    border-radius:24px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.ai-stat-label{
    font-size:12px;
    font-weight:700;
    letter-spacing:1px;
    text-transform:uppercase;
    color:#8da095;
    margin-bottom:8px;
}

.ai-stat-value{
    font-size:24px;
    font-weight:800;
    color:#f4fff8;
    line-height:1.2;
}

.ai-panel{
    border-radius:28px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.08);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.ai-section-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:16px;
    flex-wrap:wrap;
}

.ai-section-title{
    font-size:22px;
    font-weight:900;
    color:#f4fff8;
}

.ai-section-side{
    color:#b8ff3b;
    font-size:14px;
    font-weight:800;
}

.ai-empty{
    border-radius:24px;
    padding:24px;
    text-align:center;
    background:#121c16;
    border:1px solid rgba(184,255,59,0.08);
    color:#9fb4a4;
    font-size:14px;
    line-height:1.9;
}

.ai-active-card{
    border-radius:28px;
    padding:18px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.ai-card-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:14px;
    flex-wrap:wrap;
}

.ai-card-title{
    font-size:20px;
    font-weight:800;
    color:#b8ff3b;
}

.ai-card-order{
    font-size:12px;
    color:#8da095;
    font-weight:700;
}

.ai-card-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
    margin-bottom:14px;
}

.ai-card-label{
    font-size:13px;
    color:#8da095;
    margin-bottom:6px;
}

.ai-card-value{
    font-size:18px;
    font-weight:800;
    color:#f4fff8;
}

.ai-card-profit{
    color:#62ff9a;
}

.ai-status-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
    white-space:nowrap;
}

.ai-status-badge.pending{
    background:rgba(110,168,255,0.14);
    color:#cfe1ff;
    border:1px solid rgba(110,168,255,0.14);
}

.ai-status-badge.executing{
    background:rgba(184,255,59,0.14);
    color:#dfffab;
    border:1px solid rgba(184,255,59,0.14);
}

.ai-status-badge.redeem_pending{
    background:rgba(240,185,11,0.14);
    color:#ffe49a;
    border:1px solid rgba(240,185,11,0.14);
}

.ai-status-badge.closed{
    background:rgba(61,213,152,0.12);
    color:#b7ffd7;
    border:1px solid rgba(61,213,152,0.14);
}

.ai-status-badge.cancelled{
    background:rgba(255,95,116,0.12);
    color:#ffd3da;
    border:1px solid rgba(255,95,116,0.14);
}

.ai-card-note{
    padding:12px 14px;
    border-radius:16px;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.10);
    color:#dfffc3;
    font-size:13px;
    line-height:1.8;
    margin-bottom:14px;
}

.ai-card-note.pending{
    background:rgba(240,185,11,0.10);
    border:1px solid rgba(240,185,11,0.14);
    color:#ffe49a;
}

.ai-card-actions{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.ai-btn-accent,
.ai-btn-danger,
.ai-btn-dark{
    display:flex;
    align-items:center;
    justify-content:center;
    border:none;
    border-radius:18px;
    padding:14px;
    font-size:15px;
    font-weight:800;
    width:100%;
    text-align:center;
}

.ai-btn-accent{
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
}

.ai-btn-danger{
    background:linear-gradient(180deg,#ff7b8e 0%, #ef546d 100%);
    color:#ffffff;
}

.ai-btn-dark{
    background:#141f18;
    border:1px solid rgba(184,255,59,0.08);
    color:#f4fff8;
}

.ai-history-panel{
    display:none;
}

.ai-history-panel.show{
    display:block;
}

.ai-history-table-wrapper{
    overflow-x:auto;
    border-radius:20px;
    background:#121c16;
    border:1px solid rgba(184,255,59,0.08);
}

.ai-history-table{
    width:100%;
    min-width:780px;
    border-collapse:collapse;
}

.ai-history-table th,
.ai-history-table td{
    padding:14px 12px;
    border-bottom:1px solid rgba(184,255,59,0.08);
    color:#fff;
    font-size:13px;
    text-align:left;
}

.ai-history-table th{
    color:#9fb4a4;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.ai-history-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:800;
}

.ai-history-badge.closed{
    background:rgba(61,213,152,0.2);
    color:#b7ffd7;
}

.ai-history-badge.cancelled{
    background:rgba(255,95,116,0.2);
    color:#ffd3da;
}

.cancel-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.68);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    padding:20px;
}

.cancel-box{
    width:100%;
    max-width:380px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border-radius:24px;
    padding:24px;
    text-align:center;
    border:1px solid rgba(184,255,59,0.15);
    box-shadow:0 25px 60px rgba(0,0,0,0.45);
}

.cancel-box h3{
    color:#f4fff8;
    margin-bottom:10px;
    font-size:24px;
    font-weight:800;
}

.cancel-box p{
    font-size:14px;
    color:#b7c8b6;
    line-height:1.9;
}

.cancel-buttons{
    margin-top:20px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.cancel-back{
    padding:13px;
    border-radius:16px;
    border:none;
    background:#1a251c;
    color:#fff;
    font-weight:800;
    cursor:pointer;
}

.cancel-confirm{
    width:100%;
    padding:13px;
    border-radius:16px;
    border:none;
    background:linear-gradient(180deg,#ff7b8e 0%, #ef546d 100%);
    color:white;
    font-weight:800;
    cursor:pointer;
}

@media (max-width:700px){
    .ai-stats{
        grid-template-columns:1fr;
    }

    .ai-card-grid{
        grid-template-columns:1fr;
    }

    .ai-card-actions{
        grid-template-columns:1fr;
    }

    .cancel-buttons{
        grid-template-columns:1fr;
    }
}
</style>

<div class="ai-page">

    @if(session('success'))
        <div class="ai-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="ai-alert-error">{{ session('error') }}</div>
    @endif

    <div class="ai-hero">
        <div class="ai-hero-inner">
            <div class="ai-title">AI Trading Dashboard</div>
            <div class="ai-sub">AI strategies and history</div>

            <div class="ai-actions-top">
                @if($activeCount > 0)
                    <div class="ai-create-disabled">Strategy Active</div>
                @else
                    <a href="/ai/create" class="ai-create-btn">Create Strategy</a>
                @endif

                <button id="historyBtn" class="ai-history-toggle" type="button" onclick="toggleHistory()">
                    Trade History
                </button>
            </div>
        </div>
    </div>

    <div class="ai-stats">
        <div class="ai-stat-card">
            <div class="ai-stat-label">Closed Profit</div>
            <div class="ai-stat-value">${{ number_format($closedProfit,2) }}</div>
        </div>

        <div class="ai-stat-card">
            <div class="ai-stat-label">Available Balance</div>
            <div class="ai-stat-value">${{ number_format($wallet->balance ?? 0,2) }}</div>
        </div>

        <div class="ai-stat-card">
            <div class="ai-stat-label">Total Strategy Value</div>
            <div
                class="ai-stat-value"
                id="totalStrategyValueCard"
                data-closed-total="{{ number_format($closedTotalValue, 2, '.', '') }}"
            >
                ${{ number_format($totalStrategyValue,2) }}
            </div>
        </div>
    </div>

    <div class="ai-panel">
        <div class="ai-section-head">
            <div class="ai-section-title">Active Strategy</div>
            <div class="ai-section-side">Active {{ $activeCount }}</div>
        </div>

        @if($activeStrategies->count() == 0)
            <div class="ai-empty">
                لا يوجد صفقة نشطة الآن
            </div>
        @else

            @foreach($activeStrategies as $s)

                @php
                    $unlockTs = $s->unlock_at ? strtotime($s->unlock_at) : null;
                    $redeemTs = $s->redeem_available_at ? strtotime($s->redeem_available_at) : null;
                    $startedTs = $s->started_at ? strtotime($s->started_at) : time();

                    $seconds = $unlockTs ? max($unlockTs - time(), 0) : 0;
                    $h = floor($seconds / 3600);
                    $m = floor(($seconds % 3600) / 60);
                    $sec = $seconds % 60;
                    $timeLeft = $h . 'h ' . $m . 'm ' . $sec . 's';
                @endphp

                <div class="ai-active-card">
                    <div class="ai-card-top">
                        <div class="ai-card-title">{{ $s->strategy_name }}</div>
                        <div class="ai-card-order">Order #{{ $s->order_no }}</div>
                    </div>

                    <div class="ai-card-grid">
                        <div>
                            <div class="ai-card-label">Invested Amount</div>
                            <div class="ai-card-value strategy-amount" data-amount="{{ number_format((float)$s->amount, 2, '.', '') }}">
                                ${{ number_format($s->amount, 2) }}
                            </div>
                        </div>

                        <div>
                            <div class="ai-card-label">Current Profit</div>
                            <div
                                class="ai-card-value ai-card-profit strategy-profit"
                                id="profit-{{ $s->id }}"
                                data-amount="{{ number_format((float)$s->amount, 2, '.', '') }}"
                                data-percent="{{ number_format((float)$s->target_percent, 4, '.', '') }}"
                                data-started="{{ $startedTs }}"
                                data-lockhours="{{ (int)$s->lock_hours }}"
                                data-baseprofit="{{ number_format((float)$s->current_profit, 2, '.', '') }}"
                                data-status="{{ $s->status }}"
                            >
                                ${{ number_format($s->current_profit, 2) }}
                            </div>
                        </div>

                        <div>
                            <div class="ai-card-label">Pair</div>
                            <div class="ai-card-value">{{ $s->target_pair }}</div>
                        </div>

                        <div>
                            <div class="ai-card-label">Status</div>
                            <div class="ai-card-value">
                                <span class="ai-status-badge {{ $s->status }}">
                                    @if($s->status === 'pending')
                                        Pending
                                    @elseif($s->status === 'executing')
                                        Executing
                                    @elseif($s->status === 'redeem_pending')
                                        Redeem Pending
                                    @elseif($s->status === 'closed')
                                        Closed
                                    @elseif($s->status === 'cancelled')
                                        Cancelled
                                    @else
                                        {{ ucfirst($s->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($s->status === 'pending')
                        <div class="ai-card-note">
                            الصفقة قيد التهيئة الآن، وسيبدأ التنفيذ تلقائيًا بعد تجهيز النظام.
                        </div>

                        <div class="ai-card-actions">
                            <button class="ai-btn-dark" disabled>Initializing</button>

                            <button
                                class="ai-btn-danger"
                                type="button"
                                onclick="openCancelModal({{ $s->id }})"
                            >
                                Cancel
                            </button>
                        </div>
                    @elseif($s->status === 'executing')
                        <div class="ai-card-note">
                            لا تزال الصفقة قيد التنفيذ. الوقت المتبقي حتى انتهاء الصفقة:
                            <strong data-unlock="{{ $unlockTs }}">{{ $timeLeft }}</strong>.
                        </div>

                        <div class="ai-card-actions">
                            @if($unlockTs && $unlockTs <= time())
                                <form method="POST" action="/ai/redeem/{{ $s->id }}">
                                    @csrf
                                    <button type="submit" class="ai-btn-accent">Redeem</button>
                                </form>
                            @else
                                <button class="ai-btn-dark" disabled>Redeem Locked</button>
                            @endif

                            <button
                                class="ai-btn-danger"
                                type="button"
                                onclick="openCancelModal({{ $s->id }})"
                            >
                                Cancel
                            </button>
                        </div>
                    @elseif($s->status === 'redeem_pending')
                        <div class="ai-card-note pending">
                            تم طلب Redeem بنجاح. سيتم إضافة الأموال إلى الرصيد بعد
                            <strong data-redeem="{{ $redeemTs }}">Waiting...</strong>.
                        </div>

                        <div class="ai-card-actions">
                            <button class="ai-btn-dark" disabled>Waiting 24H</button>

                            <button
                                class="ai-btn-danger"
                                type="button"
                                onclick="openCancelModal({{ $s->id }})"
                            >
                                Cancel
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach

        @endif
    </div>

    <div class="ai-panel ai-history-panel" id="historyPanel">
        <div class="ai-section-head">
            <div class="ai-section-title">Trade History</div>
            <div class="ai-section-side">Records {{ $historyStrategies->count() }}</div>
        </div>

        @if($historyStrategies->count() == 0)
            <div class="ai-empty">
                لا يوجد سجل صفقات منتهية أو ملغاة بعد
            </div>
        @else
            <div class="ai-history-table-wrapper">
                <table class="ai-history-table">
                    <thead>
                        <tr>
                            <th>Closed</th>
                            <th>Status</th>
                            <th>Profit</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Pair</th>
                            <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyStrategies as $h)
                            <tr>
                                <td>{{ $h->closed_at }}</td>
                                <td>
                                    <span class="ai-history-badge {{ $h->status }}">
                                        @if($h->status === 'closed')
                                            Closed
                                        @elseif($h->status === 'cancelled')
                                            Cancelled
                                        @else
                                            {{ ucfirst($h->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td>${{ number_format($h->current_profit,2) }}</td>
                                <td>{{ number_format($h->target_percent,2) }}%</td>
                                <td>${{ number_format($h->amount,2) }}</td>
                                <td>{{ $h->target_pair }}</td>
                                <td>{{ $h->order_no }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<div id="cancelModal" class="cancel-modal">
    <div class="cancel-box">
        <h3>Cancel Strategy</h3>

        <p>
            إذا قمت بإلغاء الصفقة سيتم إعادة رأس المال فقط،
            وسيتم حذف جميع الأرباح نهائيًا.
        </p>

        <div class="cancel-buttons">
            <button id="cancelBack" class="cancel-back" type="button">
                Back
            </button>

            <form id="cancelForm" method="POST">
                @csrf
                <button type="submit" class="cancel-confirm">
                    Confirm Cancel
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleHistory(){
    const panel = document.getElementById("historyPanel");
    const btn = document.getElementById("historyBtn");

    panel.classList.toggle("show");
    btn.classList.toggle("active");
}

const cancelModal = document.getElementById("cancelModal");
const cancelForm = document.getElementById("cancelForm");
const cancelBack = document.getElementById("cancelBack");

function openCancelModal(strategyId){
    cancelForm.action = "/ai/cancel/" + strategyId;
    cancelModal.style.display = "flex";
}

if (cancelBack) {
    cancelBack.addEventListener("click", function () {
        cancelModal.style.display = "none";
    });
}

window.addEventListener("click", function (e) {
    if (e.target === cancelModal) {
        cancelModal.style.display = "none";
    }
});

function formatMoney(value){
    return '$' + Number(value).toFixed(2);
}

function updateLiveProfit(){
    const totalCard = document.getElementById('totalStrategyValueCard');
    const closedTotal = totalCard ? parseFloat(totalCard.dataset.closedTotal || 0) : 0;

    let activeTotal = 0;
    let shouldReload = false;

    document.querySelectorAll('.strategy-profit').forEach(function(el){
        const status = el.dataset.status;
        const amount = parseFloat(el.dataset.amount || 0);
        const percent = parseFloat(el.dataset.percent || 0);
        const started = parseInt(el.dataset.started || 0);
        const lockHours = parseInt(el.dataset.lockhours || 0);
        const baseProfit = parseFloat(el.dataset.baseprofit || 0);

        if (status === 'pending') {
            activeTotal += amount + baseProfit;
            return;
        }

        if (status !== 'executing') {
            activeTotal += amount + baseProfit;
            return;
        }

        const nowTs = Math.floor(Date.now() / 1000);
        let elapsed = nowTs - started;

        if (elapsed < 0) elapsed = 0;

        const maxSeconds = lockHours * 3600;
        if (elapsed > maxSeconds) elapsed = maxSeconds;

        const dailyProfit = (amount * percent) / 100;
        const profitPerSecond = dailyProfit / 86400;
        let currentProfit = profitPerSecond * elapsed;

        if (currentProfit < 0) currentProfit = 0;

        currentProfit = Number(currentProfit.toFixed(2));

        el.innerText = formatMoney(currentProfit);

        activeTotal += amount + currentProfit;

        if (elapsed >= maxSeconds) {
            shouldReload = true;
        }
    });

    if (totalCard) {
        totalCard.innerText = formatMoney(closedTotal + activeTotal);
    }

    if (shouldReload) {
        setTimeout(function(){
            window.location.reload();
        }, 1200);
    }
}

setInterval(function(){

    document.querySelectorAll('[data-unlock]').forEach(function(el){
        let unlock = parseInt(el.getAttribute('data-unlock'));
        let now = Math.floor(Date.now()/1000);

        let seconds = unlock - now;
        if(seconds < 0) seconds = 0;

        let h = Math.floor(seconds/3600);
        let m = Math.floor((seconds%3600)/60);
        let s = seconds%60;

        el.innerText = h + "h " + m + "m " + s + "s";

        if (seconds === 0) {
            setTimeout(function(){
                window.location.reload();
            }, 1200);
        }
    });

    document.querySelectorAll('[data-redeem]').forEach(function(el){
        let redeem = parseInt(el.getAttribute('data-redeem'));
        let now = Math.floor(Date.now()/1000);

        let seconds = redeem - now;
        if(seconds < 0) seconds = 0;

        let h = Math.floor(seconds/3600);
        let m = Math.floor((seconds%3600)/60);
        let s = seconds%60;

        el.innerText = h + "h " + m + "m " + s + "s";

        if (seconds === 0) {
            setTimeout(function(){
                window.location.reload();
            }, 1200);
        }
    });

    updateLiveProfit();

}, 1000);

updateLiveProfit();
</script>

@endsection