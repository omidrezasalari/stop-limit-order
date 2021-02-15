<?php

Route::group(['prefix' => "v1", 'namespace' => "V1", 'as' => 'v1.'], function () {

    Route::group(['middleware' => 'auth', 'as' => 'stoplimit.'], function () {
        Route::post('/stop-limits', "StopLimitController@store")->name('store');

    });

    Route::get('/price', function () {

//        return \Monitor\Models\OrderLog::first();

        $buy = \Illuminate\Support\Facades\Cache::get(config('stop_limit_config.buy_stop_limit_key'), []);
        $sell = \Illuminate\Support\Facades\Cache::get(config('stop_limit_config.sell_stop_limit_key'), []);
        dd(count($buy), count($sell));

    })->name('price');

});

