<?php
namespace grace\clitest\models;

use carlonicora\minimalism\abstracts\model;
use carlonicora\minimalism\library\database\databaseFactory;
use grace\clitest\configurations;
use grace\clitest\databases\actions;
use grace\clitest\managers\rabbitMqManager;

class dispatcher extends model {
    /** @var configurations */
    protected $configurations;

    public function generateData() {

        $queueManager = new rabbitMqManager($this->configurations);

        /** @var actions $actionsLoader */
        $actionsLoader = databaseFactory::create(configurations::DB_ACTIONS);

        $actions = $actionsLoader->loadNonAnalysed();
        foreach (isset($actions) ? $actions : [] as $action){
            $message = [];
            $message['type'] = 'name';
            $message['actionId'] = $action['actionId'];

            $queueManager->dispatchMessage($message);

            $message = null;
        }
    }
}