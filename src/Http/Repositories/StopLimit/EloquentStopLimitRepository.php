<?php

namespace Omidrezasalari\StopLimit\Http\Repositories\StopLimit;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Omidrezasalari\StopLimit\Facades\AuthFacade;
use Omidrezasalari\StopLimit\Models\StopLimit;

class EloquentStopLimitRepository implements StopLimitRepositoryInterface
{
    /**
     * @var StopLimit $model
     */
    private $model;

    /**
     * EloquentStopLimitRepository constructor.
     *
     * @param StopLimit $model
     */

    public function __construct(StopLimit $model)
    {
        $this->model = $model;
    }

    /**
     * Create new stop-limit orders.
     *
     * @param array $data new stop-limit order detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($data)
    {
        $stopLimit = $this->model->create([
            'limit_price' => $data['limit-price'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'owner' => AuthFacade::userId(),
            'stop_price' => $data['stop-price'],
            'client_order_id' => Str::uuid(),
        ]);

        return $stopLimit;
    }

    /**
     * Update Orders with IDs.
     *
     * @param array $fields fields to be update.
     *
     * @return void
     */
    public function updateOrders($fields)
    {
        $this->model->whereIn('id', $fields)
            ->update(['status' => $this->model->performed(), 'updated_at' => Carbon::now()]);
    }


    /**
     * Select data from database with arrays
     * @param boolean $type
     *
     * @return array
     */
    public function selectToArray($type)
    {
        return $this->model->select('id', 'amount', "stop_price", 'limit_price', "owner", 'created_at')
            ->readyToProcess()->where('type', $type)
            ->orderBy("stop_price", "asc")->get()->toArray();

    }
}