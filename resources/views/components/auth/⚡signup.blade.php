<?php

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;


new class extends Component
{

    #[Validate('required|string|max:255')]
    public $name = '';
    #[Validate('required|string|max:255|unique:users,username')]
    public $username = '';
    #[Validate('required|email|unique:users,email')]
    public $email = '';
    
    public $otp_code = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'otp_code' => 'required|string|size:6',
        'password' => 'required|string|min:6|confirmed',
    ];

    public function signup(){
        $this->validate();

        $email = trim($this->email);

        $key = 'registration-otp-' . hash('sha256', $email);
        $otpKeyhashed = Cache::get($key);

        if (!$otpKeyhashed || !Hash::check($this->otp_code, $otpKeyhashed)) {
            dd(Hash::check($this->otp_code, $otpKeyhashed));
            session()->flash('registration_error', 'Invalid OTP code.');
            return;
        }
        
        try {
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            Auth::login($user);

        } catch (\Throwable $th) {
            session()->flash('registration_error', 'Registration failed. Please try again.');
        }

        

        return redirect()->route('workspace.dashboard');
    }


    public function sendOtpCode(){
        $this->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $email = trim($this->email);

        $key = 'registration-otp-' . hash('sha256', $email);
        
        
        if(RateLimiter::tooManyAttempts($key, 3)){
            session()->flash('otp_warning', 'Too many OTP requests. Please take a break and try again later.');
            return;
        }

        RateLimiter::hit($key, 600); // 600 seconds = 10 minutes

        $otp_code = (string) rand(100000, 999999);
        
        try {
            // kirim otp

            Mail::to($email)->send(new OtpMail($otp_code));
            Cache::put(
                $key,
                Hash::make($otp_code),
                now()->addMinutes(10)
            );

            session()->flash('otp_sent', 'OTP code has been sent to your email.');
        } catch (\Throwable $th) {
            
            session()->flash('otp_error', 'Failed to send OTP. Please try again.');
        }
    }



};
?>

<div>

    {{-- Flash notification badges (fixed top center) --}}
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 pointer-events-none">
        @foreach ([
            'registration_error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'icon' => 'text-red-400 hover:text-red-600'],
            'otp_error'          => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'icon' => 'text-red-400 hover:text-red-600'],
            'otp_warning'        => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700', 'icon' => 'text-yellow-400 hover:text-yellow-600'],
            'otp_sent'           => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'icon' => 'text-green-400 hover:text-green-600'],
        ] as $flashKey => $style)
            @if (session()->has($flashKey))
                <div x-data="{ show: true }" x-show="show" x-transition
                        class="flex items-start justify-between gap-3 mb-3 px-4 py-3 border {{ $style['bg'] }} {{ $style['border'] }} rounded-lg shadow-lg pointer-events-auto">
                    <p class="text-[13px] font-medium {{ $style['text'] }}">{{ session($flashKey) }}</p>
                    <button type="button" @click="show = false" aria-label="Dismiss notification"
                            class="shrink-0 {{ $style['icon'] }} transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            @endif
        @endforeach
    </div>



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
            <div class="relative" x-data="{ visible: false }">
                <input type="password" id="password" wire:model="password" required
                        :type="visible ? 'text' : 'password'"
                        class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                        placeholder="••••••••" />
                <button type="button" aria-controls="password"
                        :aria-label="visible ? 'Hide password' : 'Show password'"
                        @click="visible = !visible"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition">
                    <svg x-show="!visible" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg x-show="visible" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
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
            <div class="relative" x-data="{ visible: false }">
                <input type="password" id="password_confirmation" wire:model="password_confirmation" required
                        :type="visible ? 'text' : 'password'"
                        class="w-full px-3.5 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm text-[#1a1a2e] placeholder-gray-300 focus:outline-none focus:border-[#1a1a2e] transition"
                        placeholder="••••••••" />
                <button type="button" aria-controls="password_confirmation"
                        :aria-label="visible ? 'Hide password' : 'Show password'"
                        @click="visible = !visible"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1a1a2e] transition">
                    <svg x-show="!visible" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg x-show="visible" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24"
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