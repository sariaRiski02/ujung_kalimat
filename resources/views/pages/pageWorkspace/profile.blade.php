@extends('layouts.workspace')

@section('content')
@php
    $inputClass = 'w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg outline-none placeholder:text-gray-400 focus:border-gray-900 transition-colors';
    $labelClass = 'block text-xs font-medium text-gray-500 mb-1.5';
@endphp

<main class="min-h-screen transition-all duration-300 p-8" :class="open ? 'ml-56' : 'ml-14'"
      x-data="{ showConfirm: {{ session('confirm_password') || $errors->confirmPassword->any() ? 'true' : 'false' }} }"
      x-effect="showConfirm && $nextTick(() => $refs.currentPassword?.focus())">

    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 font-serif">Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi dan keamanan akun kamu</p>
    </div>

    {{-- ===== OVERVIEW ===== --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6">
        <div class="flex flex-col sm:flex-row gap-5 sm:items-center">

            <div class="w-20 h-20 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0">
                <span class="font-serif text-2xl font-semibold text-gray-500">Rs</span>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-lg font-bold text-gray-900 font-serif">Rizky Saria</p>
                <p class="text-sm text-gray-400 mt-0.5">@@rizkysaria</p>
                <p class="text-sm text-gray-500 mt-2">Manis Buah</p>
            </div>

            <div class="grid grid-cols-3 gap-3 sm:w-80">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                    <p class="text-xl font-bold text-gray-900">0</p>
                    <p class="text-xs text-blue-600 font-medium mt-0.5">Articles</p>
                </div>
                <div class="bg-orange-50 border border-orange-100 rounded-xl p-3 text-center">
                    <p class="text-xl font-bold text-gray-900">0</p>
                    <p class="text-xs text-orange-600 font-medium mt-0.5">Followers</p>
                </div>
                <div class="bg-pink-50 border border-pink-100 rounded-xl p-3 text-center">
                    <p class="text-xl font-bold text-gray-900">0</p>
                    <p class="text-xs text-pink-600 font-medium mt-0.5">Following</p>
                </div>
            </div>

        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-100 rounded-xl px-4 py-3 mb-6">
        <i class="ti ti-circle-check text-base"></i>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="#" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ===== INFORMASI DASAR ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-user text-base text-gray-400"></i>
                <p class="text-sm font-medium text-gray-900">Informasi dasar</p>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label for="first_name" class="{{ $labelClass }}">Nama depan</label>
                        <input type="text" id="first_name" name="first_name" value="Rizky"
                               class="{{ $inputClass }} @error('first_name') border-red-300 @enderror">
                        @error('first_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="last_name" class="{{ $labelClass }}">Nama belakang</label>
                        <input type="text" id="last_name" name="last_name" value="Saria" class="{{ $inputClass }}">
                    </div>

                    <div>
                        <label for="username" class="{{ $labelClass }}">Username</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">@@</span>
                            <input type="text" id="username" name="username" value="rizkysaria"
                                   class="{{ $inputClass }} pl-8">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="{{ $labelClass }}">Email</label>
                        <input type="email" id="email" name="email" value="rizkysaria@example.com"
                               class="{{ $inputClass }}">
                    </div>

                </div>

                <div class="mt-4">
                    <label for="bio" class="{{ $labelClass }}">Tagline / bio singkat</label>
                    <input type="text" id="bio" name="bio"
                           value="{{ old('bio', 'Saya adalah seorang penulis yang suka berbagi pengetahuan.') }}"
                           maxlength="160"
                           placeholder="Kalimat pendek tentang kamu..."
                           class="{{ $inputClass }}">
                    <p class="text-xs text-gray-400 mt-1">Tampil di bawah nama di profil kamu</p>
                </div>
            </div>
        </div>

        {{-- ===== LOKASI ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-map-pin text-base text-gray-400"></i>
                <p class="text-sm font-medium text-gray-900">Lokasi</p>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="{{ $labelClass }}">Kota</label>
                    <input type="text" id="city" name="city" value="{{ old('city', 'Manado') }}"
                           placeholder="Manado" class="{{ $inputClass }}">
                </div>

                <div>
                    <label for="country" class="{{ $labelClass }}">Negara</label>
                    <input type="text" id="country" name="country" value="{{ old('country', 'Indonesia') }}"
                           placeholder="Indonesia" class="{{ $inputClass }}">
                </div>
            </div>
        </div>

        {{-- ===== ACTIONS ===== --}}
        <div class="flex justify-end gap-3">
            <a href="{{ url()->previous() }}"
               class="px-5 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-700 transition-colors">
                Simpan perubahan
            </button>
        </div>

    </form>

    {{-- ===== KEAMANAN ===== --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mt-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-lock text-base text-gray-400"></i>
            <p class="text-sm font-medium text-gray-900">Keamanan akun</p>
        </div>

        <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-gray-900">Password</p>
                <p class="text-xs text-gray-400 mt-0.5">Kamu perlu memasukkan password saat ini sebelum bisa menggantinya</p>
            </div>
            <button type="button" @click="showConfirm = true"
                    class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Reset password
            </button>
        </div>
    </div>

    {{-- ===== MODAL KONFIRMASI PASSWORD ===== --}}
    <div x-show="showConfirm" x-cloak x-transition.opacity
         @keydown.escape.window="showConfirm = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40">
        <div @click.outside="showConfirm = false"
             class="w-full max-w-sm bg-white border border-gray-200 rounded-xl overflow-hidden"
             role="dialog" aria-modal="true" aria-labelledby="confirm-password-title">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <p id="confirm-password-title" class="text-sm font-medium text-gray-900">Konfirmasi password</p>
                <button type="button" @click="showConfirm = false" aria-label="Tutup"
                        class="text-gray-400 hover:text-gray-900 transition-colors">
                    <i class="ti ti-x text-base"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('workspace.profile.confirm-password') }}" class="p-5">
                @csrf
                <p class="text-sm text-gray-500 mb-4">Masukkan password kamu saat ini untuk melanjutkan.</p>

                <label for="current_password" class="{{ $labelClass }}">Password saat ini</label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" id="current_password" name="current_password" required
                           autocomplete="current-password" placeholder="••••••••" x-ref="currentPassword"
                           class="{{ $inputClass }} pr-10 @if($errors->confirmPassword->has('current_password')) border-red-300 @endif">
                    <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-900 transition-colors"
                            :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                        <i class="ti text-base" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                    </button>
                </div>
                @if($errors->confirmPassword->has('current_password'))
                    <p class="text-xs text-red-500 mt-1">{{ $errors->confirmPassword->first('current_password') }}</p>
                @endif

                <div class="flex justify-end gap-3 mt-5">
                    <button type="button" @click="showConfirm = false"
                            class="px-5 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-700 transition-colors">
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
