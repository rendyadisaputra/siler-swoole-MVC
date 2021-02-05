<?php

declare(strict_types=1);

namespace App;

use App\Todo\Criteria\FindAll;
use App\Todo\Criteria\FindOne;
use App\Todo\Resolver\Query;
use App\Todo\Resolver\Save;
use App\Todo\Todos;

function create_resolvers(Todos $todos)
{
    // $tfunc = function ($root, $args) use ($todos) {
    //     return new Query($todos, new FindOne($args['id']));
    // };
    $getFindAll = new Query($todos, new FindAll());

    return [
        'Query' => [
            'todos' => new Query($todos, new FindAll()),
            'todolist' => function ($root, $args) use ($todos, $getFindAll) {
                return $getFindAll->getAllData()->find(new FindAll());
            },
            'todo' => function ($root, $args) use ($todos, $getFindAll) {
                // $data = $getFindAll->getAllData()->find(new FindOne($args['id']));

                $data = $getFindAll->getAllData()->find(new FindOne($args['id']));
                if (count($data) < 1) {
                    return [];
                } else {
                    return [$data];
                }
            },
        ],
        'Mutation' => [
            'saveTodo' => new Save($todos),
        ],
    ];
}
