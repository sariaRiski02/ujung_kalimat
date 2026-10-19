<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config as MidtransConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.header', function ($view) {
            $user = auth()->user();

            $subscriptionEndsAt = $user?->subscriptionEndsAt();

            $view->with([
                'isSubscribed' => $subscriptionEndsAt !== null,
                'subscriptionEndsAt' => $subscriptionEndsAt,
                'unreadNotificationsCount' => $user?->unreadNotifications()->count() ?? 0,
                'recentNotifications' => $user?->notifications()->latest()->limit(10)->get() ?? collect(),
            ]);
        });

        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$clientKey = config('services.midtrans.client_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production');
        MidtransConfig::$isSanitized = true;

    }
}
