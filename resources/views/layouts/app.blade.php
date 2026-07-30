<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - EVSU NSTP Management System</title>

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
        .font-display {
            font-family: "Oswald", sans-serif;
        }

        body {
            background-color: #f5f0f2;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Ccircle cx='50' cy='50' r='40' fill='none' stroke='%23ddc0c8' stroke-width='0.5'/%3E%3Ccircle cx='50' cy='50' r='30' fill='none' stroke='%23ddc0c8' stroke-width='0.5'/%3E%3Ccircle cx='50' cy='50' r='20' fill='none' stroke='%23ddc0c8' stroke-width='0.5'/%3E%3C/svg%3E");
            background-size: 80px 80px;
        }

        .flag-stripe {
            height: 5px;
            background: linear-gradient(to right, #0038a8 0%, #0038a8 33.33%, #ce1126 33.33%, #ce1126 66.66%, #f9c22e 66.66%, #f9c22e 100%);
        }

        .header-pattern {
            background-image: repeating-linear-gradient(135deg, transparent, transparent 12px, rgba(255, 255, 255, 0.04) 12px, rgba(255, 255, 255, 0.04) 24px);
        }
    </style>
</head>

<body x-data="{
    sidebarOpen: false,
    sidebarCollapsed: false,
    toggleSidebar() {
        if (window.innerWidth < 768) {
            this.sidebarOpen = !this.sidebarOpen;
        } else {
            this.sidebarCollapsed = !this.sidebarCollapsed;
        }
    }
}"
    class="min-h-screen flex flex-col text-[#2d0012] antialiased selection:bg-[#800033] selection:text-white">
    <div class="flag-stripe w-full fixed top-0 left-0 z-50"></div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-[#150008]/60 z-30 md:hidden"
        style="display: none;"></div>

    <div class="flex-1 flex pt-[5px] min-h-screen">
        <x-partials.sidebar />

        <div :class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'"
            class="flex-1 flex flex-col min-w-0 transition-all duration-300">
            <x-partials.header />

            <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-8">
                {{ $slot }}
            </main>

            <x-partials.footer />
        </div>
    </div>

    <x-utils.toast />
    @stack('scripts')
</body>

</html>
