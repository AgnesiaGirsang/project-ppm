<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

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
        // View Composer untuk notifikasi di layout admin
        View::composer('layouts.admin', function ($view) {
            $unreadNotifications = Notification::whereNull('read_at')->latest()->take(5)->get();
            $unreadCount = Notification::whereNull('read_at')->count();

            $view->with(compact('unreadNotifications', 'unreadCount'));
        });
    }
}