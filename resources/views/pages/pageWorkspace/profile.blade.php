@extends('layouts.workspace')

@section('content')
@php
    $inputClass = 'w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg outline-none placeholder:text-gray-400 focus:border-gray-900 transition-colors';
    $labelClass = 'block text-xs font-medium text-gray-500 mb-1.5';
@endphp

<main class="min-h-screen transition-all duration-300 p-8" :class="open ? 'ml-56' : 'ml-14'"
      x-data="{ showDeletePhoto: false, showConfirm: {{ session('confirm_password') || $errors->confirmPassword->any() ? 'true' : 'false' }} }"
      x-effect="showConfirm && $nextTick(() => $refs.currentPassword?.focus())">

    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 font-serif">Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account information and security</p>
    </div>

    {{-- ===== OVERVIEW ===== --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6">
        <div class="flex flex-col sm:flex-row gap-5 sm:items-center">

            {{-- Profile photo --}}
            <div class="flex flex-col items-center gap-2 shrink-0">
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-full border border-gray-200 object-cover">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                        <span class="font-serif text-2xl font-semibold text-gray-500">{{ $user->initials() }}</span>
                    </div>
                @endif

                <div class="flex items-center gap-3 text-xs">
                    <form method="POST" action="{{ route('workspace.profile.photo.update') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" class="sr-only"
                               x-ref="photo" @change="$el.form.submit()">
                        <button type="button" @click="$refs.photo.click()"
                                class="font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            Change photo
                        </button>
                    </form>

                    @if($user->profile_photo_path)
                        <form id="delete-photo-form" method="POST" action="{{ route('workspace.profile.photo.delete') }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" @click="showDeletePhoto = true"
                                class="text-gray-400 hover:text-red-500 transition-colors">Remove</button>
                    @endif
                </div>
                @error('photo')
                    <p class="text-xs text-red-500 max-w-40 text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-lg font-bold text-gray-900 font-serif truncate">{{ $user->name }}</p>
                <p class="text-sm text-gray-400 mt-0.5 truncate">&#64;{{ $user->username }}</p>
                @if($user->bio)
                    <p class="text-sm text-gray-500 mt-2">{{ $user->bio }}</p>
                @endif
                @if($user->city || $user->country)
                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                        <i class="ti ti-map-pin text-sm"></i>
                        {{ collect([$user->city, $user->country])->filter()->implode(', ') }}
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-3 sm:w-80">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                    <p class="text-xl font-bold text-gray-900">{{ $articleCount }}</p>
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

    <form method="POST" action="{{ route('workspace.profile.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ===== BASIC INFORMATION ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-user text-base text-gray-400"></i>
                <p class="text-sm font-medium text-gray-900">Basic information</p>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label for="name" class="{{ $labelClass }}">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               required maxlength="255" autocomplete="name"
                               class="{{ $inputClass }} @error('name') border-red-300 @enderror">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="username" class="{{ $labelClass }}">Username</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">&#64;</span>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                                   required minlength="3" maxlength="30" autocomplete="username"
                                   class="{{ $inputClass }} pl-8 @error('username') border-red-300 @enderror">
                        </div>
                        @error('username')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers, dots, and underscores (3–30 characters)</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email" class="{{ $labelClass }}">Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" readonly disabled
                               class="{{ $inputClass }} bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">Email cannot be changed</p>
                    </div>

                </div>

                <div class="mt-4">
                    <label for="bio" class="{{ $labelClass }}">Tagline / short bio</label>
                    <input type="text" id="bio" name="bio" value="{{ old('bio', $user->bio) }}"
                           maxlength="160" placeholder="A short sentence about you..."
                           class="{{ $inputClass }} @error('bio') border-red-300 @enderror">
                    @error('bio')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Shown below your name on your profile</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ===== LOCATION ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-map-pin text-base text-gray-400"></i>
                <p class="text-sm font-medium text-gray-900">Location</p>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="{{ $labelClass }}">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}"
                           maxlength="100" placeholder="Manado"
                           class="{{ $inputClass }} @error('city') border-red-300 @enderror">
                    @error('city')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="country" class="{{ $labelClass }}">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $user->country) }}"
                           maxlength="100" placeholder="Indonesia"
                           class="{{ $inputClass }} @error('country') border-red-300 @enderror">
                    @error('country')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ===== ACTIONS ===== --}}
        <div class="flex justify-end gap-3">
            <a href="{{ url()->previous() }}"
               class="px-5 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-700 transition-colors">
                Save changes
            </button>
        </div>

    </form>

    {{-- ===== SECURITY ===== --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mt-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-lock text-base text-gray-400"></i>
            <p class="text-sm font-medium text-gray-900">Account security</p>
        </div>

        <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-gray-900">Password</p>
                <p class="text-xs text-gray-400 mt-0.5">You need to enter your current password before you can change it</p>
            </div>
            <button type="button" @click="showConfirm = true"
                    class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Reset password
            </button>
        </div>
    </div>

    {{-- ===== REMOVE PHOTO MODAL ===== --}}
    @if($user->profile_photo_path)
    <div x-show="showDeletePhoto" x-cloak x-transition.opacity
         @keydown.escape.window="showDeletePhoto = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40">
        <div @click.outside="showDeletePhoto = false"
             class="w-full max-w-sm bg-white border border-gray-200 rounded-xl overflow-hidden"
             role="alertdialog" aria-modal="true" aria-labelledby="delete-photo-title">
            <div class="p-5 flex gap-4">
                <div class="w-10 h-10 rounded-full bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                    <i class="ti ti-trash text-lg text-red-500"></i>
                </div>
                <div>
                    <p id="delete-photo-title" class="text-sm font-medium text-gray-900">Remove profile photo?</p>
                    <p class="text-sm text-gray-500 mt-1">Your photo will be removed and replaced with your initials.</p>
                </div>
            </div>
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" @click="showDeletePhoto = false"
                        class="px-5 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" form="delete-photo-form"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    Remove
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== PASSWORD CONFIRMATION MODAL ===== --}}
    <div x-show="showConfirm" x-cloak x-transition.opacity
         @keydown.escape.window="showConfirm = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40">
        <div @click.outside="showConfirm = false"
             class="w-full max-w-sm bg-white border border-gray-200 rounded-xl overflow-hidden"
             role="dialog" aria-modal="true" aria-labelledby="confirm-password-title">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <p id="confirm-password-title" class="text-sm font-medium text-gray-900">Confirm password</p>
                <button type="button" @click="showConfirm = false" aria-label="Close"
                        class="text-gray-400 hover:text-gray-900 transition-colors">
                    <i class="ti ti-x text-base"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('workspace.profile.confirm-password') }}" class="p-5">
                @csrf
                <p class="text-sm text-gray-500 mb-4">Enter your current password to continue.</p>

                <label for="current_password" class="{{ $labelClass }}">Current password</label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" id="current_password" name="current_password" required
                           autocomplete="current-password" placeholder="••••••••" x-ref="currentPassword"
                           class="{{ $inputClass }} pr-10 @if($errors->confirmPassword->has('current_password')) border-red-300 @endif">
                    <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-900 transition-colors"
                            :aria-label="show ? 'Hide password' : 'Show password'">
                        <i class="ti text-base" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                    </button>
                </div>
                @if($errors->confirmPassword->has('current_password'))
                    <p class="text-xs text-red-500 mt-1">{{ $errors->confirmPassword->first('current_password') }}</p>
                @endif

                <div class="flex justify-end gap-3 mt-5">
                    <button type="button" @click="showConfirm = false"
                            class="px-5 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-700 transition-colors">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
