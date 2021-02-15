<?php

namespace StopLimit\Http\Repositories\StopLimit;


interface StopLimitRepositoryInterface
{

    /**
     * Create new stop-limit orders.
     *
     * @param array $data new stop-limit order detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($data);

    /**
     * Update Orders with IDs.
     *
     * @param array $fields fields to be update.
     *
     * @return void
     */
    public function updateOrders($fields);


    /**
     * Select data from database with arrays
     * @param boolean $type
     *
     * @return array
     */

    public function selectToArray($type);

}