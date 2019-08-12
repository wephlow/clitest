<?php
namespace grace\clitest\databases;

use carlonicora\minimalism\library\database\AbstractDatabaseManager;

class actions extends AbstractDatabaseManager {
    protected $dbToUse = 'clitestdb';

    protected $fields = [
        'actionId'=>self::PARAM_TYPE_INTEGER,
        'nameId'=>self::PARAM_TYPE_INTEGER,
        'initialValue'=>self::PARAM_TYPE_INTEGER,
        'isAnalysed'=>self::PARAM_TYPE_INTEGER];

    protected $primaryKey = [
        'actionId'=>self::PARAM_TYPE_INTEGER];

    protected $autoIncrementField = 'actionId';

    public function loadNonAnalysed(){
        $sql = 'SELECT * FROM actions WHERE isAnalysed=?;';
        $parameters = ['i', 0];

        $response = $this->runRead($sql, $parameters);

        return($response);
    }

    public function loadFromName($name){
        $sql = 'SELECT * FROM actions WHERE name=?;';
        $parameters = ['s', $name];

        $response = $this->runRead($sql, $parameters);

        return($response);
    }
}