<?php
declare(strict_types=1);

namespace App\Services;

use App\QueueHandlers\QueueHandler;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueService
{
    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;
    
    /**
     * @var AMQPStreamConnection
     */
    private $connection;
    
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            getenv('amqp_host'), getenv('amq_port'),
            getenv('amq_login'), getenv('amq_password'));
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('message', false, false, false, false);
    }
    
    public function createMessage(QueueHandler $handler)
    {
        $msg = new AMQPMessage(serialize($handler));
        $this->channel->basic_publish($msg, '', 'message');
    }
    
    public function consumeMessage()
    {
        $this->channel->basic_consume('message', '', false, true, false, false, function($msg){
            $class = unserialize($msg->body);
            if(is_object($class)) {
                $class();
            }
        });
    
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    
        $this->channel->close();
        $this->connection->close();
    }
    
    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}