<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use function Siler\Functional\puts;
use Siler\Swoole;

$echo = function ($frame) {
    var_dump($frame->data);
    // Swoole\broadcast($frame->data);
};

Swoole\websocket_hooks([
    'open' => puts("New connection\n"),
    'close' => puts("Someone leaves\n"),
]);

Swoole\websocket($echo, 9502)->start();
