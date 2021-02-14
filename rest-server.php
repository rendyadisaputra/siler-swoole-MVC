<?php

require_once 'src/Configs/configs.php';
require_once 'vendor/autoload.php';

use Siler\Route;
use Siler\Swoole;

$port = 9502;
// Setup the logger.
$client = null;
if ($client != null) {
    $client = new \Swoole\Client(SWOOLE_SOCK_TCP);

    if (!$client->connect('127.0.0.1', $port, -1)) {
        exit("connect failed. Error: {$client->errCode}\n");
    }
}
$log = null;
$router = [];

function sendResponse($value, int $code = 200, $response = 'json'): void
{
    $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, authorization, api_key',
        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, DELETE',
        'Server' => 'Swiler',
    ];

    if ($response == 'json' && is_array($value)) {
        if (isset($value['code'])) {
            $code = $value['code'];
            unset($value['code']);
        }
        Swoole\json($value, $code, $headers);
    } elseif ($response == 'json' && (is_string($value) || is_bool($value) || is_numeric($value))) {
        Swoole\json(['return' => $value], $code, $headers);
    } else {
        Swoole\emit($value, $code, $headers);
    }
}

$table = new \Swoole\Table(1024*1024);
$table->column('value', \Swoole\Table::TYPE_STRING, 500);
$table->create();

$handler = function ($req, $res) use (&$router, $table) {
    // Route\files('src/Routings');
    $req->table = $table;
    $dir = __DIR__.'/src/Routings/';
    $requestMethod = strtolower($req->server['request_method']);
    $parseSlice = trim($req->server['request_uri'], '/');
    $fileExt1 = realpath($dir.$parseSlice.'.'.($requestMethod).'.php');
    $fileExt2 = realpath($dir.($parseSlice == '' ? '' : $parseSlice.'/').'index'.'.'.($requestMethod).'.php');
    $req->postBody = Swoole\raw();
    try {
        $resp = null;
        if (is_file($fileExt1)) {
            if (!isset($router[$fileExt1])) {
                include_once $fileExt1;
                if (!isset($run)) {
                    Swoole\emit('Not found', 404);

                    return false;
                }
                $router[$fileExt1] = $run;
            }

            $resp = $router[$fileExt1]($req, $resp);
            if (!is_null($resp)) {
                sendResponse($resp);
            }
        } elseif (is_file($fileExt2)) {
            if (!isset($router[$fileExt2])) {
                include_once $fileExt2;
                if (!isset($run)) {
                    Swoole\emit('Not found', 404);

                    return false;
                }
                $router[$fileExt2] = $run;
            }

            $resp = $router[$fileExt2]($req, $resp);
            if (!is_null($resp)) {
                sendResponse($resp);

                return true;
            }
        } else {
            Swoole\emit('Not found', 404);

            return false;
        }
    } catch (Exception $e) {
        /*
         * Logging error is here
         *
         * $e->getMessage(), $e->getTrace()
         */

        var_dump($e->getMessage(), json_encode($e->getTrace()[0]), JSON_PRETTY_PRINT);
        Swoole\json(['Error' => 'Something is wrong broh.. '], 500);

        return false;
    }
};

$server = Swoole\http($handler, $port = 9501);
$server->set([
    'enable_static_handler' => true,
    'document_root' => __DIR__.'/public',
    'upload_tmp_dir' => __DIR__.'/public/tmp',
    'package_max_length' => 1 * 1024 * 1024,
    'pid_file' => __DIR__.'/priv/server.pid',
    'worker_num' => 200,
]);




$server->start();
