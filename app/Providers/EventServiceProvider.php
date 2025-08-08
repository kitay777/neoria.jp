<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\WorkCreated;
use App\Listeners\SendWorkNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkCreated::class => [
            SendWorkNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false; // true にすると自動検出もされます
    }
}
