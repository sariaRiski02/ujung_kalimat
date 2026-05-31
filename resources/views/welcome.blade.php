<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujung Kalimat | Kumpulan Renungan dan Tulisan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-black antialiased">

    <!-- Navigasi -->
    <nav class="fixed top-4 left-0 right-0 z-50 px-6">
        <div class="max-w-4xl mx-auto relative bg-white/80 backdrop-blur-md border border-black/10 rounded-full px-5 py-2.5 flex justify-between items-center shadow-lg">

            <!-- Logo teks -->
            <a href="#" class="font-serif italic text-lg text-black hover:opacity-60 transition flex-shrink-0">
                Ujung Kalimat
            </a>

            <!-- Kanan: tombol search + login -->
            <div class="flex items-center gap-2">
                <!-- Tombol ikon search -->
                <button onclick="openSearch()"
                    class="w-9 h-9 flex items-center justify-center rounded-full border border-black/15 hover:bg-black/5 transition"
                    aria-label="Buka pencarian">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-black/70" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </button>

                <!-- Login -->
                <a href="#" class="font-sans text-xs uppercase tracking-widest bg-black text-white px-5 py-2 rounded-full hover:bg-gray-800 transition">
                    Login
                </a>
            </div>

            <!-- Search Overlay (menutupi seluruh navbar saat aktif) -->
            <div class="search-overlay" id="searchOverlay">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#bbb">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari tulisan..." />
                <button onclick="closeSearch()" class="flex-shrink-0 hover:opacity-60 transition" aria-label="Tutup pencarian">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    <!-- Hero Section -->
    <header class="max-w-4xl mx-auto px-6 py-16 border-b border-black pt-32">
        <h1 class="text-6xl md:text-8xl font-serif italic mb-8">Menemukan titik, di ujung kalimat.</h1>
        <p class="max-w-xl text-lg md:text-xl font-sans text-gray-600">
            Sebuah ruang untuk esai, renungan acak, dan observasi tentang kehidupan yang tidak selalu memiliki jawaban pasti.
        </p>
    </header>

    <!-- Artikel Terbaru -->
    <main class="max-w-4xl mx-auto px-6 py-20">
        <h2 class="text-sm font-sans uppercase tracking-widest mb-12 text-gray-500">Bacaan Terbaru</h2>
        <div class="space-y-16">
            <article class="group">
                <a href="#" class="block">
                    <span class="text-xs font-sans uppercase text-gray-400">25 Mei 2026</span>
                    <h3 class="text-3xl md:text-4xl font-serif mt-2 mb-3 group-hover:underline">Menulis di Tengah Kebisingan</h3>
                    <p class="text-gray-700 font-sans leading-relaxed max-w-2xl">Bagaimana cara menjaga fokus saat dunia terus menuntut atensi di setiap detiknya?</p>
                </a>
            </article>
            <article class="group">
                <a href="#" class="block">
                    <span class="text-xs font-sans uppercase text-gray-400">12 Mei 2026</span>
                    <h3 class="text-3xl md:text-4xl font-serif mt-2 mb-3 group-hover:underline">Logika dan Intuisi</h3>
                    <p class="text-gray-700 font-sans leading-relaxed max-w-2xl">Sebuah catatan tentang persinggungan antara kode yang kaku dan perasaan yang cair.</p>
                </a>
            </article>
            <article class="group">
                <a href="#" class="block">
                    <span class="text-xs font-sans uppercase text-gray-400">01 Mei 2026</span>
                    <h3 class="text-3xl md:text-4xl font-serif mt-2 mb-3 group-hover:underline">Tentang Waktu yang Hilang</h3>
                    <p class="text-gray-700 font-sans leading-relaxed max-w-2xl">Refleksi tentang produktivitas yang terkadang justru menjauhkan kita dari esensi.</p>
                </a>
            </article>
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-4xl mx-auto px-6 py-12 border-t border-black text-sm font-sans flex flex-col md:flex-row justify-between gap-6">
        <p class="text-gray-500">&copy; 2026 Ujung Kalimat.</p>
        <div class="flex gap-6">
            <a href="#" class="hover:underline">Twitter</a>
            <a href="#" class="hover:underline">Newsletter</a>
        </div>
    </footer>

    <script>
        function openSearch() {
            const overlay = document.getElementById('searchOverlay');
            overlay.classList.add('active');
            setTimeout(() => document.getElementById('searchInput').focus(), 50);
        }
        function closeSearch() {
            document.getElementById('searchOverlay').classList.remove('active');
            document.getElementById('searchInput').value = '';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSearch();
        });
    </script>

</body>
</html>