<?php

namespace App\Controllers;

use App\Root;

class Controller extends Root
{
    public static function __callStatic($name, $arguments)
    {
        return (new Controller())->$name();
    }

    public function __construct()
    {
        return parent::__construct(__DIR__);
    }
}