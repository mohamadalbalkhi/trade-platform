<x-guest-layout>
    <style>
        .dx-auth-wrap{
            width:100%;
            max-width:440px;
            margin:0 auto;
        }

        .dx-auth-card{
            position:relative;
            overflow:hidden;
            border-radius:32px;
            padding:22px 22px 20px;
            background:
                linear-gradient(135deg, rgba(5,10,7,0.78) 0%, rgba(5,10,7,0.90) 100%),
                radial-gradient(circle at 84% 18%, rgba(184,255,59,0.14) 0%, rgba(184,255,59,0) 28%),
                radial-gradient(circle at 14% 100%, rgba(98,255,154,0.06) 0%, rgba(98,255,154,0) 28%),
                linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
            border:1px solid rgba(184,255,59,0.12);
            box-shadow:
                0 20px 44px rgba(0,0,0,0.34),
                inset 0 1px 0 rgba(255,255,255,0.03);
            backdrop-filter:blur(10px);
            -webkit-backdrop-filter:blur(10px);
        }

        .dx-auth-card::before{
            content:"";
            position:absolute;
            right:-70px;
            top:-70px;
            width:220px;
            height:220px;
            border-radius:50%;
            background:radial-gradient(circle, rgba(184,255,59,0.09) 0%, rgba(184,255,59,0) 72%);
            pointer-events:none;
        }

        .dx-auth-card::after{
            content:"";
            position:absolute;
            left:-80px;
            bottom:-80px;
            width:220px;
            height:220px;
            border-radius:50%;
            background:radial-gradient(circle, rgba(98,255,154,0.07) 0%, rgba(98,255,154,0) 72%);
            pointer-events:none;
        }

        .dx-auth-inner{
            position:relative;
            z-index:2;
        }

        .dx-logo-wrap{
            display:flex;
            justify-content:center;
            margin-bottom:14px;
        }

        .dx-logo-box{
            width:120px;
            height:120px;
            border-radius:28px;
            padding:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:
                linear-gradient(145deg, rgba(13,23,17,0.96) 0%, rgba(9,16,12,0.98) 100%);
            border:1px solid rgba(184,255,59,0.18);
            box-shadow:
                0 0 18px rgba(184,255,59,0.18),
                0 0 40px rgba(184,255,59,0.08),
                inset 0 0 14px rgba(184,255,59,0.04);
        }

        .dx-logo{
            width:100%;
            height:100%;
            object-fit:contain;
            filter:
                drop-shadow(0 0 10px rgba(184,255,59,0.20))
                drop-shadow(0 0 18px rgba(184,255,59,0.14));
        }

        .dx-brand{
            text-align:center;
            margin-bottom:14px;
        }

        .dx-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:7px 12px;
            border-radius:999px;
            margin-bottom:10px;
            background:rgba(184,255,59,0.08);
            border:1px solid rgba(184,255,59,0.14);
            color:#e2ffb4;
            font-size:10px;
            font-weight:900;
            letter-spacing:1px;
            text-transform:uppercase;
        }

        .dx-brand-title{
            color:#f8fff9;
            font-size:28px;
            font-weight:900;
            line-height:1.1;
            margin-bottom:8px;
            letter-spacing:-0.3px;
        }

        .dx-brand-title span{
            color:#b8ff3b;
            text-shadow:0 0 14px rgba(184,255,59,0.22);
        }

        .dx-brand-sub{
            color:#adc0b2;
            font-size:13px;
            line-height:1.7;
            max-width:300px;
            margin:0 auto;
        }

        .dx-divider{
            width:56px;
            height:4px;
            border-radius:999px;
            margin:0 auto 14px;
            background:linear-gradient(90deg, rgba(184,255,59,0) 0%, rgba(184,255,59,0.9) 50%, rgba(184,255,59,0) 100%);
            box-shadow:0 0 12px rgba(184,255,59,0.12);
        }

        .dx-status{
            margin-bottom:10px;
        }

        .dx-field{
            margin-bottom:12px;
        }

        .dx-label{
            display:block;
            color:#e6ffd6;
            font-size:12px;
            font-weight:900;
            margin-bottom:7px;
        }

        .dx-input{
            width:100%;
            border:none !important;
            outline:none !important;
            border-radius:18px !important;
            padding:14px 15px !important;
            background:
                linear-gradient(180deg, rgba(11,16,20,0.98) 0%, rgba(10,15,18,0.98) 100%) !important;
            color:#f4fff8 !important;
            border:1px solid rgba(184,255,59,0.10) !important;
            font-size:14px !important;
            transition:.2s ease !important;
            box-shadow:none !important;
        }

        .dx-input:focus{
            border-color:rgba(184,255,59,0.24) !important;
            box-shadow:0 0 0 4px rgba(184,255,59,0.08) !important;
        }

        .dx-input::placeholder{
            color:#7d9184;
        }

        .dx-error{
            margin-top:6px;
        }

        .dx-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            flex-wrap:wrap;
            margin-top:4px;
            margin-bottom:14px;
        }

        .dx-remember{
            display:inline-flex;
            align-items:center;
            gap:10px;
            color:#dcebdd;
            font-size:13px;
            font-weight:800;
            user-select:none;
            cursor:pointer;
        }

        .dx-remember input{
            display:none;
        }

        .dx-check{
            width:22px;
            height:22px;
            border-radius:8px;
            border:1px solid rgba(184,255,59,0.18);
            background:rgba(255,255,255,0.04);
            display:flex;
            align-items:center;
            justify-content:center;
            transition:.2s ease;
            flex:0 0 22px;
        }

        .dx-check::after{
            content:"✓";
            color:#07120b;
            font-size:12px;
            font-weight:900;
            opacity:0;
            transform:scale(.7);
            transition:.2s ease;
        }

        .dx-remember input:checked + .dx-check{
            background:radial-gradient(circle,#e5ff86 0%,#b8ff3b 100%);
            border-color:rgba(184,255,59,0.28);
            box-shadow:0 0 14px rgba(184,255,59,0.16);
        }

        .dx-remember input:checked + .dx-check::after{
            opacity:1;
            transform:scale(1);
        }

        .dx-link{
            color:#d8ff8d;
            font-size:12px;
            font-weight:900;
            text-decoration:none;
        }

        .dx-link:hover{
            color:#edffbf;
            text-decoration:underline;
        }

        .dx-btn{
            width:100%;
            border:none;
            cursor:pointer;
            border-radius:18px;
            padding:15px 16px;
            background:radial-gradient(circle,#f1ff98 0%,#dfff5a 38%,#b8ff3b 100%);
            color:#07120b;
            font-size:16px;
            font-weight:900;
            box-shadow:
                0 12px 24px rgba(184,255,59,0.20),
                0 0 22px rgba(184,255,59,0.12);
            transition:.22s ease;
        }

        .dx-btn:hover{
            transform:translateY(-1px);
            filter:brightness(1.03);
        }

        .dx-footer{
            margin-top:14px;
            text-align:center;
            color:#9aac9f;
            font-size:13px;
            line-height:1.7;
        }

        .dx-footer a{
            color:#d8ff8d;
            font-weight:900;
            text-decoration:none;
        }

        .dx-footer a:hover{
            text-decoration:underline;
        }

        @media (max-height:860px){
            .dx-logo-box{
                width:100px;
                height:100px;
                border-radius:24px;
            }

            .dx-brand-title{
                font-size:24px;
            }

            .dx-brand-sub{
                font-size:12px;
                line-height:1.6;
            }

            .dx-input{
                padding:13px 14px !important;
            }

            .dx-btn{
                padding:14px 14px;
            }
        }

        @media (max-width:640px){
            .dx-auth-wrap{
                max-width:410px;
            }

            .dx-auth-card{
                padding:20px 16px 18px;
                border-radius:26px;
            }

            .dx-logo-box{
                width:96px;
                height:96px;
                border-radius:22px;
            }

            .dx-brand-title{
                font-size:24px;
            }

            .dx-brand-sub{
                font-size:12px;
                max-width:280px;
            }

            .dx-input{
                padding:13px 14px !important;
                border-radius:16px !important;
            }

            .dx-btn{
                padding:14px 14px;
                border-radius:16px;
            }

            .dx-row{
                gap:8px;
            }

            .dx-link,
            .dx-remember{
                font-size:12px;
            }
        }
    </style>

    <div class="dx-auth-wrap">
        <div class="dx-auth-card">
            <div class="dx-auth-inner">

                <div class="dx-logo-wrap">
                    <div class="dx-logo-box">
                        <img src="{{ asset('images/dawnex-logo.png') }}" alt="DawnEX" class="dx-logo">
                    </div>
                </div>

                <div class="dx-brand">
                    <div class="dx-badge">Secure Login</div>
                    <div class="dx-brand-title">Welcome to <span>DawnEX</span></div>
                    <div class="dx-brand-sub">
                        Sign in to access your AI trading workspace, wallet balance, market activity, and strategy controls.
                    </div>
                </div>

                <div class="dx-divider"></div>

                <div class="dx-status">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="dx-field">
                        <x-input-label for="email" :value="__('Email')" class="dx-label" />
                        <x-text-input
                            id="email"
                            class="dx-input"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email"
                        />
                        <x-input-error :messages="$errors->get('email')" class="dx-error" />
                    </div>

                    <div class="dx-field">
                        <x-input-label for="password" :value="__('Password')" class="dx-label" />
                        <x-text-input
                            id="password"
                            class="dx-input"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        />
                        <x-input-error :messages="$errors->get('password')" class="dx-error" />
                    </div>

                    <div class="dx-row">
                        <label for="remember_me" class="dx-remember">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span class="dx-check"></span>
                            <span>{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="dx-link" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="dx-btn">
                        {{ __('Log in') }}
                    </button>

                    @if (Route::has('register'))
                        <div class="dx-footer">
                            Don’t have an account?
                            <a href="{{ route('register') }}">Create one</a>
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>