<?php


namespace Omidrezasalari\StopLimit\Classes\Facades;

use Illuminate\Support\Arr;
use Omidrezasalari\StopLimit\Facades\StopLimitQueueFacade;
use Omidrezasalari\StopLimit\Http\Repositories\Cache\CacheRepositoryInterface;
use Omidrezasalari\StopLimit\Interfaces\MessageInterface;

class EloquentProcess
{
    /**
     * @var MessageInterface
     */
    private $messageSender;
    /**
     * @var CacheRepositoryInterface
     */
    private $cacheRepository;

    /**
     * Constructor EloquentProcess class.
     *
     * @param MessageInterface $messageSender
     * @param CacheRepositoryInterface $cacheRepository
     */
    public function __construct(MessageInterface $messageSender, CacheRepositoryInterface $cacheRepository)
    {
        $this->messageSender = $messageSender;
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * build process step by step for running.
     *
     * @param integer|string $instantPrice instant price of BitCoin.
     *
     * @return  void
     */
    public function build($instantPrice)
    {
        list($sellStopLimits, $buyStopLimits) = $this->getValuesFromCache();

        list($fetchSellStopLimitsForFiltered, $fetchBuyStopLimitsForFiltered) =
            $this->isStopOrdersExist($sellStopLimits, $instantPrice, $buyStopLimits);

        $logData = ['start-time' => now(), 'instant-price' => $instantPrice];

        $messages = $this->sendToQueues($fetchBuyStopLimitsForFiltered,
            $fetchSellStopLimitsForFiltered, $logData);

        $sellOrderIds = Arr::pluck($fetchSellStopLimitsForFiltered, 'id');
        $buyOrderIds = Arr::pluck($fetchBuyStopLimitsForFiltered, 'id');

        $this->resetCache($sellStopLimits, $sellOrderIds, $buyStopLimits, $buyOrderIds);

        StopLimitQueueFacade::dispatch(Arr::collapse([$sellOrderIds, $buyOrderIds]), $messages['status']);
    }

    /**
     * get values from their caches.
     *
     * @return array
     */
    public function getValuesFromCache(): array
    {
        $sellStopLimits = $this->cacheRepository
            ->get(config("stop_limit_config.sell_stop_limit_key"));

        $buyStopLimits = $this->cacheRepository
            ->get(config("stop_limit_config.buy_stop_limit_key"));

        return array(collect($sellStopLimits), collect($buyStopLimits));
    }

    /**
     * In this section, the orders that need to be executed are refined.
     *
     * @param  array $sellStopLimits Sell stop limit order that store in cache.
     * @param integer $instantPrice instant price of Bitcoin.
     * @param array $buyStopLimits buy stop limit order that store in cache.
     *
     * @return array
     */
    public function isStopOrdersExist($sellStopLimits, $instantPrice, $buyStopLimits): array
    {
        $fetchSellStopLimitsForFiltered = $sellStopLimits
            ->filter(function ($stop) use ($instantPrice) {
                return $stop['stop_price'] <= $instantPrice;
            })->sortBy('created_at')->sortByDesc('stop_price')->toArray();

        $fetchBuyStopLimitsForFiltered = $buyStopLimits
            ->filter(function ($stop) use ($instantPrice) {
                return $stop['stop_price'] >= $instantPrice;
            })->SortBy('created_at')->SortBy('stop_price')->toArray();

        return array($fetchSellStopLimitsForFiltered, $fetchBuyStopLimitsForFiltered);
    }

    /**
     * Send to queue after orders have been successfully processed
     *
     * @param array $buyOrders
     * @param  array $sellOrders
     * @param array $logData
     * @return array
     */
    public function sendToQueues($buyOrders, $sellOrders, array $logData): array
    {
        try {
            $channel = config('stop_limit_config.trade_engine_channel');
            $this->messageSender->exchangeDeclare($channel, config('stop_limit_config.exchange_name'));
            $buyMessages = $this->messageSender->createMessage($buyOrders);
            $this->messageSender->publish($channel, $buyMessages,
                config('stop_limit_config.exchange_name'),
                config('stop_limit_config.buy_route_key'));

            $sellMessages = $this->messageSender->createMessage($sellOrders);
            $this->messageSender->publish($channel, $sellMessages,
                config('stop_limit_config.exchange_name'),
                config('stop_limit_config.sell_route_key'));

            return Arr::collapse([$logData, ['status' => 1, 'end_time' => now()]]);

        } catch (\Exception $exception) {
            return Arr::collapse([$logData,
                ['exception' => $exception->getMessage(), 'end_time' => now(), 'status' => 0]]);
        }
    }

    /**
     * Cache update after orders are executed.
     *
     * @param array $sellStopLimits All sell IDs that were in the cache.
     * @param array $sellOrderIds Sell ID of orders that have been processed.
     * @param array $buyStopLimits buys IDs that were in the cache
     * @param array $buyOrderIds Buy ID of orders that have been processed.
     *
     * @return  void
     */
    public function resetCache($sellStopLimits, $sellOrderIds, $buyStopLimits, $buyOrderIds): void
    {
        $sellCaches = $sellStopLimits
            ->whereNotIn('id', $sellOrderIds)->toArray();

        $buyCaches = $buyStopLimits
            ->whereNotIn('id', $buyOrderIds)->toArray();

        $this->cacheRepository->forever(config('stop_limit_config.sell_stop_limit_key'), $sellCaches);
        $this->cacheRepository->forever(config('stop_limit_config.buy_stop_limit_key'), $buyCaches);
    }


}