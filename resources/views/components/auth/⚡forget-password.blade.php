<?php

use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

new class extends Component
{
    public $email;
    public $errorMessage;
    public $successMessage;
    public $countDown = 0;
    
    
    
    public function sendOtp(){

        $this->errorMessage = '';
        $this->successMessage = '';

        $this->validate([
            'email' => 'required|email',
        ]);
        $email = strtolower(trim($this->email));

        
        
        if (!User::where('email', $email)->exists()) {
            $this->errorMessage = 'Email tidak ditemukan.';
            return;
        }
            
        $key = 'reset_password-' . hash('sha256', $email);
        $keyAttempts = 'reset_password_attempts-' . hash('sha256', $email);

        
        if(RateLimiter::tooManyAttempts($key, 1) && !Cache::has($keyAttempts)){
            $secondsLeft = RateLimiter::availableIn($key);
            $this->errorMessage = 'Kode sudah dikirim. Kamu bisa minta kode lagi setelah waktu tunggunya habis. Tunggu ' . $secondsLeft . ' detik.';
            $this->countDown = $secondsLeft;
            return;
        }
        // Generate otp
        $otp = (string) rand(100000, 999999);
        
        try {
            Mail::to($email)->send(new ForgetPasswordMail($otp));
            Cache::put($key, 
                    Hash::make($otp), 
                    now()->addMinutes(3)
                ); // 3 minutes    
            Cache::put($keyAttempts, 0, now()->addMinutes(3)); // 3 minutes for attempts
            RateLimiter::hit($key, 180); // 3 minutes
            $this->countDown = 180; // 3 minutes countdown for the frontend

            $this->successMessage = 'Kode OTP telah dikirim ke email Anda.';

        } catch (\Throwable $th) {
            $this->errorMessage = 'Gagal mengirim kode OTP. Silakan coba lagi.';
            return;
        }
        
    }

    public function clearError(){
        $this->errorMessage = '';
    }
};
?>
    {{-- Because you are alive, everything is possible. - Thich Nhat Hanh --}}
<div x-data="{
        length: 6,
        digits: Array(6).fill(''),
        get code() { return this.digits.join('') },
        boxes() { return this.$root.querySelectorAll('[data-otp]') },
        input(index, event) {
            const value = event.target.value.replace(/\D/g, '').slice(-1);
            this.digits[index] = value;
            event.target.value = value;
            if (value && index < this.length - 1) {
                this.boxes()[index + 1].focus();
            }
        },
        backspace(index, event) {
            if (!this.digits[index] && index > 0) {
                this.boxes()[index - 1].focus();
            }
        },
        paste(event) {
            const pasted = (event.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, this.length);
            if (!pasted) return;
            for (let i = 0; i < this.length; i++) {
                this.digits[i] = pasted[i] ?? '';
                this.boxes()[i].value = this.digits[i];
            }
            this.boxes()[Math.min(pasted.length, this.length - 1)].focus();
        }
    }">

    @if ($successMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-2.5 rounded-full border border-green-200 bg-green-50 px-4 py-2.5 shadow-lg"
            role="status"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <p class="text-xs font-medium text-green-600 whitespace-nowrap">{{ $successMessage }} Email sudah di kirim</p>
            <button type="button" @click="show = false"
                    class="ml-1 shrink-0 rounded-full p-0.5 text-green-400 hover:text-green-600 hover:bg-green-100 transition"
                    aria-label="Tutup notifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    @endif

    <form>
        @csrf

        @if ($errorMessage)
            <div
                class="mb-4 flex items-start gap-2.5 rounded-lg border border-red-100 bg-red-50 px-3.5 py-2.5"
            >
                <p class="text-xs text-red-500 leading-relaxed">
                    {{ $errorMessage }}
                </p>

                <button
                    type="button"
                    wire:click="clearError"
                    class="ml-auto shrink-0 text-red-400 hover:text-red-600"
                    aria-label="Tutup notifikasi"
                >
                    &times;
                </button>
            </div>
        @endif

        @session('otp')
            <div x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mb-4 flex items-start gap-2.5 rounded-lg border border-red-100 bg-red-50 px-3.5 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p class="text-xs text-red-500 leading-relaxed">{{ session('otp') }}</p>
                <button type="button" @click="show = false"
                        class="ml-auto shrink-0 rounded-md p-0.5 text-red-300 hover:text-red-500 hover:bg-red-100 transition"
                        aria-label="Tutup notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        @endsession

        <input type="hidden" name="otp" :value="code">
        

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-[#1a1a2e] mb-2">Email Address</label>
            <div class="flex gap-2">
                <input type="email" name="email" wire:model="email" id="email" 
                        value="{{ old('email', $email ?? '') }}"
                        required
                        placeholder="Enter your email"
                        class="flex-1 px-4 py-3 border @error('email') border-red-300 @else border-gray-200 @enderror rounded-lg text-[15px] text-[#1a1a2e] placeholder-gray-400 focus:outline-none focus:border-[#1a1a2e] transition" />
                <button type="button" wire:click="sendOtp"
                        class="cursor-pointer px-4 py-3 bg-[#1a1a2e] text-white rounded-lg text-[15px] font-medium hover:bg-[#2d2d4a] transition tracking-wide whitespace-nowrap">
                    Send Code
                </button>
            </div>
            @error('email')
                <p class="mt-2 flex items-center gap-1.5 text-xs text-red-500 leading-relaxed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex items-center justify-between gap-2 mb-6" @paste.prevent="paste($event)">
            <template x-for="(digit, index) in digits" :key="index">
                <input type="text" wire:model="digits[index]" inputmode="numeric" maxlength="1" autocomplete="one-time-code"
                        data-otp :aria-label="'Digit ' + (index + 1)"
                        @input="input(index, $event)"
                        @keydown.backspace="backspace(index, $event)"
                        @focus="$el.select()"
                        class="w-11 h-13 py-3 border border-gray-200 rounded-lg text-center text-lg font-semibold text-[#1a1a2e] focus:outline-none focus:border-[#1a1a2e] transition" />
            </template>
        </div>

        <p class="text-xs text-gray-400 text-center mb-6">Enter the 6-digit code sent to your email</p>

        <button type="submit" :disabled="code.length < length" wire:click="test"
                class="w-full py-3 bg-[#1a1a2e] text-white rounded-lg text-[15px] font-medium hover:bg-[#2d2d4a] transition tracking-wide disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-[#1a1a2e]">
            Verify code
        </button>
    </form>

    <p class="text-center text-sm text-gray-400 mt-6">
        Didn’t receive the code?
        <span
            x-data="{
                countDown: $wire.entangle('countDown'),
                timer: null,
                start() {
                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        if (this.countDown > 0) {
                            this.countDown--;
                        } else {
                            clearInterval(this.timer);
                            this.timer = null;
                        }
                    }, 1000);
                },
                get formatted() {
                    const m = String(Math.floor(this.countDown / 60)).padStart(2, '0');
                    const s = String(this.countDown % 60).padStart(2, '0');
                    return `${m}:${s}`;
                }
            }"
            x-init="
                if (countDown > 0) start();
                $watch('countDown', v => { if (v > 0 && !timer) start(); });
            "
        >
            <template x-if="countDown > 0">
                <span>| <span x-text="formatted"></span></span>
            </template>
            <template x-if="countDown <= 0">
                <button type="button" wire:click="sendOtp" class="text-[#1a1a2e] font-medium hover:underline">Resend</button>
            </template>
        </span>
    </p>
</div>

