<?php

return [

    /*
    |--------------------------------------------------------------------------
    | RabbitMq config for connect to server
    |--------------------------------------------------------------------------
    */
    'rabbitmq_host' => "localhost",
    "rabbitmq_port" => 5672,
    "rabbitmq_username" => "guest",
    "rabbitmq_password" => "guest",
    "rabbitmq_queue_name" => "instant_price",
    "rabbitmq_vhost" => "/",

    /*
    |--------------------------------------------------------------------------
    | trading config key for buy/sell cache.
    |--------------------------------------------------------------------------
    */
    "buy_stop_limit_key" => "37aa8f438bbfa22fb414200178a162cb9dda3987",
    "sell_stop_limit_key" => "915054b4733e022e0a8420d28a6842a2bc2c9d44",

    /*
    |--------------------------------------------------------------------------
    | Configure sent and received messages
    |--------------------------------------------------------------------------
    */
    "exchange_name" => "stopLimits",
    "buy_route_key" => "buy_queue",
    "sell_route_key" => "sell_queue",
    "trade_engine_connection" => null,
    "trade_engine_channel" => null,
    /*
     |--------------------------------------------------------------------------
     | Configure authenticate class for get online user.
     |--------------------------------------------------------------------------
    */
    "authenticate-class" => \StopLimit\Classes\Facades\ApiAuth::class,
    /*
    |--------------------------------------------------------------------------
    | Stop limit process settings
    |--------------------------------------------------------------------------
   */
    "process-class" => StopLimit\Classes\Facades\EloquentProcess::class,
    "get-instant-price-class" => StopLimit\Classes\Facades\ReceiveGetInstantPrice::class,
    "received-messages-class" => StopLimit\Classes\Facades\ReceivedQueueMessage::class,

];
