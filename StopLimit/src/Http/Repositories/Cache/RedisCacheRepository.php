<?php

namespace StopLimit\Http\Repositories\Cache;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use StopLimit\Http\Repositories\StopLimit\StopLimitRepositoryInterface;

class RedisCacheRepository implements CacheRepositoryInterface
{
    /**
     * @var StopLimitRepositoryInterface
     */
    private $stopLimitRepository;

    public function __construct(StopLimitRepositoryInterface $stopLimitRepository)
    {
        $this->stopLimitRepository = $stopLimitRepository;
    }

    /**
     * Insert or select exist cache with $key
     *
     * @param boolean $type type of stop limit order (buy/sell)
     *
     * @return array
     */
    public function getOrInsert($type)
    {
        if ($type) {
            return Cache::rememberForever(config("stop_limit_config.buy_stop_limit_key"), function () use ($type) {
                return $this->stopLimitRepository->selectToArray($type);
            });
        }
        return Cache::rememberForever(config("stop_limit_config.sell_stop_limit_key"), function () use ($type) {
            return $this->stopLimitRepository->selectToArray($type);
        });
    }

    /**
     * Check if new stop-price exist in cache or no.
     *
     * @param $order
     * @param boolean $type type of stop limit order (buy/sell)
     *
     * @return void;
     */
    public function checkOrInsert($order, $type)
    {
        $cachedStopPrices = collect($this->getOrInsert($type));

        if (!in_array($order->id, $cachedStopPrices->pluck('id')->all())) {

            $cachedStopPrices = $cachedStopPrices->push([
                "id" => $order->id,
                "amount" => $order->amount,
                "stop_price" => $order->stop_price,
                "limit_price" => $order->limit_price,
                "owner" => $order->owner,
                "created_at" => $order->created_at,
                "total_price" => $order->limit_price * $order->amount])->sortBy("stop_price");

            if ($type) {
                Cache::forever(config("stop_limit_config.buy_stop_limit_key"), $cachedStopPrices->all());
            } else {
                Cache::forever(config("stop_limit_config.sell_stop_limit_key"), $cachedStopPrices->all());
            }
        }
    }

    /**
     * get cache with key.
     *
     * @param string $key cache name.
     *
     * @return array|mixed
     */
    public function get($key): array
    {
        return Cache::get($key, []);
    }

    /**
     * save to cache for ever
     *
     * @param  string $key cache name
     * @param array|mixed $data data ،he data we want to save.
     *
     * @return void
     */
    public function forever($key, $data)
    {
        Cache::forever($key, $data);

    }
}