<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-xl mx-auto my-10 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-gray-900 text-white">
            <h1 class="text-2xl font-bold tracking-tight">
                Ujung Kalimat
            </h1>
            <p class="mt-2 text-sm text-gray-300">
                Setiap tulisan punya cerita.
            </p>
        </div>

        <div class="px-8 py-10">
            <h2 class="text-xl font-bold text-gray-900">
                Verifikasi alamat email kamu
            </h2>

            <p class="mt-4 text-sm leading-6 text-gray-600">
                Terima kasih telah mendaftar di Ujung Kalimat.
                Untuk melanjutkan proses pendaftaran, masukkan
                kode verifikasi berikut pada halaman pendaftaran.
            </p>

            <div class="my-8 rounded-lg bg-gray-50 border border-gray-200 px-6 py-6 text-center">
                <p class="text-xs uppercase tracking-widest text-gray-500">
                    Kode verifikasi
                </p>

                <p class="mt-3 text-3xl font-bold tracking-[0.4em] text-gray-900">
                    045323
                </p>
            </div>

            <p class="text-sm leading-6 text-gray-600">
                Kode ini berlaku selama <strong>5 menit</strong>.
                Jangan bagikan kode ini kepada siapa pun, termasuk
                pihak yang mengaku sebagai tim Ujung Kalimat.
            </p>

            <p class="mt-4 text-sm leading-6 text-gray-600">
                Jika kamu tidak merasa melakukan pendaftaran,
                abaikan email ini. Akun tidak akan dibuat tanpa
                proses verifikasi yang diperlukan.
            </p>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-sm text-gray-600">
                    Salam hangat,<br>
                    <strong class="text-gray-900">Tim Ujung Kalimat</strong>
                </p>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-5 text-center">
            <p class="text-xs text-gray-500">
                Email ini dikirim secara otomatis. Mohon jangan membalasnya.
            </p>
            <p class="mt-2 text-xs text-gray-400">
                &copy; {{ date('Y') }} Ujung Kalimat.
            </p>
        </div>
    </div>
</body>
</html>
```
