@extends('layouts.workspace')

@section('tab')
    Reset Password
@endsection

@section('content')

    <main class="min-h-screen transition-all duration-300 flex items-start justify-center px-6 pt-20 pb-16"
          :class="open ? 'ml-56' : 'ml-14'">
        <div class="w-full max-w-sm">

            <h1 class="font-serif text-3xl font-bold text-[#1a1a2e] text-center leading-tight mb-1.5">
                Set a new password.
            </h1>
            <p class="text-sm text-gray-400 text-center mb-9">
                Choose a strong password you haven’t used before.
            </p>

            <form method="POST" action="{{ route('workspace.profile.reset-password.update') }}"
                  x-data="{ showPassword: false, showConfirmation: false }">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="password" class="block text-[13px] font-medium text-gray-600 mb-1.5">New password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                               autocomplete="new-password" minlength="8"
                               class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                               placeholder="••••••••" />
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition"
                                :aria-label="showPassword ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Minimum 8 characters.</p>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-[13px] font-medium text-gray-600 mb-1.5">Confirm new password</label>
                    <div class="relative">
                        <input :type="showConfirmation ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                               autocomplete="new-password"
                               class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                               placeholder="••••••••" />
                        <button type="button" @click="showConfirmation = !showConfirmation"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition"
                                :aria-label="showConfirmation ? 'Hide password' : 'Show password'">
                            <svg x-show="!showConfirmation" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showConfirmation" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-[#1a1a2e] text-white rounded-lg text-[15px] font-medium hover:bg-[#2d2d4a] transition tracking-wide">
                    Update password
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                <a href="{{ route('workspace.profile') }}" class="text-[#1a1a2e] font-medium hover:underline">Back to profile</a>
            </p>

        </div>
    </main>

@endsection
