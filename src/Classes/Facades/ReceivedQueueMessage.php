<?php

namespace StopLimit\Classes\Facades;

use StopLimit\Interfaces\MessageInterface;

class ReceivedQueueMessage
{

    /**
     * @var MessageInterface
     */
    private $messageSender;

    /**
     * Create a new command instance.
     *
     * @param MessageInterface $messageSender
     */

    public function __construct(MessageInterface $messageSender)
    {
        $this->messageSender = $messageSender;
    }

    /**
     * received message from buy/sell queues.
     *
     * @return  void
     * @throws \Exception
     */

    public function receivedMessages()
    {
        $connection = config('stop_limit_config.trade_engine_connection');
        $channel = config('stop_limit_config.trade_engine_channel');

        list($queue_name, ,) = $this->messageSender->createQueue($channel, "", false, true);

        $severities = [config('stop_limit_config.buy_route_key'), config('stop_limit_config.sell_route_key')];

        foreach ($severities as $severity) {
            $this->messageSender
                ->bindQueue($channel, $queue_name, config('stop_limit_config.exchange_name'), $severity);
        }

        $callback = function ($msg) {
            echo ' [x] ', $msg->delivery_info['routing_key'], ':->', $msg->body, "\n";
        };

        $this->messageSender->basicConsume($channel, $queue_name, $callback, true);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $this->messageSender->close($connection, $channel);
    }
}