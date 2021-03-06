<?php

declare(strict_types=1);

namespace App;

use App\Todo\InMemoryTodos;
use function Siler\Encoder\Json\decode;
use function Siler\Env\env_int;
use function Siler\GraphQL\schema;
use function Siler\Http\Request\raw;
use function Siler\Swoole\http;
use function Siler\Swoole\json;

$basedir = __DIR__;
require_once "$basedir/vendor/autoload.php";

$todos = new InMemoryTodos();
$type_defs = file_get_contents("$basedir/res/schema.graphql");
$schema = schema($type_defs, create_resolvers($todos));
$handler = function () use ($schema) {
    // var_dump(decode(raw()));

    return json(['hello world']);
};
$port = env_int('PORT', 8000);

echo "Listening on http://localhost:$port\n";
http($handler, $port)->start();
