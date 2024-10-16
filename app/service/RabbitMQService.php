<?php

namespace App\service;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQService
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $this->channel = $this->connection->channel();
    }

    public function sendMessage(string $queue, string $message)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
