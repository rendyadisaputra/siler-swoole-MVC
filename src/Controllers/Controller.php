<?php

namespace App\Controllers;

use App\Root;
use App\Services\JwtToken;
use Siler\Swoole;

class Controller extends Root
{
    public $authData = [];
    public $clientRequest;
    public $postBody;

    public static function __callStatic($name, $arguments)
    {
        $args = null;
        // var_dump($arguments);
        if (isset($arguments[0])) {
            $args = $arguments[0];
        }

        return (new Controller($args))->$name();
    }

    public function __construct($args)
    {
        $this->clientRequest = Swoole\request();
        $this->postBody = Swoole\raw();
        if (is_array($args) && isset($args['auth'])) {
            $this->checkAuth();
        }

        return parent::__construct(__DIR__);
    }

    private function checkAuth()
    {
        $headers = $this->clientRequest->header;

        $isAuthAvailable = isset($headers['authorization']) || isset($headers['Authorization']);
        if (!$isAuthAvailable) {
            $this->sendResponse(['error' => 'unauthorized'], 401);
        }

        $token = isset($headers['authorization']) ? $headers['authorization'] : $headers['Authorization'];
        /** Check validation */
        $authData = null;
        try {
            $authData = JwtToken::decode($token);
        } finally {
            if (!$authData) {
                $this->sendResponse(['error' => 'unauthorized / invalid Token'], 401);
            }
        }
    }

    //@override
    public function runResponse($val)
    {
        if (is_array($val)) {
            if (isset($val['response']) && isset($val['code']) && is_numeric($val['code'])) {
                return $this->sendResponse($val['response'], $val['code']);
            }
        }

        return $this->sendResponse(['val' => $val], 200);
    }

    public function createResponse(array $val, int $code)
    {
        return ['response' => $val, 'code' => $code];
    }

    public function checkArraySpecs(array $arrayNeeded, array $Arrays)
    {
        foreach ($arrayNeeded as $val) {
            if (!isset($Arrays[$val])) {
                return false;
            } elseif (empty($Arrays[$val])) {
                return false;
            }
        }

        return true;
    }
}
