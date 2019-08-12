<?php
require_once('vendor/autoload.php');

use carlonicora\minimalism\bootstrapper;
use grace\clitest\configurations;
use \grace\clitest\managers\rabbitMqManager;

$bootstrapper = new bootstrapper('grace\\clitest');

$callback = function ($msg) {
    global $bootstrapper;

    $parameters = explode(' ', $msg->body);
    $parameterValues = array();

    $model = '';

    for ($parameterCount = 0; $parameterCount <= sizeof($parameters)-1; $parameterCount=$parameterCount+2){
        if ($parameters[$parameterCount] == 'type') {
            $model = $parameters[$parameterCount + 1];
        } else {
            $parameterValues[$parameters[$parameterCount]] = $parameters[$parameterCount+1];
        }
    }

    $controller = $bootstrapper->loadController($model, array(), $parameterValues);
    $controller->render();

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

/** @var configurations $configurations */
$configurations = $bootstrapper->getConfigurations();

$queue = new rabbitMqManager($configurations);
$queue->initialiseDispatcher($callback);

while (count($queue->channel->callbacks)) {
    $queue->channel->wait();
}

$queue = null;

exit;