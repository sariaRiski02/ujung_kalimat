<?php

use Livewire\Attributes\Validate;
use App\Models\User;
use Livewire\Component;


new class extends Component
{

    #[Validate('required|string|max:255')]
    public $name = '';
    #[Validate('required|string|max:255|unique:users,username')]
    public $username = '';
    #[Validate('required|email|unique:users,email')]
    public $email = '';
    #[Validate('required|string|size:6')]
    public $otp_code = '';
    #[Validate('required|string|min:6|confirmed')]
    public $password = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'otp_code' => 'required|string|size:6',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function signup(){
        $this->validate();

        try {
            User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
        } catch (Throwable $th) {
            session()->flash('registration_error', 'Registration failed. Please try again.');
        }
    }


    public function sendOtpCode(){
        
    }



};
?>

<div>
    {{-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh --}}
    <form method="POST" wire:submit="signup">

        {{-- Nama --}}
        <div class="mb-4">
            <label for="name" class="block text-[13px] font-medium text-gray-600 mb-1.5">Name</label>
            <input type="text" id="name" wire:model="name" value="{{ old('name') }}" required
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                    placeholder="John" />
            @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Username --}}
        <div class="mb-4">
            <label for="username" class="block text-[13px] font-medium text-gray-600 mb-1.5">Username</label>
            <input type="text" id="username" wire:model.live="username" value="{{ old('username') }}" required
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                    placeholder="@ujungkalimat" />
            @error('username')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-[13px] font-medium text-gray-600 mb-1.5">Email</label>
            <input type="email" id="email" wire:model.live="email" value="{{ old('email') }}" required
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                    placeholder="nama@email.com" />
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- OTP Code --}}
        <div class="mb-4">
            <label for="otp_code" class="block text-[13px] font-medium text-gray-600 mb-1.5">Verification Code</label>
            <div class="flex gap-2">
                <input type="text" id="otp_code" wire:model="otp_code" value="{{ old('otp_code') }}"
                        class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                        placeholder="Enter 6-digit code" />
                <button type="button" id="sendCodeBtn"
                        class="px-5 py-2.5 bg-[#1a1a2e] text-white rounded-lg text-[13px] font-medium hover:bg-[#2d2d4a] transition whitespace-nowrap"
                        wire:click="sendOtpCode">
                    Send Code
                </button>
            </div>
            <div id="otpTimer" class="text-xs text-gray-400 mt-1.5 hidden">
                Code sent! Resend in <span id="otpCount">60</span>s
            </div>
            @error('otp_code')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-[13px] font-medium text-gray-600 mb-1.5">Password</label>
            <div class="relative">
                <input type="password" id="password" wire:model.live="password" required
                        class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                        placeholder="••••••••" />
                <button type="button" aria-controls="password" aria-label="Show password" data-password-toggle
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition">
                    <svg data-eye-icon xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg data-eye-off-icon xmlns="http://www.w3.org/2000/svg" class="hidden w-4.5 h-4.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                        <line x1="2" y1="2" x2="22" y2="22"/>
                    </svg>
                </button>
            </div>
            <p class="text-[11px] text-gray-300 mt-1">Minimum 6 characters</p>
            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-2">
            <label for="password_confirmation" class="block text-[13px] font-medium text-gray-600 mb-1.5">Confirm password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" wire:model.live="password_confirmation" required
                        class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                        placeholder="••••••••" />
                <button type="button" aria-controls="password_confirmation" aria-label="Show password" data-password-toggle
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition">
                    <svg data-eye-icon xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg data-eye-off-icon xmlns="http://www.w3.org/2000/svg" class="hidden w-4.5 h-4.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                        <line x1="2" y1="2" x2="22" y2="22"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit"
                class="w-full mt-3 py-3 bg-[#1a1a2e] text-white rounded-lg text-[15px] font-medium hover:bg-[#2d2d4a] transition tracking-wide">
            Create Account
        </button>
    </form>
</div>