<?php
namespace grace\clitest\managers;

use grace\clitest\configurations;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Exception;

class rabbitMqManager {
    /** @var configurations */
    private $configurations;

    /** @var AMQPStreamConnection*/
    private $connection;

    /** @var AMQPChannel */
    public $channel;

    /**
     * rabbitMqDispatcher constructor.
     * @param configurations $configurations
     */
    public function __construct($configurations) {
        $this->configurations = $configurations;

        $this->connection = new AMQPStreamConnection(
            $this->configurations->rabbitMqConnection['host'],
            $this->configurations->rabbitMqConnection['port'],
            $this->configurations->rabbitMqConnection['user'],
            $this->configurations->rabbitMqConnection['password']);
        $this->channel = $this->connection->channel();
    }

    public function __destruct() {
        $this->channel->close();
        try{
            $this->connection->close();
        } catch (Exception $e){

        }
    }

    public function initialiseDispatcher(&$callback){
        $this->channel->queue_declare($this->configurations->getQueueName(), false, true, false, false);

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($this->configurations->getQueueName() , '', false, false, false, false, $callback);
    }

    /**
     * @param array $message
     * @return bool
     */
    public function dispatchMessage($message){
        $response = false;

        $this->channel->queue_declare($this->configurations->getQueueName(), false, true, false, false);

        $data = '';
        foreach (isset($message) ? $message : [] as $parameterName=>$parameterValue){
            $data .= $parameterName . ' ' . $parameterValue . ' ';
        }
        $data = substr($data, 0, strlen($data)-1);

        $msg = new AMQPMessage(
            $data,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $this->channel->basic_publish($msg, '', $this->configurations->getQueueName());

        return($response);
    }
}