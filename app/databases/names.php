<?php
namespace grace\clitest\databases;

use carlonicora\minimalism\library\database\AbstractDatabaseManager;

class names extends AbstractDatabaseManager {
    protected $dbToUse = 'clitestdb';

    protected $fields = [
        'nameId'=>self::PARAM_TYPE_INTEGER,
        'name'=>self::PARAM_TYPE_STRING,
        'totalValue'=>self::PARAM_TYPE_INTEGER,
        'averageValue'=>self::PARAM_TYPE_INTEGER];

    protected $primaryKey = [
        'nameId'=>self::PARAM_TYPE_INTEGER];

    public function loadFromName($name){
        $sql = 'SELECT * FROM names WHERE name=?;';
        $parameters = ['s', $name];

        $response = $this->runReadSingle($sql, $parameters);

        return($response);
    }
}