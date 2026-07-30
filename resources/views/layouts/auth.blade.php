<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EVSU NSTP-CWTS Management System</title>
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

<body class="min-h-screen flex flex-col text-[#2d0012] antialiased selection:bg-[#800033] selection:text-white">
    <div class="flag-stripe w-full fixed top-0 left-0 z-50"></div>
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
        {{ $slot }}
    </main>
    <footer class="py-6 text-center text-[#9ca3af] text-xs font-body">
        &copy; {{ date('Y') }} EVSU NSTP-CWTS · Student Registry System
    </footer>

    <x-utils.toast />
    @stack('scripts')
</body>

</html>
