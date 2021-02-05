<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Siler\Route;
use Siler\Swoole;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('accessed.log', Logger::WARNING));

// add records to the log

// Setup the logger.
$client = null;
if ($client != null) {
    $client = new \Swoole\Client(SWOOLE_SOCK_TCP);

    if (!$client->connect('127.0.0.1', 9502, -1)) {
        exit("connect failed. Error: {$client->errCode}\n");
    }
}
// $log = null;
$handler = function ($req, $res) use ($client, $log) {
    if ($client != null) {
        go(function () use ($client) {
            $client->send("hello world\n");
        });
    }
    if ($log != null) {
        $log->error('Hello worlds');
    }
    go(function () use ($client) {
        Route\get('/', function () {
            Swoole\emit('Hello world', 200);
        });
        Route\get('/todos', function () {
            Swoole\json(['Hello world'], 200);
        });

        // None of the above short-circuited the response with Swoole\emit().
        Swoole\emit('Not found', 404);
    });
};

Swoole\http($handler, 9501)->start();
