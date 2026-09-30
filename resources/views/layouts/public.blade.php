<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'EVSU NSTP Portal' }} - Eastern Visayas State University</title>

    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, body { font-family: "Source Sans 3", sans-serif; }
        h1, h2, h3, h4, .font-display { font-family: "Oswald", sans-serif; }
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

    <header x-data="{ mobileMenuOpen: false }" class="bg-[#2d0012] header-pattern border-b border-[#4a001c] shadow-lg sticky top-[4px] z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 overflow-hidden flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="EVSU NSTP" class="w-9 h-9 sm:w-10 sm:h-10 object-contain drop-shadow" />
                <div>
                    <span class="text-[#f9c22e] text-[9px] font-display tracking-[0.15em] sm:tracking-[0.2em] uppercase block leading-none">
                        Republic of the Philippines
                    </span>
                    <h1 class="text-white font-display text-base sm:text-lg tracking-wide leading-tight whitespace-nowrap">
                        EVSU NSTP
                    </h1>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-xs font-display tracking-widest uppercase">
                <a href="{{ route('home') }}" wire:navigate 
                   class="transition {{ request()->routeIs('home') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }}">
                    Home
                </a>
                <a href="{{ route('news.index') }}" wire:navigate 
                   class="transition {{ request()->routeIs('news.*') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }}">
                    Bulletins
                </a>
                <a href="{{ route('about') }}" wire:navigate 
                   class="transition {{ request()->routeIs('about') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }}">
                    About
                </a>
                <a href="{{ route('services') }}" wire:navigate 
                   class="transition {{ request()->routeIs('services') ? 'text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:text-[#f9c22e]' }}">
                    Services
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="bg-[#f9c22e] hover:bg-[#e5a800] text-[#2d0012] px-4 py-2 rounded-lg font-semibold shadow transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="border border-[#f9c22e]/40 hover:bg-[#f9c22e]/10 text-[#f9c22e] px-4 py-2 rounded-lg font-semibold transition">
                        Portal Login
                    </a>
                @endauth
            </nav>

            <div class="flex items-center md:hidden">
                <button 
                    type="button" 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="text-[#f9c22e] hover:text-white p-2 rounded-lg bg-[#4a001c]/60 hover:bg-[#4a001c] transition border border-[#4a001c]"
                    aria-label="Toggle navigation"
                >
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>

                    <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            @click.outside="mobileMenuOpen = false"
            style="display: none;"
            class="md:hidden bg-[#1f000c] border-b border-[#4a001c] shadow-2xl px-5 py-4 space-y-2.5 font-display tracking-widest uppercase text-xs"
        >
            <a href="{{ route('home') }}" wire:navigate @click="mobileMenuOpen = false" 
               class="block py-2.5 px-3 rounded-lg transition {{ request()->routeIs('home') ? 'bg-[#4a001c] text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:bg-[#4a001c]/40 hover:text-white' }}">
                Home
            </a>
            <a href="{{ route('news.index') }}" wire:navigate @click="mobileMenuOpen = false" 
               class="block py-2.5 px-3 rounded-lg transition {{ request()->routeIs('news.*') ? 'bg-[#4a001c] text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:bg-[#4a001c]/40 hover:text-white' }}">
                Bulletins & News
            </a>
            <a href="{{ route('about') }}" wire:navigate @click="mobileMenuOpen = false" 
               class="block py-2.5 px-3 rounded-lg transition {{ request()->routeIs('about') ? 'bg-[#4a001c] text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:bg-[#4a001c]/40 hover:text-white' }}">
                About EVSU NSTP
            </a>
            <a href="{{ route('services') }}" wire:navigate @click="mobileMenuOpen = false" 
               class="block py-2.5 px-3 rounded-lg transition {{ request()->routeIs('services') ? 'bg-[#4a001c] text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:bg-[#4a001c]/40 hover:text-white' }}">
                Student Services
            </a>
            <a href="{{ route('terms') }}" wire:navigate @click="mobileMenuOpen = false" 
               class="block py-2.5 px-3 rounded-lg transition {{ request()->routeIs('terms') ? 'bg-[#4a001c] text-[#f9c22e] font-bold' : 'text-[#e8b4c4]/80 hover:bg-[#4a001c]/40 hover:text-white' }}">
                Terms & Privacy (RA 10173)
            </a>

            <div class="pt-3 border-t border-[#4a001c]/80">
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center bg-[#f9c22e] hover:bg-[#e5a800] text-[#2d0012] py-2.5 rounded-xl font-bold tracking-widest shadow transition">
                        Open Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block text-center border border-[#f9c22e] hover:bg-[#f9c22e]/10 text-[#f9c22e] py-2.5 rounded-xl font-bold tracking-widest transition">
                        Portal Sign In
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-8">
        {{ $slot }}
    </main>

    <footer class="bg-[#2d0012] header-pattern text-white/70 border-t border-[#4a001c] mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div>
                <p class="font-display tracking-wider uppercase text-white font-semibold">Eastern Visayas State University</p>
                <p class="text-[#e8b4c4]/60 text-[11px] mt-0.5">National Service Training Program · Tacloban City, Leyte</p>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-[#e8b4c4]/60">
                <a href="{{ route('terms') }}" wire:navigate class="hover:text-white transition">Terms & Privacy Policy (RA 10173)</a>
                <span>·</span>
                <span>Republic of the Philippines</span>
            </div>
        </div>
        <div class="flag-stripe w-full opacity-40"></div>
    </footer>
</body>

</html>