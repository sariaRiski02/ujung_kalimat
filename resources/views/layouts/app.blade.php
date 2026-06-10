<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Ujung Kalimat | Kumpulan Renungan dan Tulisan</title>
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