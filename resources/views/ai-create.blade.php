@extends('layouts.app')

@section('content')

@php
    $wallet = $wallet ?? \App\Models\Wallet::where('user_name', auth()->user()->name)->first();
    $balanceValue = (float) ($wallet->balance ?? 0);
@endphp

<style>
.ai-create-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.ai-create-card{
    border-radius:28px;
    padding:20px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.ai-create-title{
    font-size:28px;
    font-weight:900;
    color:#f4fff8;
    margin-bottom:8px;
    text-align:center;
}

.ai-create-sub{
    font-size:14px;
    color:#9fb4a4;
    line-height:1.8;
    margin-bottom:18px;
    text-align:center;
}

.ai-balance{
    padding:14px 16px;
    border-radius:18px;
    background:rgba(184,255,59,0.08);
    border:1px solid rgba(184,255,59,0.10);
    color:#dfffc3;
    font-size:14px;
    font-weight:700;
    margin-bottom:16px;
    text-align:center;
}

.ai-form{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.ai-label{
    font-size:13px;
    color:#8da095;
    margin-bottom:8px;
    display:block;
}

.ai-input{
    width:100%;
    border:none;
    outline:none;
    border-radius:18px;
    padding:15px 16px;
    background:#141f18;
    color:#f4fff8;
    font-size:16px;
    border:1px solid rgba(184,255,59,0.08);
}

.ai-percent-row{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:10px;
}

.ai-percent-btn{
    border:none;
    border-radius:14px;
    padding:12px 10px;
    background:#141f18;
    border:1px solid rgba(184,255,59,0.08);
    color:#f4fff8;
    font-size:13px;
    font-weight:800;
    cursor:pointer;
    transition:.2s ease;
}

.ai-percent-btn:hover,
.ai-percent-btn.active{
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
}

.ai-plans{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;
}

.ai-plan{
    position:relative;
    border:none;
    border-radius:22px;
    padding:16px 12px;
    background:#141f18;
    border:1px solid rgba(184,255,59,0.08);
    color:#f4fff8;
    text-align:center;
    cursor:pointer;
    transition:.2s ease;
}

.ai-plan:hover{
    transform:translateY(-2px);
    border-color:rgba(184,255,59,0.18);
}

.ai-plan.active{
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
}

.ai-plan-rate{
    font-size:18px;
    font-weight:900;
    margin-bottom:4px;
}

.ai-plan-time{
    font-size:13px;
    opacity:.9;
}

.ai-create-btn{
    border:none;
    border-radius:20px;
    padding:16px;
    font-size:16px;
    font-weight:900;
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
    cursor:pointer;
}

.ai-create-btn:disabled{
    opacity:.6;
    cursor:not-allowed;
}

.ai-error{
    padding:14px 16px;
    border-radius:18px;
    font-size:14px;
    font-weight:700;
    background:rgba(255,95,116,0.10);
    border:1px solid rgba(255,95,116,0.18);
    color:#ffd3da;
}

.ai-overlay{
    position:fixed;
    inset:0;
    background:rgba(5,10,7,0.92);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    padding:20px;
}

.ai-overlay-box{
    width:100%;
    max-width:500px;
    border-radius:28px;
    padding:30px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.14);
    box-shadow:0 30px 70px rgba(0,0,0,0.45);
}

.ai-overlay-title{
    font-size:24px;
    font-weight:900;
    color:#f4fff8;
    margin-bottom:18px;
    text-align:center;
}

.ai-steps{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.ai-step{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    color:#9fb4a4;
    font-size:15px;
    font-weight:800;
    opacity:.45;
    transition:.3s ease;
}

.ai-step-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.ai-step-spinner{
    width:16px;
    height:16px;
    border:2px solid rgba(184,255,59,0.18);
    border-top:2px solid #b8ff3b;
    border-radius:50%;
    display:none;
    animation:spin .8s linear infinite;
}

.ai-step-check{
    display:none;
    font-size:18px;
    font-weight:900;
    color:#62ff9a;
}

.ai-step.active{
    opacity:1;
    color:#f4fff8;
}

.ai-step.active .ai-step-spinner{
    display:inline-block;
}

.ai-step.done{
    opacity:1;
    color:#dfffc3;
}

.ai-step.done .ai-step-spinner{
    display:none;
}

.ai-step.done .ai-step-check{
    display:inline-block;
}

.ai-step.success{
    margin-top:10px;
    padding-top:16px;
    border-top:1px solid rgba(184,255,59,0.10);
    color:#62ff9a;
    font-size:18px;
    font-weight:900;
    display:none;
    justify-content:center;
    gap:10px;
}

.ai-step.success.show{
    display:flex;
}

.success-ring{
    width:24px;
    height:24px;
    border-radius:50%;
    border:2px solid #62ff9a;
    display:flex;
    align-items:center;
    justify-content:center;
    animation:successPulse 1s ease infinite;
}

@keyframes spin{
    from{transform:rotate(0deg);}
    to{transform:rotate(360deg);}
}

@keyframes successPulse{
    0%{box-shadow:0 0 0 0 rgba(98,255,154,0.45);}
    70%{box-shadow:0 0 0 12px rgba(98,255,154,0);}
    100%{box-shadow:0 0 0 0 rgba(98,255,154,0);}
}

@media (max-width:700px){
    .ai-plans{
        grid-template-columns:1fr;
    }

    .ai-percent-row{
        grid-template-columns:repeat(2,1fr);
    }
}
</style>

<div class="ai-create-page">

    @if(session('error'))
        <div class="ai-error">{{ session('error') }}</div>
    @endif

    <div class="ai-create-card">
        <div class="ai-create-title">Create AI Strategy</div>
        <div class="ai-create-sub">
            أدخل المبلغ، اختر الخطة المناسبة، وسيبدأ النظام بإنشاء الصفقة مباشرة.
        </div>

        <div class="ai-balance">
            Available Balance: ${{ number_format($balanceValue, 2) }}
        </div>

        <form id="aiCreateForm" class="ai-form" method="POST" action="/ai/store">
            @csrf

            <input type="hidden" id="availableBalanceValue" value="{{ $balanceValue }}">
            <input type="hidden" name="strategy" id="strategyInput">

            <div>
                <label class="ai-label">Amount</label>
                <input
                    class="ai-input"
                    type="number"
                    step="0.01"
                    min="1000"
                    name="amount"
                    id="amountInput"
                    placeholder="Enter amount or use % buttons"
                    required
                >
            </div>

            <div class="ai-percent-row">
                <button type="button" class="ai-percent-btn" onclick="selectPercent('max', this)">MAX</button>
                <button type="button" class="ai-percent-btn" onclick="selectPercent('75', this)">75%</button>
                <button type="button" class="ai-percent-btn" onclick="selectPercent('50', this)">50%</button>
                <button type="button" class="ai-percent-btn" onclick="selectPercent('25', this)">25%</button>
            </div>

            <div>
                <label class="ai-label">Choose Strategy</label>

                <div class="ai-plans">
                    <button type="button" class="ai-plan" onclick="selectPlan(this, 'advanced')">
                        <div class="ai-plan-rate">3%</div>
                        <div class="ai-plan-time">Days 24</div>
                    </button>

                    <button type="button" class="ai-plan" onclick="selectPlan(this, 'pro')">
                        <div class="ai-plan-rate">1.8%</div>
                        <div class="ai-plan-time">Days 9</div>
                    </button>

                    <button type="button" class="ai-plan" onclick="selectPlan(this, 'starter')">
                        <div class="ai-plan-rate">1.2%</div>
                        <div class="ai-plan-time">Days 3</div>
                    </button>
                </div>
            </div>

            <button type="submit" id="createBtn" class="ai-create-btn">
                Create AI Strategy
            </button>
        </form>
    </div>
</div>

<div id="aiOverlay" class="ai-overlay">
    <div class="ai-overlay-box">
        <div class="ai-overlay-title">Initializing AI Strategy</div>

        <div class="ai-steps">
            <div class="ai-step" id="step1">
                <div class="ai-step-left">
                    <span class="ai-step-spinner"></span>
                    <span id="searchingText">Searching market...</span>
                </div>
                <span class="ai-step-check">✔</span>
            </div>

            <div class="ai-step" id="step2">
                <div class="ai-step-left">
                    <span class="ai-step-spinner"></span>
                    <span id="pairFoundText">Pair found</span>
                </div>
                <span class="ai-step-check">✔</span>
            </div>

            <div class="ai-step" id="step3">
                <div class="ai-step-left">
                    <span class="ai-step-spinner"></span>
                    <span>Scanning liquidity</span>
                </div>
                <span class="ai-step-check">✔</span>
            </div>

            <div class="ai-step" id="step4">
                <div class="ai-step-left">
                    <span class="ai-step-spinner"></span>
                    <span>AI analysis</span>
                </div>
                <span class="ai-step-check">✔</span>
            </div>

            <div class="ai-step" id="step5">
                <div class="ai-step-left">
                    <span class="ai-step-spinner"></span>
                    <span>Finalizing order</span>
                </div>
                <span class="ai-step-check">✔</span>
            </div>

            <div class="ai-step success" id="successStep">
                <span class="success-ring">✔</span>
                <span>Successfully created</span>
            </div>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('aiCreateForm');
const createBtn = document.getElementById('createBtn');
const strategyInput = document.getElementById('strategyInput');
const amountInput = document.getElementById('amountInput');
const overlay = document.getElementById('aiOverlay');
let searchInterval = null;

function selectPercent(percent, button){
    document.querySelectorAll('.ai-percent-btn').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');

    const balance = parseFloat(document.getElementById('availableBalanceValue').value || 0);
    if (!balance || balance <= 0) return;

    let amount = 0;

    if (percent === 'max') {
        amount = balance;
    } else {
        amount = balance * (parseInt(percent) / 100);
    }

    amountInput.value = amount.toFixed(2);
}

function selectPlan(button, strategyValue){
    document.querySelectorAll('.ai-plan').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    strategyInput.value = strategyValue;
}

function randomPair(){
    const pairs = ['BTC/USDT','ETH/USDT','SOL/USDT','XRP/USDT','ADA/USDT','LINK/USDT'];
    return pairs[Math.floor(Math.random() * pairs.length)];
}

function startSearchingAnimation(finalPair){
    const searchingText = document.getElementById('searchingText');
    const pairs = ['BTC/USDT','ETH/USDT','SOL/USDT','XRP/USDT','ADA/USDT','LINK/USDT'];
    let index = 0;

    searchInterval = setInterval(function(){
        searchingText.innerText = 'Searching market... ' + pairs[index];
        index++;
        if(index >= pairs.length){
            index = 0;
        }
    }, 900);

    setTimeout(function(){
        clearInterval(searchInterval);
        searchingText.innerText = 'Searching market...';
        document.getElementById('pairFoundText').innerText = 'Pair found: ' + finalPair;
    }, 25000);
}

function runCreateAnimationAndSubmit(e){
    e.preventDefault();

    const amount = parseFloat(amountInput.value || 0);
    const strategy = strategyInput.value;

    if (!amount || amount <= 0) {
        alert('Please enter amount');
        return;
    }

    if (amount < 1000) {
        alert('Minimum AI investment is $1000');
        return;
    }

    if (!strategy) {
        alert('Please choose strategy');
        return;
    }

    createBtn.disabled = true;
    overlay.style.display = 'flex';

    const selectedPair = randomPair();
    startSearchingAnimation(selectedPair);

    const steps = [
        { el: document.getElementById('step1'), duration: 25000 },
        { el: document.getElementById('step2'), duration: 15000 },
        { el: document.getElementById('step3'), duration: 25000 },
        { el: document.getElementById('step4'), duration: 25000 },
        { el: document.getElementById('step5'), duration: 20000 }
    ];

    const successStep = document.getElementById('successStep');
    let index = 0;

    function nextStep(){
        if (index > 0) {
            steps[index - 1].el.classList.remove('active');
            steps[index - 1].el.classList.add('done');
        }

        if (index < steps.length) {
            steps[index].el.classList.add('active');
            const duration = steps[index].duration;
            index++;
            setTimeout(nextStep, duration);
        } else {
            successStep.classList.add('show');
            setTimeout(function(){
                form.submit();
            }, 2500);
        }
    }

    nextStep();
}

form.addEventListener('submit', runCreateAnimationAndSubmit);
</script>

@endsection