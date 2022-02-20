<?php

namespace Omidrezasalari\StopLimit\Classes\Facades;

use Omidrezasalari\StopLimit\Jobs\UpdateOrder;

class DefaultStopLimitQueue
{

    /**
     * A line for updating the information of stop order orders
     * that have been sent to the trading engine.
     *
     * @param array $orderIds
     * @param bool $status
     */
    public function dispatch(array $orderIds,$status): void
    {
        UpdateOrder::dispatchIf($status, $orderIds);
    }


}