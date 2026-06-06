@extends('layouts.auth')


@section('tab')
    signin
@endsection

@section('content')

{{-- Main --}}
    <main class="flex-1 flex items-start justify-center px-6 pt-20 pb-16">
        <div class="w-full max-w-sm">

            <h1 class="font-serif text-3xl font-bold text-[#1a1a2e] text-center leading-tight mb-1.5">
                Selamat datang kembali.
            </h1>
            <p class="text-sm text-gray-400 text-center mb-9">
                Masuk untuk melanjutkan membaca dan menulis.
            </p>

            {{-- Google --}}
            <a href=""
               class="flex items-center justify-center gap-2.5 w-full px-4 py-3 rounded-lg border border-gray-200 bg-white text-sm font-medium text-[#1a1a2e] hover:border-gray-400 hover:bg-gray-50 transition mb-3 no-underline">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Lanjutkan dengan Google
            </a>

            {{-- Facebook --}}
            <a href=""
               class="flex items-center justify-center gap-2.5 w-full px-4 py-3 rounded-lg border border-gray-200 bg-white text-sm font-medium text-[#1a1a2e] hover:border-gray-400 hover:bg-gray-50 transition mb-5 no-underline">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
                </svg>
                Lanjutkan dengan Facebook
            </a>

            {{-- Divider --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-300">atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Form --}}
            <form method="POST" action="">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-[13px] font-medium text-gray-600 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                           placeholder="nama@email.com" />
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="password" class="block text-[13px] font-medium text-gray-600 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                           placeholder="••••••••" />
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mb-5">
                    <a href="" class="text-xs text-gray-400 hover:text-[#1a1a2e] transition">
                        Lupa kata sandi?
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-[#1a1a2e] text-white rounded-lg text-[15px] font-medium hover:bg-[#2d2d4a] transition tracking-wide">
                    signin
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Belum punya akun?
                <a href="{{route('signup')}}" class="text-[#1a1a2e] font-medium hover:underline">Signup now</a>
            </p>

            <p class="text-center text-[11px] text-gray-300 mt-7 leading-relaxed">
                Dengan masuk, kamu menyetujui
                <a href="/terms" class="text-gray-400 hover:underline">Syarat & Ketentuan</a>
                dan <a href="/privacy" class="text-gray-400 hover:underline">Kebijakan Privasi</a> Ujung Kalimat.
            </p>

        </div>
    </main>
    
@endsection