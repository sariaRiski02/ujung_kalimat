@extends('layouts.auth')

@section('tab')
    Privacy Policy
@endsection

@section('content')
    <main class="flex-1 px-6 py-12 lg:px-10">
        <div class="mx-auto flex w-full max-w-4xl flex-col gap-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="space-y-3">
                <p class="text-sm uppercase tracking-[0.25em] text-gray-400">Legal</p>
                <h1 class="font-serif text-3xl font-bold text-[#1a1a2e] md:text-4xl">Privacy Policy</h1>
                <p class="text-sm text-gray-500 md:text-base">
                    This Privacy Policy explains how Ujung Kalimat collects, uses, and protects your personal information.
                </p>
            </div>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">1. Information We Collect</h2>
                <p>We collect information you provide during registration, such as your name, email, username, and any content you publish on the platform.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">2. How We Use Your Information</h2>
                <p>Your information is used to provide account access, personalize your experience, improve our services, and communicate important updates.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">3. Data Protection</h2>
                <p>We take reasonable steps to protect your personal data from unauthorized access, misuse, or disclosure.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">4. Cookies and Analytics</h2>
                <p>We may use cookies and analytics tools to understand usage patterns and improve performance of the platform.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">5. Your Rights</h2>
                <p>You may contact us to request access, correction, or deletion of your personal information where applicable.</p>
            </section>

            <p class="text-xs text-gray-400">Last updated: 14 June 2026</p>
        </div>
    </main>
@endsection