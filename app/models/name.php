<?php
namespace grace\clitest\models;

use carlonicora\minimalism\abstracts\model;
use carlonicora\minimalism\library\database\databaseFactory;
use grace\clitest\configurations;
use grace\clitest\databases\actions;
use grace\clitest\databases\names;

class name extends model {
    public function generateData() {

        /** @var actions $actionsLoader */
        $actionsLoader = databaseFactory::create(configurations::DB_ACTIONS);

        /** @var names $namesLoader */
        $namesLoader = databaseFactory::create(configurations::DB_NAMES);

        $actionId = $this->parameterValues['actionId'];
        $action = $actionsLoader->loadFromId($actionId);

        $name = [];
        $name['name'] = $action['name'];
        $name['totalValue'] = $action['initialValue'];
        $name['averageValue'] = floatval(($name['averageValue'] + $action['initialValue']) / 2);

        $response = $namesLoader->update($name);

        return($response);
    }
}