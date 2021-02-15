<?php

namespace StopLimit\Classes\Facades;

use Illuminate\Support\Facades\Event;
use StopLimit\Events\StopLimitCreated;
use StopLimit\Models\StopLimit;

class DefaultStopLimitEvent
{
    /**
     * dispatch event base on laravel event service.
     *
     * @param StopLimit $object new stop limit order created.
     *
     * @return void
     */
    public function dispatch($object)
    {
        Event::dispatch(new StopLimitCreated($object));
    }
}