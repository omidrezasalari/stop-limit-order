<?php


namespace Omidrezasalari\StopLimit\Facades;

/**
 * @method static build($argument);
 * @method static array getValuesFromCache();
 * @method static array isStopOrdersExist(array $sellStopLimits, integer | string $instantPrice, array $buyStopLimits);
 * @method static sendToQueues(array $buyOrders, array $sellOrders,array $logData);
 * @method static resetCache(array $sellStopLimits, array $sellOrderIds, array $buyStopLimits, array $buyOrderIds);
 */
class StopLimitProcessFacade extends BaseFacade
{
}