
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('tab') — Ujung Kalimat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body class="bg-white min-h-screen flex flex-col font-sans">

    {{-- Navbar --}}
    <nav class="flex items-center justify-between px-10 py-4 border-b border-gray-200">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 no-underline">
            <span class="font-serif text-lg font-medium text-[#1a1a2e]">Ujung Kalimat</span>
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('signup') }}" class="text-sm {{ request()->routeIs('signup') ? 'bg-[#1a1a2e] text-white px-5 py-2 rounded-full hover:bg-[#2d2d4a] transition-colors' : 'text-gray-500 hover:text-[#1a1a2e] transition-colors'}} ">
                Signup
            </a>
            <a href="{{ route('signin') }}" class="text-sm {{ request()->routeIs('signin') ? 'bg-[#1a1a2e] text-white px-5 py-2 rounded-full hover:bg-[#2d2d4a] transition-colors' : 'text-gray-500 hover:text-[#1a1a2e] transition-colors'}} ">
                Signin
            </a>
        </div>
    </nav>

    @yield('content')

    @stack('scripts')
</body>
</html>