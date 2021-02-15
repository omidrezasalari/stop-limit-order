<?php

namespace StopLimit\Interfaces;

interface ResponderInterface
{

    /**
     * Message when  new stop limit order created.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderCreated();

    /**
     * Message when the order has already been registered.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderExist();

}