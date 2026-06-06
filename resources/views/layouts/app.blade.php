<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <title>Ujung Kalimat | Kumpulan Renungan dan Tulisan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-black antialiased">

    @include('partials.header')

    <div class="flex flex-col max-w-4xl px-6 mx-auto  gap-8 py-8">
        @yield('content')
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/search.js')}}"></script>
</body>
</html>