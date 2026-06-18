@extends('layouts.auth')

@section('tab')
    Terms & Conditions
@endsection

@section('content')
    <main class="flex-1 px-6 py-12 lg:px-10">
        <div class="mx-auto flex w-full max-w-4xl flex-col gap-8 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="space-y-3">
                <p class="text-sm uppercase tracking-[0.25em] text-gray-400">Legal</p>
                <h1 class="font-serif text-3xl font-bold text-[#1a1a2e] md:text-4xl">Terms & Conditions</h1>
                <p class="text-sm text-gray-500 md:text-base">
                    These terms govern your use of Ujung Kalimat. By accessing or using our service, you agree to the rules below.
                </p>
            </div>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">1. Acceptance of Terms</h2>
                <p>By creating an account, reading content, or publishing on Ujung Kalimat, you acknowledge that you have read and accepted these Terms & Conditions.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">2. Use of the Service</h2>
                <p>You agree to use the platform lawfully and respectfully. You may not post harmful, illegal, misleading, or infringing content.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">3. Content Ownership</h2>
                <p>Content you publish remains your responsibility. By posting, you grant Ujung Kalimat permission to display and distribute your content on the platform.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">4. Account Responsibility</h2>
                <p>You are responsible for protecting your account credentials and all activity that happens under your account.</p>
            </section>

            <section class="space-y-4 text-sm text-gray-600 md:text-[15px]">
                <h2 class="text-xl font-semibold text-[#1a1a2e]">5. Changes to These Terms</h2>
                <p>We may update these Terms & Conditions from time to time. Continued use of the platform means you accept the latest version.</p>
            </section>

            <p class="text-xs text-gray-400">Last updated: 14 June 2026</p>
        </div>
    </main>
@endsection
