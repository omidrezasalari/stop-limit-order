<?php

namespace Omidrezasalari\StopLimit\Classes\Outputs;

use Illuminate\Http\Response;
use Omidrezasalari\StopLimit\Interfaces\ResponderInterface;

class JsonResponder implements ResponderInterface
{

    /**
     * Message when  new stop limit order created.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderCreated()
    {
        return response()->json(['message' => __('messages.order-created')], Response::HTTP_OK);
    }

    /**
     * Message when the order has already been registered.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderExist()
    {
        return response()->json(['message' => __("messages.order-exist")], Response::HTTP_CREATED);

    }
}