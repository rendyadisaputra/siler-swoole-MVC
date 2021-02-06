<?php

namespace App;

use Siler\Swoole;

class Root
{
    private $calledClass = '';
    private $dir;
    // private $routedMethod = [];

    public function __call($name, $arguments)
    {
        if ($this->calledClass == '') {
            $dir = ($this->dir).'/'.$name;
            if (is_dir($dir)) {
                $this->calledClass = $name;

                return $this;
            } else {
                Swoole\json('dir class not found '.($dir), 501);
            }
        } elseif ($this->calledClass != '') {
            $file = ($this->dir).'/'.($this->calledClass).'/'.$name.'.php';
            if (is_file($file)) {
                include $file;

                return $$name($this);
            } else {
                Swoole\json('function file not found '.($file), 501);
            }
        }

        return null;
    }

    public function __construct($dir = __DIR__)
    {
        $this->dir = $dir;

        return $this;
    }

    public function sendResponse($value, int $code = 200, $response = 'json'): void
    {
        $headers = [
            'Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Headers'=>'Content-Type, Authorization, authorization, api_key',
            'Access-Control-Allow-Methods'=> 'GET, POST, OPTIONS, PUT, DELETE',
            'Server' => 'Swiler'
        ];
        if ($response == 'json' && is_array($value)) {
            Swoole\json($value, $code , $headers);
        } elseif ($response == 'json' && is_string($value)) {
            Swoole\json([$value], $code , $headers);
        } else {
            Swooole\emit($value, $code , $headers);
        }
    }
}
