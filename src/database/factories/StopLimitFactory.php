<?php

use Omidrezasalari\StopLimit\Http\Repositories\Cache\CacheRepositoryInterface;
use Omidrezasalari\StopLimit\Models\StopLimit;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(StopLimit::class, function (Faker $faker) {
    return [
        'stop_price' => random_int(280000000, 400000000),
        "limit_price" => random_int(280000000, 400000000),
        "amount" => $faker->randomFloat(),
        "owner" => random_int(1, 4),
        "type" => $faker->boolean,
        'client_order_id' => $faker->uuid,
    ];
});

$factory->afterCreating(StopLimit::class, function ($order) {

    $cacheRepository = resolve(CacheRepositoryInterface::class);
    $cacheRepository->getOrInsert($order->type);
    $cacheRepository->checkOrInsert($order, $order->type);
});
