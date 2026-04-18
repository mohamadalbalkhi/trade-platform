<nav class="dx-topbar-wrap">
    <style>
        .dx-topbar-wrap{
            width:100%;
            display:flex;
            justify-content:center;
            padding:18px 14px 10px;
            background:transparent;
        }

        .dx-topbar{
            width:100%;
            max-width:430px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
        }

        .dx-topbar-side{
            display:flex;
            align-items:center;
            gap:10px;
            min-width:0;
        }

        .dx-pill{
            height:38px;
            padding:0 16px;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            font-size:14px;
            font-weight:800;
            color:#f5f7f8;
            background:rgba(255,255,255,0.06);
            border:1px solid rgba(255,255,255,0.08);
            box-shadow:inset 0 1px 0 rgba(255,255,255,0.03);
            transition:.18s ease;
            white-space:nowrap;
        }

        .dx-pill:hover{
            transform:translateY(-1px);
            border-color:rgba(184,255,59,0.16);
            color:#ffffff;
            background:rgba(184,255,59,0.05);
        }

        .dx-pill.active{
            color:#dfff7a;
            border-color:rgba(184,255,59,0.20);
            background:rgba(184,255,59,0.08);
            box-shadow:0 0 16px rgba(184,255,59,0.08);
        }

        .dx-brand{
            flex:1 1 auto;
            min-width:0;
            text-align:center;
            text-decoration:none;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:2px;
            padding:0 8px;
        }

        .dx-brand-title{
            font-size:20px;
            line-height:1;
            font-weight:900;
            letter-spacing:.3px;
            color:#d7ff4f;
            text-shadow:0 0 14px rgba(184,255,59,0.20);
        }

        .dx-brand-sub{
            font-size:10px;
            line-height:1;
            font-weight:800;
            letter-spacing:2px;
            color:#cfd7cf;
            text-transform:uppercase;
            opacity:.92;
        }

        .dx-account{
            position:relative;
        }

        .dx-account-btn{
            height:38px;
            padding:0 16px;
            border:none;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            font-size:14px;
            font-weight:800;
            color:#f5f7f8;
            background:rgba(255,255,255,0.06);
            border:1px solid rgba(255,255,255,0.08);
            box-shadow:inset 0 1px 0 rgba(255,255,255,0.03);
            transition:.18s ease;
            white-space:nowrap;
        }

        .dx-account-btn:hover{
            transform:translateY(-1px);
            border-color:rgba(184,255,59,0.16);
            background:rgba(184,255,59,0.05);
        }

        .dx-account-menu{
            position:absolute;
            top:48px;
            right:0;
            min-width:190px;
            padding:10px;
            border-radius:18px;
            background:linear-gradient(180deg, rgba(17,23,19,0.98) 0%, rgba(10,14,11,0.98) 100%);
            border:1px solid rgba(184,255,59,0.12);
            box-shadow:0 16px 34px rgba(0,0,0,0.28);
            z-index:999;
            display:none;
        }

        .dx-account.open .dx-account-menu{
            display:block;
        }

        .dx-user-box{
            padding:10px 12px 12px;
            border-radius:14px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.05);
            margin-bottom:8px;
        }

        .dx-user-name{
            color:#f6fff9;
            font-size:14px;
            font-weight:800;
            line-height:1.4;
            margin-bottom:3px;
            word-break:break-word;
        }

        .dx-user-email{
            color:#9eaca0;
            font-size:12px;
            line-height:1.5;
            word-break:break-word;
        }

        .dx-menu-link,
        .dx-menu-submit{
            width:100%;
            text-align:left;
            text-decoration:none;
            border:none;
            cursor:pointer;
            display:flex;
            align-items:center;
            padding:12px 12px;
            border-radius:12px;
            background:transparent;
            color:#eef7ee;
            font-size:13px;
            font-weight:800;
            transition:.16s ease;
        }

        .dx-menu-link:hover,
        .dx-menu-submit:hover{
            background:rgba(184,255,59,0.06);
            color:#dfff7a;
        }

        .dx-mobile{
            display:none;
        }

        @media (max-width: 760px){
            .dx-topbar{
                max-width:430px;
                flex-wrap:wrap;
                justify-content:center;
                gap:10px 8px;
            }

            .dx-topbar-side{
                display:none;
            }

            .dx-account{
                display:none;
            }

            .dx-brand{
                order:1;
                width:100%;
                margin-bottom:4px;
            }

            .dx-mobile{
                order:2;
                display:flex;
                align-items:center;
                justify-content:center;
                gap:8px;
                width:100%;
            }

            .dx-pill{
                height:36px;
                padding:0 14px;
                font-size:13px;
            }

            .dx-brand-title{
                font-size:18px;
            }

            .dx-brand-sub{
                font-size:9px;
                letter-spacing:1.8px;
            }
        }
    </style>

    <div class="dx-topbar">
        <div class="dx-topbar-side">
            <a href="{{ url('/home') }}" class="dx-pill {{ request()->is('home') ? 'active' : '' }}">
                Home
            </a>

            <a href="{{ url('/wallet') }}" class="dx-pill {{ request()->is('wallet') ? 'active' : '' }}">
                Assets
            </a>
        </div>

        <a href="{{ url('/home') }}" class="dx-brand">
            <div class="dx-brand-title">DawnEX</div>
            <div class="dx-brand-sub">AI TRADING PLATFORM</div>
        </a>

        <div class="dx-account" id="dxAccountMenu">
            <button type="button" class="dx-account-btn" onclick="toggleDxAccountMenu()">
                Account
            </button>

            <div class="dx-account-menu">
                <div class="dx-user-box">
                    <div class="dx-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="dx-user-email">{{ Auth::user()->email ?? '' }}</div>
                </div>

                <a href="{{ url('/profile') }}" class="dx-menu-link">Profile</a>
                <a href="{{ url('/support') }}" class="dx-menu-link">Support</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dx-menu-submit">Log Out</button>
                </form>
            </div>
        </div>

        <div class="dx-mobile">
            <a href="{{ url('/home') }}" class="dx-pill {{ request()->is('home') ? 'active' : '' }}">
                Home
            </a>

            <a href="{{ url('/wallet') }}" class="dx-pill {{ request()->is('wallet') ? 'active' : '' }}">
                Assets
            </a>

            <a href="{{ url('/profile') }}" class="dx-pill {{ request()->is('profile') ? 'active' : '' }}">
                Account
            </a>
        </div>
    </div>

    <script>
        function toggleDxAccountMenu() {
            const menu = document.getElementById('dxAccountMenu');
            if (!menu) return;
            menu.classList.toggle('open');
        }

        document.addEventListener('click', function (e) {
            const menu = document.getElementById('dxAccountMenu');
            if (!menu) return;
            if (!menu.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>
</nav>