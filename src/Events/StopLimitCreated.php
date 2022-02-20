<?php

namespace Omidrezasalari\StopLimit\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Omidrezasalari\StopLimit\Models\StopLimit;

class StopLimitCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stopLimitOrder;

    /**
     * Create a new event instance.
     *
     * @param StopLimit $stopLimitOrder
     */

    public function __construct(StopLimit $stopLimitOrder)
    {
        //
        $this->stopLimitOrder = $stopLimitOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
