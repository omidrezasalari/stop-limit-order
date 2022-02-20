<?php

namespace Omidrezasalari\StopLimit\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Omidrezasalari\StopLimit\Http\Repositories\StopLimit\StopLimitRepositoryInterface;

class UpdateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderIds;

    public $retryAfter = 3;

    /**
     * UpdateOrder constructor.
     * @param $orderIds
     */
    public function __construct($orderIds)
    {
        $this->orderIds = $orderIds;
    }

    /**
     * handle UpdateOrder job.
     *
     *
     * @param StopLimitRepositoryInterface $stopLimitRepository
     */
    public function handle(StopLimitRepositoryInterface $stopLimitRepository)
    {
        if (count($this->orderIds) <= 20000) {
            $stopLimitRepository->updateOrders($this->orderIds);
        }
        else {
            $orderSlices = array_chunk($this->orderIds, 1000);
            foreach ($orderSlices as $orders) {
                $stopLimitRepository->updateOrders($orders);
            }
        }
    }


}