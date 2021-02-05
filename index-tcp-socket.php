<?php

require_once 'vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('accessed-socked.log', Logger::WARNING));

// add records to the log

define('TCP_HOST', '192.168.56.66');
define('TCP_PORT', 9502);

$server = new Swoole\Server('0.0.0.0', 9502);
$server->on('connect', function ($server, $fd) {
    // echo "New connection established: #{$fd}.\n";
});
$server->on('receive', function ($server, $fd, $from_id, $data) use ($log) {
    // echo "Echo to #{$fd}: \n".$data;
    $log->error('Hello worlds');
    sleep(3);
    // $server->send($fd, "Echo to #{$fd}: \n".$data);
    // $server->close($fd);
});
$server->on('close', function ($server, $fd) {
    // echo "Connection closed: #{$fd}.\n";
});
$server->start();
