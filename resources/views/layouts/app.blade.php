<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DawnEX') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body{
            min-height:100%;
            margin:0;
            padding:0;
            overflow-x:hidden;
            background:
                radial-gradient(circle at top center, rgba(184,255,59,0.08) 0%, rgba(184,255,59,0) 24%),
                linear-gradient(180deg, #0a0f0c 0%, #0d1410 55%, #0a0f0c 100%);
            color:#f4fff8;
        }

        body{
            font-family:'Figtree', sans-serif;
        }

        .dx-app-shell{
            min-height:100vh;
            background:
                radial-gradient(circle at 50% 10%, rgba(184,255,59,0.05) 0%, rgba(184,255,59,0) 28%),
                linear-gradient(180deg, rgba(10,15,12,0.96) 0%, rgba(13,20,16,0.98) 100%);
        }

        .dx-page-main{
            min-height:calc(100vh - 78px);
            padding:0 0 110px;
        }

        .dx-page-frame{
            width:100%;
            max-width:430px;
            margin:0 auto;
            position:relative;
        }

        .dx-page-header{
            background:transparent;
            box-shadow:none;
            border:none;
        }

        .dx-page-header-inner{
            width:100%;
            max-width:430px;
            margin:0 auto;
            padding:8px 14px 4px;
        }

        .dx-page-content{
            width:100%;
            max-width:430px;
            margin:0 auto;
        }

        @media (max-width: 480px){
            .dx-page-frame,
            .dx-page-header-inner,
            .dx-page-content{
                max-width:100%;
            }

            .dx-page-main{
                padding-bottom:108px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="dx-app-shell">

        @include('layouts.navigation')

        @isset($header)
            <header class="dx-page-header">
                <div class="dx-page-header-inner">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="dx-page-main">
            <div class="dx-page-frame">
                <div class="dx-page-content">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>
            </div>
        </main>

        @include('layouts.bottom-nav')

    </div>
</body>
</html>