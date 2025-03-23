<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\TransactionProcessed;
use App\Listeners\SendPaymentNotification;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        TransactionProcessed::class => [
            SendPaymentNotification::class,
        ],
    ];
    
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
        //
    }
}
