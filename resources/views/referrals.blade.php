@extends('layouts.app')

@section('content')

<style>
    .ref-page{
        max-width:620px;
        margin:0 auto;
        padding:18px 0 130px;
        display:flex;
        flex-direction:column;
        gap:16px;
    }

    .ref-panel,
    .hero-card{
        border-radius:28px;
        border:1px solid rgba(203,255,71,0.08);
        background:
            radial-gradient(circle at 85% 10%, rgba(201,255,84,0.10) 0%, rgba(201,255,84,0) 28%),
            radial-gradient(circle at 15% 100%, rgba(156,255,84,0.05) 0%, rgba(156,255,84,0) 28%),
            linear-gradient(180deg, rgba(18,22,19,0.98) 0%, rgba(11,14,12,0.98) 100%);
        box-shadow:0 18px 42px rgba(0,0,0,0.28);
        overflow:hidden;
    }

    .hero-card{
        padding:22px 18px 18px;
        position:relative;
    }

    .hero-card::after{
        content:"";
        position:absolute;
        width:220px;
        height:220px;
        right:-70px;
        top:-70px;
        border-radius:50%;
        background:radial-gradient(circle, rgba(207,255,88,0.09) 0%, rgba(207,255,88,0) 70%);
        pointer-events:none;
    }

    .hero-top{
        position:relative;
        z-index:2;
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        margin-bottom:16px;
    }

    .hero-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 14px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
        letter-spacing:.8px;
        text-transform:uppercase;
        color:#10140f;
        background:linear-gradient(180deg,#eaff9a 0%, #cbff47 100%);
        box-shadow:0 10px 20px rgba(203,255,71,0.18);
        margin-bottom:10px;
    }

    .hero-title{
        color:#f5f9f3;
        font-size:24px;
        font-weight:900;
        line-height:1.25;
        margin-bottom:6px;
    }

    .hero-sub{
        color:#96a096;
        font-size:13px;
        line-height:1.8;
        max-width:360px;
    }

    .hero-reward{
        text-align:right;
        position:relative;
        z-index:2;
    }

    .hero-reward-label{
        color:#8d988c;
        font-size:11px;
        font-weight:800;
        text-transform:uppercase;
        letter-spacing:1px;
        margin-bottom:6px;
    }

    .hero-reward-value{
        color:#f8fff2;
        font-size:30px;
        font-weight:900;
        line-height:1.1;
        margin-bottom:4px;
    }

    .hero-reward-sub{
        color:#9ca79d;
        font-size:11px;
        line-height:1.6;
    }

    .link-box{
        position:relative;
        z-index:2;
        margin-top:10px;
        padding:14px;
        border-radius:20px;
        background:rgba(255,255,255,0.03);
        border:1px solid rgba(203,255,71,0.08);
    }

    .link-label{
        color:#909a91;
        font-size:11px;
        font-weight:800;
        letter-spacing:1px;
        text-transform:uppercase;
        margin-bottom:8px;
    }

    .link-row{
        display:flex;
        gap:10px;
        align-items:center;
    }

    .link-value{
        flex:1;
        min-width:0;
        padding:13px 14px;
        border-radius:16px;
        background:#0d120f;
        border:1px solid rgba(255,255,255,0.06);
        color:#f6fbf4;
        font-size:13px;
        font-weight:800;
        line-height:1.6;
        word-break:break-all;
    }

    .copy-btn{
        border:none;
        cursor:pointer;
        min-width:116px;
        padding:13px 16px;
        border-radius:16px;
        font-size:12px;
        font-weight:900;
        color:#10140f;
        background:linear-gradient(180deg,#eaff9a 0%, #cbff47 100%);
        box-shadow:0 12px 22px rgba(203,255,71,0.16);
        transition:.2s ease;
    }

    .copy-btn:hover{
        transform:translateY(-1px);
        box-shadow:0 14px 24px rgba(203,255,71,0.20);
    }

    .copy-btn.copied{
        background:linear-gradient(180deg,#d6ff83 0%, #9be12f 100%);
    }

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:12px;
    }

    .stat-card{
        padding:16px;
        border-radius:22px;
        border:1px solid rgba(203,255,71,0.07);
        background:
            linear-gradient(180deg, rgba(19,24,21,0.98) 0%, rgba(12,16,14,0.98) 100%);
        box-shadow:0 14px 28px rgba(0,0,0,0.22);
    }

    .stat-label{
        color:#8d978d;
        font-size:10px;
        font-weight:800;
        text-transform:uppercase;
        letter-spacing:1px;
        margin-bottom:8px;
        line-height:1.5;
    }

    .stat-value{
        color:#f6fbf4;
        font-size:23px;
        font-weight:900;
        line-height:1.1;
    }

    .panel-head{
        padding:18px 18px 10px;
    }

    .panel-title{
        color:#f6fbf4;
        font-size:20px;
        font-weight:900;
        margin-bottom:6px;
    }

    .panel-sub{
        color:#919d93;
        font-size:12px;
        line-height:1.75;
    }

    .invite-list{
        padding:0 18px 18px;
        display:flex;
        flex-direction:column;
        gap:10px;
    }

    .invite-row{
        display:grid;
        grid-template-columns:1.1fr 1fr .8fr .8fr .8fr;
        gap:10px;
        align-items:center;
        padding:14px;
        border-radius:18px;
        background:rgba(255,255,255,0.03);
        border:1px solid rgba(255,255,255,0.05);
    }

    .invite-label{
        color:#808b82;
        font-size:10px;
        font-weight:800;
        text-transform:uppercase;
        letter-spacing:1px;
        margin-bottom:5px;
    }

    .invite-value{
        color:#f5faf3;
        font-size:13px;
        font-weight:800;
        line-height:1.5;
        word-break:break-word;
    }

    .yes-badge,
    .no-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:58px;
        padding:8px 10px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
    }

    .yes-badge{
        background:rgba(203,255,71,0.11);
        border:1px solid rgba(203,255,71,0.14);
        color:#dfff8f;
    }

    .no-badge{
        background:rgba(255,255,255,0.04);
        border:1px solid rgba(255,255,255,0.06);
        color:#d4ddd5;
    }

    .levels-wrap{
        padding:0 18px 18px;
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .level-card{
        position:relative;
        overflow:hidden;
        padding:16px;
        border-radius:22px;
        border:1px solid rgba(203,255,71,0.08);
        background:
            radial-gradient(circle at 88% 15%, rgba(203,255,71,0.08) 0%, rgba(203,255,71,0) 32%),
            linear-gradient(180deg, rgba(18,22,19,0.98) 0%, rgba(10,13,11,0.98) 100%);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
    }

    .level-left{
        min-width:0;
    }

    .level-top{
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:8px;
        flex-wrap:wrap;
    }

    .level-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:7px 12px;
        border-radius:999px;
        color:#10140f;
        background:linear-gradient(180deg,#eaff9a 0%, #cbff47 100%);
        font-size:11px;
        font-weight:900;
        text-transform:uppercase;
        letter-spacing:.8px;
    }

    .level-need{
        color:#aab4ab;
        font-size:12px;
        font-weight:800;
    }

    .level-title{
        color:#f8fcf6;
        font-size:18px;
        font-weight:900;
        margin-bottom:6px;
    }

    .level-sub{
        color:#97a197;
        font-size:12px;
        line-height:1.75;
    }

    .level-right{
        text-align:right;
        flex:0 0 auto;
    }

    .level-money{
        color:#f9fff4;
        font-size:24px;
        font-weight:900;
        line-height:1.1;
        margin-bottom:6px;
    }

    .level-profit{
        color:#d8ff86;
        font-size:12px;
        font-weight:900;
        margin-bottom:8px;
    }

    .level-status{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 12px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
    }

    .level-status.unlocked{
        background:rgba(203,255,71,0.12);
        border:1px solid rgba(203,255,71,0.14);
        color:#dfff8f;
    }

    .level-status.pending{
        background:rgba(255,255,255,0.04);
        border:1px solid rgba(255,255,255,0.06);
        color:#d5ddd5;
    }

    .level-status.agent{
        background:rgba(255,221,87,0.10);
        border:1px solid rgba(255,221,87,0.14);
        color:#ffe680;
    }

    .empty-box{
        margin:0 18px 18px;
        padding:16px;
        border-radius:18px;
        background:rgba(255,255,255,0.03);
        border:1px solid rgba(255,255,255,0.06);
        color:#9aa59b;
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
        background:rgba(203,255,71,0.10);
        color:#ddff93;
        border:1px solid rgba(203,255,71,0.13);
    }

    .alert.error{
        background:rgba(255,90,112,0.10);
        color:#ffc0ca;
        border:1px solid rgba(255,90,112,0.13);
    }

    .copy-toast{
        position:fixed;
        left:50%;
        top:24px;
        transform:translateX(-50%) translateY(-15px);
        background:linear-gradient(180deg,#eaff9a 0%, #cbff47 100%);
        color:#10140f;
        font-size:13px;
        font-weight:900;
        padding:14px 18px;
        border-radius:16px;
        box-shadow:0 16px 30px rgba(0,0,0,0.26);
        opacity:0;
        pointer-events:none;
        transition:.25s ease;
        z-index:999999;
    }

    .copy-toast.show{
        opacity:1;
        transform:translateX(-50%) translateY(0);
    }

    @media (max-width:720px){
        .hero-top{
            flex-direction:column;
        }

        .hero-reward{
            text-align:left;
        }

        .stats-grid{
            grid-template-columns:1fr 1fr;
        }

        .invite-row{
            grid-template-columns:1fr;
        }

        .link-row{
            flex-direction:column;
            align-items:stretch;
        }

        .level-card{
            flex-direction:column;
            align-items:flex-start;
        }

        .level-right{
            text-align:left;
        }
    }
</style>

<div id="copyToast" class="copy-toast">Referral link copied successfully</div>

<div class="ref-page">

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="hero-card">
        <div class="hero-top">
            <div>
                <div class="hero-badge">Invite & Earn</div>
                <div class="hero-title">Grow Your Team</div>
                <div class="hero-sub">
                    ادعُ أصدقاءك إلى DawnEX، وراقب حالة الدعوات المؤهلة، وارتقِ عبر مستويات المكافآت حتى تصل إلى مستوى الوكيل داخل المنصة.
                </div>
            </div>

            <div class="hero-reward">
                <div class="hero-reward-label">Current Bonus</div>
                <div class="hero-reward-value">${{ number_format($rewardAmount ?? 0, 2) }}</div>
                <div class="hero-reward-sub">
                    Referral reward based on qualified users
                </div>
            </div>
        </div>

        <div class="link-box">
            <div class="link-label">Your Referral Link</div>
            <div class="link-row">
                <div class="link-value" id="referralLinkText">{{ $referralLink }}</div>
                <button type="button" class="copy-btn" id="copyButton" onclick="copyReferralLink()">Copy Link</button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Invites</div>
            <div class="stat-value">{{ $totalInvites }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Verified</div>
            <div class="stat-value">{{ $verifiedCount }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Deposited</div>
            <div class="stat-value">{{ $depositedCount }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Qualified</div>
            <div class="stat-value">{{ $qualifiedCount }}</div>
        </div>
    </div>

    <div class="ref-panel">
        <div class="panel-head">
            <div class="panel-title">Referral History</div>
            <div class="panel-sub">
                يظهر فقط رقم الحساب، تاريخ التسجيل، وحالة التحقق والإيداع والتأهل. لا يوجد اسم ولا إيميل، لأن بعض البشر لا يعرفون حدودهم.
            </div>
        </div>

        @if(isset($referralRows) && count($referralRows) > 0)
            <div class="invite-list">
                @foreach($referralRows as $row)
                    <div class="invite-row">
                        <div>
                            <div class="invite-label">Account ID</div>
                            <div class="invite-value">#{{ $row->account_id }}</div>
                        </div>

                        <div>
                            <div class="invite-label">Joined</div>
                            <div class="invite-value">{{ \Carbon\Carbon::parse($row->joined_at)->format('Y-m-d') }}</div>
                        </div>

                        <div>
                            <div class="invite-label">Verified</div>
                            {!! $row->verified === 'Yes' ? '<span class="yes-badge">Yes</span>' : '<span class="no-badge">No</span>' !!}
                        </div>

                        <div>
                            <div class="invite-label">Deposit</div>
                            {!! $row->deposited === 'Yes' ? '<span class="yes-badge">Yes</span>' : '<span class="no-badge">No</span>' !!}
                        </div>

                        <div>
                            <div class="invite-label">Qualified</div>
                            {!! $row->qualified === 'Yes' ? '<span class="yes-badge">Yes</span>' : '<span class="no-badge">No</span>' !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box">
                لا توجد دعوات حتى الآن. شارك الرابط الخاص بك، ودع الناس يأتون إلى المنصة بدل أن تبقى الصفحة فارغة بهذا الشكل الكئيب.
            </div>
        @endif
    </div>

    <div class="ref-panel">
        <div class="panel-head">
            <div class="panel-title">Referral Reward Levels</div>
            <div class="panel-sub">
                كل مستوى يفتح مكافأة مالية إضافية مع زيادة على أرباحك، والمستوى الثالث يمنحك صفة وكيل داخل المنصة.
            </div>
        </div>

        <div class="levels-wrap">

            <div class="level-card">
                <div class="level-left">
                    <div class="level-top">
                        <div class="level-badge">Level 1</div>
                        <div class="level-need">Requires 5 Qualified Referrals</div>
                    </div>
                    <div class="level-title">Starter Partner Reward</div>
                    <div class="level-sub">
                        احصل على مكافأة ثابتة ورفع بسيط على نسبة أرباحك العادية عندما تصل إلى أول مستوى مؤهل.
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-money">$500</div>
                    <div class="level-profit">+1% Profit Boost</div>
                    <div class="level-status {{ ($qualifiedCount ?? 0) >= 5 ? 'unlocked' : 'pending' }}">
                        {{ ($qualifiedCount ?? 0) >= 5 ? 'Unlocked' : 'Pending' }}
                    </div>
                </div>
            </div>

            <div class="level-card">
                <div class="level-left">
                    <div class="level-top">
                        <div class="level-badge">Level 2</div>
                        <div class="level-need">Requires 15 Qualified Referrals</div>
                    </div>
                    <div class="level-title">Advanced Growth Reward</div>
                    <div class="level-sub">
                        مستوى أقوى لمستخدمين جادين في توسيع الشبكة، مع مكافأة أعلى وزيادة أوضح على الأرباح.
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-money">$2,000</div>
                    <div class="level-profit">+2.5% Profit Boost</div>
                    <div class="level-status {{ ($qualifiedCount ?? 0) >= 15 ? 'unlocked' : 'pending' }}">
                        {{ ($qualifiedCount ?? 0) >= 15 ? 'Unlocked' : 'Pending' }}
                    </div>
                </div>
            </div>

            <div class="level-card">
                <div class="level-left">
                    <div class="level-top">
                        <div class="level-badge">Level 3</div>
                        <div class="level-need">Requires 30 Qualified Referrals</div>
                    </div>
                    <div class="level-title">Agent Status</div>
                    <div class="level-sub">
                        عند الوصول إلى هذا المستوى تصبح وكيلًا داخل المنصة، ويمكن ترقيتك من الإدارة للحصول على تعليمات خاصة وأرباح أسبوعية حسب النظام الداخلي.
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-money">$4,000</div>
                    <div class="level-profit">+3.2% Profit Boost</div>
                    <div class="level-status {{ ($qualifiedCount ?? 0) >= 30 ? 'agent' : 'pending' }}">
                        {{ ($qualifiedCount ?? 0) >= 30 ? 'Agent Unlocked' : 'Pending' }}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    function copyReferralLink() {
        const link = document.getElementById('referralLinkText').innerText;
        const button = document.getElementById('copyButton');
        const toast = document.getElementById('copyToast');

        navigator.clipboard.writeText(link).then(function () {
            button.innerText = 'Copied';
            button.classList.add('copied');
            toast.classList.add('show');

            setTimeout(() => {
                button.innerText = 'Copy Link';
                button.classList.remove('copied');
                toast.classList.remove('show');
            }, 2200);
        }).catch(function () {
            toast.innerText = 'Could not copy referral link';
            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
                toast.innerText = 'Referral link copied successfully';
            }, 2200);
        });
    }
</script>

@endsection