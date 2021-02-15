<?php

namespace StopLimit\Http\Repositories\Cache;

interface CacheRepositoryInterface
{
    /**
     * Insert or select exist cache with $key
     *
     * @param boolean $type type of stop limit order (buy/sell)
     *
     * @return array
     */
    public function getOrInsert($type);

    /**
     * Check if new stop-price exist in cache or no.
     *
     * @param $order
     * @param boolean $type type of stop limit order (buy/sell)
     *
     * @return void;
     */

    public function checkOrInsert($order, $type);

    /**
     * get cache with key.
     *
     * @param string $key cache name.
     *
     * @return array|mixed
     */
    public function get($key);

    /**
     * save to cache for ever
     *
     * @param  string $key cache name
     * @param array|mixed $data data ،he data we want to save.
     *
     * @return void
     */
    public function forever($key, $data);
}