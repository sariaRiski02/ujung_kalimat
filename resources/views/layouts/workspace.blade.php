<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Workspace | Ujung Kalimat</title>
    <style>
        .preload, .preload * { transition: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans preload"
      x-data="{ open: localStorage.getItem('workspaceSidebarOpen') === 'true' }"
      x-init="$watch('open', value => localStorage.setItem('workspaceSidebarOpen', value));
              requestAnimationFrame(() => requestAnimationFrame(() => $el.classList.remove('preload')))">

    {{-- Sidebar --}}
    @include('pages.pageWorkspace.sidebar')
    @yield('content')

    @stack('scripts')
</body>
</html>