<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DawnEX</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *{
            box-sizing:border-box;
        }

        html, body{
            min-height:100%;
            margin:0;
            padding:0;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(184,255,59,0.08) 0%, transparent 24%),
                radial-gradient(circle at bottom left, rgba(98,255,154,0.06) 0%, transparent 22%),
                linear-gradient(180deg,#040905 0%, #07100b 45%, #050b08 100%);
            color:#f4fff8;
            overflow-x:hidden;
            position:relative;
        }

        body::before{
            content:"";
            position:fixed;
            inset:0;
            background:
                linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
            background-size:34px 34px;
            opacity:.14;
            z-index:0;
            pointer-events:none;
        }

        body::after{
            content:"";
            position:fixed;
            inset:0;
            background:
                radial-gradient(circle at center, rgba(184,255,59,0.03) 0%, rgba(184,255,59,0) 42%);
            z-index:0;
            pointer-events:none;
        }

        .guest-shell{
            position:relative;
            z-index:4;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:120px 16px 24px;
        }

        .guest-inner{
            width:100%;
            display:flex;
            justify-content:center;
        }

        .top-brand{
            position:fixed;
            top:16px;
            left:50%;
            transform:translateX(-50%);
            z-index:9;
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:8px;
            text-align:center;
            pointer-events:none;
        }

        .top-brand-logo{
            width:82px;
            height:82px;
            object-fit:contain;
            filter:
                drop-shadow(0 0 10px rgba(184,255,59,0.18))
                drop-shadow(0 0 22px rgba(184,255,59,0.20));
            animation:logoFloat 4.8s ease-in-out infinite;
        }

        .top-brand-title{
            color:#ebf9cf;
            font-size:15px;
            font-weight:900;
            letter-spacing:2px;
            text-transform:uppercase;
            text-shadow:0 0 14px rgba(184,255,59,0.16);
        }

        @keyframes logoFloat{
            0%{ transform:translateY(0px); }
            50%{ transform:translateY(-4px); }
            100%{ transform:translateY(0px); }
        }

        .market-bg{
            position:fixed;
            inset:0;
            z-index:1;
            overflow:hidden;
            pointer-events:none;
        }

        .grid-glow{
            position:absolute;
            inset:0;
            background:
                radial-gradient(circle at 20% 70%, rgba(98,255,154,0.05) 0%, transparent 24%),
                radial-gradient(circle at 80% 25%, rgba(184,255,59,0.06) 0%, transparent 24%),
                radial-gradient(circle at 55% 45%, rgba(184,255,59,0.04) 0%, transparent 18%);
            animation:gridGlowMove 12s ease-in-out infinite alternate;
        }

        @keyframes gridGlowMove{
            0%{ transform:translateX(0px) translateY(0px) scale(1); }
            100%{ transform:translateX(8px) translateY(-6px) scale(1.03); }
        }

        .glow-orb{
            position:absolute;
            border-radius:50%;
            filter:blur(16px);
            opacity:.8;
        }

        .glow-orb.one{
            width:300px;
            height:300px;
            top:10%;
            right:6%;
            background:radial-gradient(circle, rgba(184,255,59,0.07) 0%, rgba(184,255,59,0) 70%);
            animation:orbMove 9s ease-in-out infinite;
        }

        .glow-orb.two{
            width:240px;
            height:240px;
            bottom:10%;
            left:4%;
            background:radial-gradient(circle, rgba(98,255,154,0.06) 0%, rgba(98,255,154,0) 70%);
            animation:orbMove 11s ease-in-out infinite reverse;
        }

        .glow-orb.three{
            width:220px;
            height:220px;
            top:46%;
            left:50%;
            transform:translateX(-50%);
            background:radial-gradient(circle, rgba(184,255,59,0.045) 0%, rgba(184,255,59,0) 72%);
            animation:orbPulse 6s ease-in-out infinite;
        }

        @keyframes orbMove{
            0%{ transform:translateY(0px) translateX(0px); }
            50%{ transform:translateY(-10px) translateX(8px); }
            100%{ transform:translateY(0px) translateX(0px); }
        }

        @keyframes orbPulse{
            0%{ opacity:.4; transform:translateX(-50%) scale(1); }
            50%{ opacity:.72; transform:translateX(-50%) scale(1.05); }
            100%{ opacity:.4; transform:translateX(-50%) scale(1); }
        }

        .waves{
            position:absolute;
            inset:0;
            z-index:2;
            opacity:.9;
        }

        .wave{
            position:absolute;
            left:-10%;
            width:120%;
            height:180px;
            opacity:.18;
        }

        .wave svg{
            width:100%;
            height:100%;
        }

        .wave.one{
            top:18%;
            animation:floatLine 11s ease-in-out infinite;
        }

        .wave.two{
            top:34%;
            opacity:.12;
            animation:floatLine 14s ease-in-out infinite reverse;
        }

        .wave.three{
            top:52%;
            opacity:.10;
            animation:floatLine 16s ease-in-out infinite;
        }

        .wave.four{
            top:68%;
            opacity:.08;
            animation:floatLine 19s ease-in-out infinite reverse;
        }

        @keyframes floatLine{
            0%{ transform:translateY(0px) translateX(0px); }
            50%{ transform:translateY(-10px) translateX(10px); }
            100%{ transform:translateY(0px) translateX(0px); }
        }

        .ticker-strip{
            position:absolute;
            left:0;
            right:0;
            top:50%;
            transform:translateY(-50%);
            height:34px;
            overflow:hidden;
            z-index:2;
            opacity:.08;
            border-top:1px solid rgba(184,255,59,0.08);
            border-bottom:1px solid rgba(184,255,59,0.08);
            background:rgba(255,255,255,0.01);
        }

        .ticker-track{
            display:flex;
            align-items:center;
            gap:34px;
            width:max-content;
            height:100%;
            white-space:nowrap;
            color:#dfffab;
            font-size:13px;
            font-weight:800;
            animation:tickerMove 26s linear infinite;
            padding-left:20px;
        }

        @keyframes tickerMove{
            0%{ transform:translateX(0); }
            100%{ transform:translateX(-50%); }
        }

        .coin-float{
            position:absolute;
            width:48px;
            height:48px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:rgba(184,255,59,0.06);
            border:1px solid rgba(184,255,59,0.10);
            color:#dfffab;
            font-size:14px;
            font-weight:900;
            box-shadow:0 0 16px rgba(184,255,59,0.08);
            opacity:.16;
            animation:coinDrift 12s ease-in-out infinite;
        }

        .coin-float.btc{ top:24%; left:10%; animation-delay:0s; }
        .coin-float.eth{ top:72%; left:14%; animation-delay:2s; }
        .coin-float.ai{ top:26%; right:10%; animation-delay:4s; }
        .coin-float.usdt{ top:74%; right:14%; animation-delay:6s; }
        .coin-float.xrp{ top:40%; left:6%; animation-delay:3s; }
        .coin-float.sol{ top:58%; right:8%; animation-delay:5s; }

        @keyframes coinDrift{
            0%{ transform:translateY(0px) rotate(0deg); }
            50%{ transform:translateY(-10px) rotate(4deg); }
            100%{ transform:translateY(0px) rotate(0deg); }
        }

        .spark{
            position:absolute;
            width:4px;
            height:4px;
            border-radius:50%;
            background:#dfffab;
            box-shadow:0 0 10px rgba(184,255,59,0.35);
            opacity:.18;
            animation:sparkBlink 4s ease-in-out infinite;
        }

        .spark.s1{ top:18%; left:20%; animation-delay:0s; }
        .spark.s2{ top:26%; right:18%; animation-delay:1s; }
        .spark.s3{ top:68%; left:15%; animation-delay:2s; }
        .spark.s4{ top:74%; right:22%; animation-delay:3s; }
        .spark.s5{ top:46%; left:50%; animation-delay:1.5s; }

        @keyframes sparkBlink{
            0%, 100%{ opacity:.08; transform:scale(1); }
            50%{ opacity:.34; transform:scale(1.8); }
        }

        .candles-rain{
            position:absolute;
            inset:0;
            z-index:3;
            overflow:hidden;
        }

        .rain-candle{
            position:absolute;
            top:-180px;
            width:12px;
            display:flex;
            flex-direction:column;
            align-items:center;
            opacity:0;
            animation:fallCandle linear infinite;
        }

        .rain-candle .wick{
            width:2px;
            border-radius:999px;
            margin-bottom:-2px;
        }

        .rain-candle .body{
            width:12px;
            border-radius:4px;
        }

        .rain-candle.green .body{
            background:linear-gradient(180deg,#9dff8f 0%, #4fe27f 100%);
            box-shadow:0 0 14px rgba(98,255,154,0.14);
        }

        .rain-candle.red .body{
            background:linear-gradient(180deg,#ff9e9e 0%, #ef5353 100%);
            box-shadow:0 0 14px rgba(239,68,68,0.12);
        }

        .rain-candle.green .wick{
            background:rgba(98,255,154,0.45);
        }

        .rain-candle.red .wick{
            background:rgba(255,95,116,0.40);
        }

        @keyframes fallCandle{
            0%{
                transform:translateY(0);
                opacity:0;
            }
            8%{
                opacity:.32;
            }
            85%{
                opacity:.32;
            }
            100%{
                transform:translateY(125vh);
                opacity:0;
            }
        }

        @media (max-width:640px){
            .guest-shell{
                padding:108px 14px 22px;
                align-items:center;
            }

            .top-brand{
                top:12px;
            }

            .top-brand-logo{
                width:70px;
                height:70px;
            }

            .top-brand-title{
                font-size:13px;
                letter-spacing:1.6px;
            }

            .wave{
                height:150px;
            }

            .coin-float{
                width:40px;
                height:40px;
                font-size:12px;
            }

            .ticker-strip{
                height:28px;
            }

            .ticker-track{
                font-size:11px;
                gap:22px;
            }

            .rain-candle{
                width:10px;
            }

            .rain-candle .body{
                width:10px;
            }
        }
    </style>
</head>
<body>

    <div class="market-bg">

        <div class="grid-glow"></div>

        <div class="glow-orb one"></div>
        <div class="glow-orb two"></div>
        <div class="glow-orb three"></div>

        <div class="waves">
            <div class="wave one">
                <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                    <path fill="none" stroke="rgba(184,255,59,0.28)" stroke-width="3"
                        d="M0,192 C180,130 260,230 420,190 C600,145 700,90 860,138 C1020,186 1160,95 1440,140" />
                </svg>
            </div>

            <div class="wave two">
                <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                    <path fill="none" stroke="rgba(255,95,116,0.18)" stroke-width="2.5"
                        d="M0,220 C120,240 240,120 420,150 C600,180 760,250 960,180 C1160,110 1260,145 1440,110" />
                </svg>
            </div>

            <div class="wave three">
                <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                    <path fill="none" stroke="rgba(98,255,154,0.18)" stroke-width="2.5"
                        d="M0,150 C180,120 300,210 480,170 C700,120 760,70 940,110 C1160,160 1250,220 1440,170" />
                </svg>
            </div>

            <div class="wave four">
                <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                    <path fill="none" stroke="rgba(255,255,255,0.10)" stroke-width="2"
                        d="M0,180 C220,240 360,100 540,140 C760,190 840,210 1040,150 C1190,105 1320,145 1440,125" />
                </svg>
            </div>
        </div>

        <div class="ticker-strip">
            <div class="ticker-track">
                <span>BTC +2.14%</span>
                <span>ETH +1.28%</span>
                <span>SOL -0.82%</span>
                <span>XRP +3.05%</span>
                <span>AI VAULT LIVE</span>
                <span>USDT STABLE</span>
                <span>BTC +2.14%</span>
                <span>ETH +1.28%</span>
                <span>SOL -0.82%</span>
                <span>XRP +3.05%</span>
                <span>AI VAULT LIVE</span>
                <span>USDT STABLE</span>
            </div>
        </div>

        <div class="coin-float btc">₿</div>
        <div class="coin-float eth">◎</div>
        <div class="coin-float ai">AI</div>
        <div class="coin-float usdt">₮</div>
        <div class="coin-float xrp">X</div>
        <div class="coin-float sol">S</div>

        <div class="spark s1"></div>
        <div class="spark s2"></div>
        <div class="spark s3"></div>
        <div class="spark s4"></div>
        <div class="spark s5"></div>

        <div class="candles-rain">
            <div class="rain-candle green" style="left:6%; animation-duration:10s; animation-delay:-1s;">
                <div class="wick" style="height:36px;"></div>
                <div class="body" style="height:68px;"></div>
            </div>

            <div class="rain-candle red" style="left:12%; animation-duration:13s; animation-delay:-4s;">
                <div class="wick" style="height:28px;"></div>
                <div class="body" style="height:34px;"></div>
            </div>

            <div class="rain-candle green" style="left:18%; animation-duration:11s; animation-delay:-2s;">
                <div class="wick" style="height:44px;"></div>
                <div class="body" style="height:82px;"></div>
            </div>

            <div class="rain-candle red" style="left:28%; animation-duration:14s; animation-delay:-6s;">
                <div class="wick" style="height:30px;"></div>
                <div class="body" style="height:38px;"></div>
            </div>

            <div class="rain-candle green" style="left:38%; animation-duration:12s; animation-delay:-3s;">
                <div class="wick" style="height:40px;"></div>
                <div class="body" style="height:72px;"></div>
            </div>

            <div class="rain-candle red" style="left:48%; animation-duration:15s; animation-delay:-8s;">
                <div class="wick" style="height:32px;"></div>
                <div class="body" style="height:42px;"></div>
            </div>

            <div class="rain-candle green" style="left:58%; animation-duration:10.5s; animation-delay:-5s;">
                <div class="wick" style="height:48px;"></div>
                <div class="body" style="height:88px;"></div>
            </div>

            <div class="rain-candle red" style="left:68%; animation-duration:13.5s; animation-delay:-7s;">
                <div class="wick" style="height:26px;"></div>
                <div class="body" style="height:32px;"></div>
            </div>

            <div class="rain-candle green" style="left:78%; animation-duration:11.5s; animation-delay:-2.5s;">
                <div class="wick" style="height:38px;"></div>
                <div class="body" style="height:76px;"></div>
            </div>

            <div class="rain-candle red" style="left:88%; animation-duration:14.5s; animation-delay:-9s;">
                <div class="wick" style="height:30px;"></div>
                <div class="body" style="height:36px;"></div>
            </div>

            <div class="rain-candle green" style="left:94%; animation-duration:12.5s; animation-delay:-4.5s;">
                <div class="wick" style="height:46px;"></div>
                <div class="body" style="height:84px;"></div>
            </div>
        </div>
    </div>

    <div class="top-brand">
        <img src="{{ asset('images/dawnex-logo.png') }}" alt="DawnEX" class="top-brand-logo">
        <div class="top-brand-title">AI TRADING PLATFORM</div>
    </div>

    <div class="guest-shell">
        <div class="guest-inner">
            {{ $slot }}
        </div>
    </div>

</body>
</html>