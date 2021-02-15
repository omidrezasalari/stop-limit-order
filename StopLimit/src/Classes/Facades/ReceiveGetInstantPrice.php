<?php

namespace StopLimit\Classes\Facades;

use Illuminate\Support\Facades\Artisan;
use StopLimit\Interfaces\MessageInterface;

class ReceiveGetInstantPrice
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
     * Receive instant price and send to check:insert {instantPrice} command.
     */
    public function receivedThenSend(): void
    {
        $connection = config('stop_limit_config.trade_engine_connection');
        $channel = config('stop_limit_config.trade_engine_channel');
        $this->messageSender
            ->createQueue($channel, config('stop_limit_config.rabbitmq_queue_name'));

        $callback = function ($msg) {
            $this->messageSender->basicAck($msg);
            Artisan::call('check:insert', ['instantPrice' => $msg->body]);
        };
        $this->messageSender->basicQos($channel);
        $this->messageSender
            ->basicConsume($channel, config('stop_limit_config.rabbitmq_queue_name'), $callback);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $this->messageSender->close($connection, $channel);

    }
}