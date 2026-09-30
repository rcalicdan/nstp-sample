<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'EVSU NSTP Portal' }} - Eastern Visayas State University</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *,
        body {
            font-family: "Source Sans 3", sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        .font-display {
            font-family: "Oswald", sans-serif;
        }

        body {
            background-color: #fcf9fa;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Ccircle cx='50' cy='50' r='40' fill='none' stroke='%23ddc0c8' stroke-width='0.4'/%3E%3Ccircle cx='50' cy='50' r='30' fill='none' stroke='%23ddc0c8' stroke-width='0.4'/%3E%3C/svg%3E");
            background-size: 80px 80px;
        }

        .flag-stripe {
            height: 4px;
            background: linear-gradient(to right, #0038a8 0%, #0038a8 33.33%, #ce1126 33.33%, #ce1126 66.66%, #f9c22e 66.66%, #f9c22e 100%);
        }

        .header-pattern {
            background-image: repeating-linear-gradient(135deg, transparent, transparent 12px, rgba(255, 255, 255, 0.04) 12px, rgba(255, 255, 255, 0.04) 24px);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col text-[#2d0012] antialiased">
    <div class="flag-stripe w-full fixed top-0 left-0 z-50"></div>

    <header class="bg-[#2d0012] header-pattern border-b border-[#4a001c] shadow-lg sticky top-[4px] z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="EVSU NSTP"
                    class="w-10 h-10 object-contain drop-shadow" />
                <div>
                    <span class="text-[#f9c22e] text-[10px] font-display tracking-[0.2em] uppercase block leading-none">
                        Republic of the Philippines
                    </span>
                    <h1 class="text-white font-display text-lg tracking-wide leading-tight">
                        EVSU NSTP
                    </h1>
                </div>
            </a>

            <nav class="flex items-center gap-6 text-xs font-display tracking-widest uppercase">
                <a href="{{ route('home') }}" wire:navigate
                    class="transition {{ request()->routeIs('home') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }}">
                    Home
                </a>
                <a href="{{ route('news.index') }}" wire:navigate
                    class="transition {{ request()->routeIs('news.*') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }}">
                    Bulletins
                </a>
                <a href="{{ route('about') }}" wire:navigate
                    class="transition {{ request()->routeIs('about') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }} hidden sm:inline">
                    About
                </a>
                <a href="{{ route('services') }}" wire:navigate
                    class="transition {{ request()->routeIs('services') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }} hidden md:inline">
                    Services
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="bg-[#f9c22e] hover:bg-[#e5a800] text-[#2d0012] px-4 py-2 rounded-lg font-semibold shadow transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="border border-[#f9c22e]/40 hover:bg-[#f9c22e]/10 text-[#f9c22e] px-4 py-2 rounded-lg font-semibold transition">
                        Portal Login
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-8">
        {{ $slot }}
    </main>

    <footer class="bg-[#2d0012] header-pattern text-white/70 border-t border-[#4a001c] mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div>
                <p class="font-display tracking-wider uppercase text-white font-semibold">Eastern Visayas State
                    University</p>
                <p class="text-[#e8b4c4]/60 text-[11px] mt-0.5">National Service Training Program · Tacloban City, Leyte
                </p>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-[#e8b4c4]/60">
                <a href="{{ route('terms') }}" wire:navigate class="hover:text-white transition">Terms & Privacy Policy
                    (RA 10173)</a>
                <span>·</span>
                <span>Republic of the Philippines</span>
            </div>
        </div>
        <div class="flag-stripe w-full opacity-40"></div>
    </footer>
</body>

</html>
