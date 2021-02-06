<?php

namespace App\Controllers;

use App\Root;
use Siler\Swoole;
use App\Services\JwtToken;

class Controller extends Root
{
    public $authData = [];
    public static function __callStatic($name, $arguments)
    {
        $args = null;
        if(isset($arguments[0])){
            $args = $arguments[0];
        }
        return (new Controller($arguments[0]))->$name();
    }

    public function __construct($args)
    {
       
        if(is_array($args) && isset($args['auth'])){
            $this->checkAuth();
        }
        return parent::__construct(__DIR__);
    }

    
    private function checkAuth(){
        $headers = Swoole\request()->header;
        $isAuthAvailable = isset($headers['authorization']) || isset($headers['Authorization']);
        if(!$isAuthAvailable){
            $this->sendResponse(['error'=> 'unauthorized'], 401);
        }

        $token = isset($headers['authorization']) ? $headers['authorization'] : $headers['Authorization'];
        /** Check validation */
        $authData = null;
        try{
            $authData = JwtToken::decode($token);
        } finally{
            if(!$authData){
                $this->sendResponse(['error'=> 'unauthorized / invalid Token'], 401);
            }
        };
        
    }
}
