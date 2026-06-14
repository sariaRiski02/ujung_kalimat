@extends('layouts.auth')


@section('tab')
    signin
@endsection

@section('content')

{{-- Main --}}
    <main class="flex-1 flex items-start justify-center px-6 pt-20 pb-16">
        <div class="w-full max-w-sm">

            <h1 class="font-serif text-3xl font-bold text-[#1a1a2e] text-center leading-tight mb-1.5">
                Welcome back.
            </h1>
            <p class="text-sm text-gray-400 text-center mb-9">
                Sign in to continue reading and writing.
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
                Continue with Google
            </a>

            {{-- Facebook --}}
            <a href=""
               class="flex items-center justify-center gap-2.5 w-full px-4 py-3 rounded-lg border border-gray-200 bg-white text-sm font-medium text-[#1a1a2e] hover:border-gray-400 hover:bg-gray-50 transition mb-5 no-underline">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
                </svg>
                Continue with Facebook
            </a>

            {{-- Divider --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-300">or</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('signin.post') }}">
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
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                               placeholder="••••••••" />
                        <button type="button" id="togglePassword" 
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition" 
                                aria-label="Show password">
                            {{-- Eye (password tersembunyi) --}}
                            <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            {{-- Eye-off (password terlihat) --}}
                            <svg id="iconEyeOff" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 hidden" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="2" y1="2" x2="22" y2="22"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-5">
                    <label for="remember" class="inline-flex items-center gap-2 text-xs text-gray-500 cursor-pointer">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-[#1a1a2e] focus:ring-[#1a1a2e]">
                        Remember me
                    </label>
                    <a href="" class="text-xs text-gray-400 hover:text-[#1a1a2e] transition">
                        Forgot password?
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-[#1a1a2e] text-white rounded-lg text-[15px] font-medium hover:bg-[#2d2d4a] transition tracking-wide">
                    Sign in
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Don’t have an account?
                <a href="{{route('signup')}}" class="text-[#1a1a2e] font-medium hover:underline">Sign up now</a>
            </p>

            <p class="text-center text-[11px] text-gray-300 mt-7 leading-relaxed">
                By signing in, you agree to the
                <a href="/terms" class="text-gray-400 hover:underline">Terms & Conditions</a>
                and <a href="/privacy" class="text-gray-400 hover:underline">Privacy Policy</a> of Ujung Kalimat.
            </p>

        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const passwordInput = document.getElementById('password');
                const toggleButton  = document.getElementById('togglePassword');
                const iconEye       = document.getElementById('iconEye');
                const iconEyeOff    = document.getElementById('iconEyeOff');

                if (!passwordInput || !toggleButton) return;

                toggleButton.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';

                    passwordInput.type = isPassword ? 'text' : 'password';

                    iconEye.classList.toggle('hidden', isPassword);
                    iconEyeOff.classList.toggle('hidden', !isPassword);

                    toggleButton.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                });
            });
        </script>
    @endpush
    
@endsection