<?php

namespace StopLimit\Providers;

use Illuminate\Database\Eloquent\Factory;
use StopLimit\Classes\Facades\DefaultStopLimitEvent;
use StopLimit\Classes\Facades\DefaultStopLimitQueue;
use StopLimit\Classes\Message;
use StopLimit\Console\Commands\FakeStopLimit;
use StopLimit\Console\Commands\GetInstantPrice;
use StopLimit\Console\Commands\ReceivedQueueMessages;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use StopLimit\Classes\Outputs\JsonResponder;
use StopLimit\Console\Commands\CheckThenInsert;
//use StopLimit\Exceptions\StopLimitExceptionHandler;

use StopLimit\Facades\GetInstantPriceFacade;
use StopLimit\Facades\ReceivedQueueMessagesFacade;
use StopLimit\Facades\StopLimitProcessFacade;
use StopLimit\Facades\AuthFacade;
use StopLimit\Facades\StopLimitEventFacade;
use StopLimit\Facades\StopLimitQueueFacade;
use StopLimit\Http\Repositories\Cache\CacheRepositoryInterface;
use StopLimit\Http\Repositories\Cache\RedisCacheRepository;
use StopLimit\Http\Repositories\StopLimit\EloquentStopLimitRepository;
use StopLimit\Http\Repositories\StopLimit\StopLimitRepositoryInterface;
use StopLimit\Interfaces\MessageInterface;
use StopLimit\Interfaces\ResponderInterface;

//use Illuminate\Contracts\Debug\ExceptionHandler;

class StopLimitServiceProvider extends ServiceProvider
{
    protected $commands = [
        CheckThenInsert::class,
        ReceivedQueueMessages::class,
        FakeStopLimit::class,
        GetInstantPrice::class
    ];

    private $namespace = 'StopLimit\Http\Controllers';

    /**
     * @inheritdoc
     */

    public function register()
    {
        $this->registerFactories();
        $this->bindContracts();
    }

    /**
     * @inheritdoc
     */

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'stopLimit');

//        dd($this->app->routesAreCached());

        if (!$this->app->routesAreCached()) {
            $this->defineRoutes();
        }

        $this->publish();

        $this->queueDependencyForTradeEngine();

//        $this->app->bind(ExceptionHandler::class, StopLimitExceptionHandler::class);
    }

    /**
     * Publish dependencies that the user can change.
     *
     * @return void
     */

    public function publish()
    {
        $this->publishes([
            __DIR__ . '/../config/stop_limit_config.php' => config_path('stop_limit.php')
        ], 'stop-Limit-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang'),
        ], "stop-limit-translation");
    }

    /**
     * Register  package routes.
     *
     * @return void
     */
    private function defineRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . './../routes/routes.php');
    }

    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Register abstractions for usage.
     *
     * @return void
     */
    private function bindContracts()
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->bind(StopLimitRepositoryInterface::class, EloquentStopLimitRepository::class);
        $this->app->bind(CacheRepositoryInterface::class, RedisCacheRepository::class);
        $this->app->bind(ResponderInterface::class, JsonResponder::class);
        $this->app->bind(MessageInterface::class, Message::class);
        $this->config();
        AuthFacade::shouldProxyTo(config("stop_limit_config.authenticate-class"));
        StopLimitProcessFacade::shouldProxyTo(config("stop_limit_config.process-class"));
        GetInstantPriceFacade::shouldProxyTo(config("stop_limit_config.get-instant-price-class"));
        ReceivedQueueMessagesFacade::shouldProxyTo(config("stop_limit_config.received-messages-class"));
        StopLimitEventFacade::shouldProxyTo(DefaultStopLimitEvent::class);
        StopLimitQueueFacade::shouldProxyTo(DefaultStopLimitQueue::class);
        $this->commands($this->commands);


    }

    /**
     * specify config path and its name.
     */
    public function config()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/stop_limit_config.php',
            'stop_limit_config'
        );
    }

    public function queueDependencyForTradeEngine()
    {
        $messageSender = resolve(MessageInterface::class);
        $connection = $messageSender->connect();
        $channel = $messageSender->channel($connection);
        config()->set('stop_limit_config.trade_engine_channel', $channel);
        config()->set('stop_limit_config.trade_engine_connection', $connection);
    }


}