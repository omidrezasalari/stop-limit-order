<?php

namespace Omidrezasalari\StopLimit\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omidrezasalari\StopLimit\Events\StopLimitCreated;
use Omidrezasalari\StopLimit\Http\Repositories\Cache\CacheRepositoryInterface;

class InsertToCache
{
    /**
     * @var CacheRepositoryInterface
     */
    private $cacheRepository;

    /**
     * Create the event listener.
     *
     * @param CacheRepositoryInterface $cacheRepository
     */
    public function __construct(CacheRepositoryInterface $cacheRepository)
    {
        //
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * Handle the event.
     *
     * @param StopLimitCreated $event
     * @return void
     */
    public function handle(StopLimitCreated $event)
    {
        $this->cacheRepository->getOrInsert($event->stopLimitOrder->type);

        $this->cacheRepository->checkOrInsert($event->stopLimitOrder, $event->stopLimitOrder->type);
    }
}
