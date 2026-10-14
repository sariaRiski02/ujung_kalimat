@extends('layouts.auth')

@section('tab')
    Verify OTP
@endsection

@section('content')

    <main class="flex-1 flex items-start justify-center px-6 pt-20 pb-16">
        <div class="w-full max-w-sm">

            <h1 class="font-serif text-3xl font-bold text-indigo-600 text-center leading-tight mb-1.5">
                Verify your email.
            </h1>
            <p class="text-sm text-gray-600 text-center mb-9">
                Enter your email and the 6-digit code we sent to verify your account.
            </p>

            <livewire:auth.verify-otp />

            <p class="text-center text-sm text-gray-600 mt-2">
                <a href="{{ route('signin') }}" class="hover:text-indigo-600 transition">Back to sign in</a>
            </p>

        </div>
    </main>

@endsection
