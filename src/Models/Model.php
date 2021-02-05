<?php

namespace App\Models;

use App\Root;

class Model extends Root
{
    public $tableName;

    public static function __callStatic($name, $arguments)
    {
        $n = new Model();

        return (new Model())->$name()->init();
    }

    public function __construct()
    {
        return parent::__construct(__DIR__);
    }
}
