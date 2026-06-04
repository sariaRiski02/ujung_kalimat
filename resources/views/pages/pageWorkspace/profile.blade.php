@extends('layouts.workspace')

@section('content')
<div class="min-h-screen transition-all duration-300 p-8 bg-gray-50" :class="open ? 'ml-56' : 'ml-14'"">

    {{-- ===== OVERVIEW CARD ===== --}}
    <div class="border-2 border-amber-400 rounded-2xl p-7 mb-6 flex gap-6 items-start">

        {{-- Avatar --}}
        <div class="w-20 h-20 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center flex-shrink-0">
            <span class="font-serif text-2xl font-semibold text-stone-500">
                Rs
            </span>
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <p class="font-serif text-xl font-semibold tracking-tight">Rizky Saria</p>
            <p class="text-xs text-stone-400 mt-0.5 mb-2">@@rizkysaria</p>
            <p class="font-serif italic text-sm text-stone-500 leading-relaxed mb-3">
                Manis Buah
            </p>
            @if(false)
            <p class="text-sm text-stone-500 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                Manado, Indonesia
            </p>
            @endif
            <div class="flex gap-5 mt-3 pt-3 border-t border-stone-100">
                <div>
                    <p class="text-base font-medium">0</p>
                    <p class="text-xs text-stone-400 mt-0.5">Articles</p>
                </div>
                <div>
                    <p class="text-base font-medium">0</p>
                    <p class="text-xs text-stone-400 mt-0.5">Followers</p>
                </div>
                <div>
                    <p class="text-base font-medium">0</p>
                    <p class="text-xs text-stone-400 mt-0.5">Following</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="border border-stone-200 rounded-2xl p-7 bg-white">

        @if(session('success'))
        <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="#">
            @csrf
            @method('PUT')

            {{-- Informasi Dasar --}}
            <p class="font-serif text-sm font-semibold text-stone-500 pb-2.5 border-b border-stone-100 mb-5">Informasi dasar</p>

            <div class="grid grid-cols-2 gap-4">

                {{-- Nama Depan --}}
                <div>
                    <label for="first_name" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nama depan</label>
                    <input type="text" id="first_name" name="first_name"
                           value="Rizky"
                           class="w-full px-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors @error('first_name') border-red-300 @enderror">
                    @error('first_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama Belakang --}}
                <div>
                    <label for="last_name" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nama belakang</label>
                    <input type="text" id="last_name" name="last_name"
                           value="Saria"
                           class="w-full px-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Username</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm">@</span>
                        <input type="text" id="username" name="username"
                               value="@@rizkysaria"
                               class="w-full pl-7 pr-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Email</label>
                    <input type="email" id="email" name="email"
                           value="rizkysaria@example.com"
                           class="w-full px-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                </div>

            </div>

            {{-- Tagline / Bio --}}
            <div class="mt-4">
                <label for="bio" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Tagline / bio singkat</label>
                <input type="text" id="bio" name="bio"
                       value="{{ old('bio', 'Saya adalah seorang penulis yang suka berbagi pengetahuan.') }}"
                       maxlength="160"
                       placeholder="Kalimat pendek tentang kamu..."
                       class="w-full px-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                <p class="text-[11px] text-stone-400 mt-1">Tampil di bawah nama di profil kamu</p>
            </div>

            <div class="border-t border-stone-100 my-6"></div>

            {{-- Lokasi --}}
            <p class="font-serif text-sm font-semibold text-stone-500 pb-2.5 border-b border-stone-100 mb-5">Lokasi</p>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label for="city" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kota</label>
                    <input type="text" id="city" name="city"
                           value="{{ old('city', 'Manado') }}"
                           placeholder="Manado"
                           class="w-full px-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                </div>

                <div>
                    <label for="country" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Negara</label>
                    <input type="text" id="country" name="country"
                           value="{{ old('country', 'Indonesia') }}"
                           placeholder="Indonesia"
                           class="w-full px-3.5 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                </div>

            </div>

            <div class="border-t border-stone-100 my-6"></div>

            {{-- Keamanan --}}
            <p class="font-serif text-sm font-semibold text-stone-500 pb-2.5 border-b border-stone-100 mb-5">Keamanan akun</p>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label for="password" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Password baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               class="w-full px-3.5 pr-10 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors @error('password') border-red-300 @enderror">
                        <button type="button" onclick="togglePw('password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Konfirmasi password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="••••••••"
                               class="w-full px-3.5 pr-10 py-2.5 text-sm border border-stone-200 rounded-xl bg-white text-stone-800 outline-none focus:border-amber-400 transition-colors">
                        <button type="button" onclick="togglePw('password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        </button>
                    </div>
                </div>

            </div>
            <p class="text-[11px] text-stone-400 mt-2">Kosongkan jika tidak ingin mengganti password</p>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-stone-100">
                <a href="{{ url()->previous() }}"
                   class="px-5 py-2.5 text-sm text-stone-500 border border-stone-200 rounded-full hover:bg-stone-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium bg-stone-900 text-white rounded-full hover:bg-stone-700 transition-colors">
                    Simpan perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('svg path:first-child').setAttribute('d',
        isHidden
        ? 'M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88'
        : 'M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z'
    );
}
</script>
@endpush
@endsection