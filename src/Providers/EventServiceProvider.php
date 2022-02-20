<?php

namespace Omidrezasalari\StopLimit\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Omidrezasalari\StopLimit\Events\StopLimitCreated;
use Omidrezasalari\StopLimit\Listeners\InsertToCache;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StopLimitCreated::class => [InsertToCache::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}