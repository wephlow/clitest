<?php
namespace grace\clitest;

use carlonicora\minimalism\abstracts\AbstractConfigurations;

class configurations extends AbstractConfigurations {
    public $rabbitMqConnection;

    /** @var string */
    private $queueName;

    const DB_ACTIONS = 'grace\\clitest\\databases\\actions';
    const DB_NAMES = 'grace\\clitest\\databases\\names';

    public function loadConfigurations() {
        parent::loadConfigurations();

        $this->rabbitMqConnection = array();
        list(
            $this->rabbitMqConnection['host'],
            $this->rabbitMqConnection['port'],
            $this->rabbitMqConnection['user'],
            $this->rabbitMqConnection['password']
            )= explode(',', getenv('RABBITMQ_CONNECTION'));

        $this->queueName = getenv('RABBITMQ_QUEUE_NAME');
    }

    /**
     * @return string
     */
    public function getQueueName() {
        return $this->queueName;
    }
}