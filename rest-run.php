<?php

require_once 'src/Configs/configs.php';
require_once 'vendor/autoload.php';

use Siler\Route;
use Siler\Swoole;

// Setup the logger.
$client = null;
if ($client != null) {
    $client = new \Swoole\Client(SWOOLE_SOCK_TCP);

    if (!$client->connect('127.0.0.1', 9502, -1)) {
        exit("connect failed. Error: {$client->errCode}\n");
    }
}
$log = null;
$router = [];
$handler = function ($req, $res) use (&$router) {
    // Route\files('src/Routings');
    $dir = __DIR__.'/src/Routings/';
    $requestMethod = strtolower($req->server['request_method']);
    $parseSlice = trim($req->server['request_uri'], '/');
    $fileExt1 = $dir.$parseSlice.'.'.($requestMethod).'.php';
    $fileExt2 = $dir.($parseSlice == '' ? '' : $parseSlice.'/').'index'.'.'.($requestMethod).'.php';

    try {
        if (is_file($fileExt1)) {
            if (!isset($router[$fileExt1])) {
                include_once $fileExt1;
                $router[$fileExt1] = $run;
            }

            $router[$fileExt1]();
        } elseif (is_file($fileExt2)) {
            if (!isset($router[$fileExt2])) {
                include_once $fileExt2;
                $router[$fileExt2] = $run;
            }

            $router[$fileExt2]();
        } else {
            Swoole\emit('Not found', 404);
        }
    } catch (Exception $e) {
        /*
         * Logging error is here
         *
         * $e->getMessage(), $e->getTrace()
         */

        // var_dump($e->getMessage(), json_encode($e->getTrace()[0]), JSON_PRETTY_PRINT);
        Swoole\json(['Error' => 'Something is wrong broh.. '], 500);
    }
};

Swoole\http($handler, 9501)->start();
