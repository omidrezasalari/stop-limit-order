<?php


namespace Omidrezasalari\StopLimit\Interfaces;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

interface MessageInterface
{
    /**
     * Connection for Rabbitmq server
     *
     * @return null|AMQPStreamConnection
     */
    public function connect();

    /**
     * Create channel with online connection
     *
     * @param AMQPStreamConnection $connection
     *
     *
     * @return AMQPChannel $channel
     */
    public function channel($connection);

    /**
     * Create new exchange for routing messages.
     *
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param string $name name of exchange.
     *
     * @param string $type The type of exchange to which messages
     * can be sent according to the law
     * @return void
     */
    public function exchangeDeclare($channel, string $name, $type = "direct");

    /**
     * Create the queue if it does not already exist.
     *
     * @param AMQPChannel $channel
     * @param string $name name for queue.
     * @param boolean $durability durable message flag.
     * @param boolean $exclusively exclusive message flag.
     * @return array|null
     */
    public function createQueue($channel, $name,$durability=true,$exclusively=false);

    /**
     * Create new messages for send
     * make message persistent with delivery_mode=>2
     *
     * @param array $messages list of jobs you want them to run.
     *
     * @return AMQPMessage $message
     */
    public function createMessage(array $messages);

    /**
     * Publish messages on channel
     *
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param AMQPMessage $messages of want send to consumer.
     * @param string $exchangeName the exchange name .
     * @param string $routeKey the route key of queue messages.
     *
     * @return boolean
     */
    public function publish($channel, $messages, $exchangeName, $routeKey);

    /**
     * Close all dependencies for publish messages.
     *
     * @param AMQPStreamConnection $connection
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     *
     * @throws \Exception
     *
     * @return void
     */
    public function close($connection, $channel);

    /**
     * Bind queue for receive messages base on route key.
     *
     * @param  string $queueName name the queue
     * @param string $exchange name of exchange
     * @param string $routeKey name of route key
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel the channel you connected to send.
     *
     * @return void
     */
    public function bindQueue($channel, $queueName, $exchange, $routeKey);

    /**
     * Return acknowledge message.
     *
     * @param string|object $message
     *
     * @return void
     */
    public function basicAck($message);

    /**
     * Specifies QoS
     *
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel the channel you connected to send.
     *
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException if the specified operation timeout was exceeded
     *
     * @return mixed
     */
    public function basicQos($channel);

    /**
     * Start a queue consumer.
     * This method asks the server to start a "consumer", which is a transient request for messages from a specific queue.
     * Consumers last as long as the channel they were declared on, or until the client cancels them.
     *
     * @param string $queue
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel the channel you connected to send.
     * @param boolean $noAck
     * @param callable|null $callback
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException if the specified operation timeout was exceeded
     * @throws \InvalidArgumentException
     * @return string
     */
    public function basicConsume($channel, $queue = '', $callback = null, $noAck = false);
}