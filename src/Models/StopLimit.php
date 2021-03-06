<?php

namespace Omidrezasalari\StopLimit\Models;

use Illuminate\Database\Eloquent\Model;
use Omidrezasalari\StopLimit\Traits\EloquentModelTrait;

class StopLimit extends Model
{
    use EloquentModelTrait;

    protected $fillable = ["stop_price", "limit_price", "amount", "owner", "type", 'client_order_id', 'status'];

    protected $appends = ["total_price"];

}
