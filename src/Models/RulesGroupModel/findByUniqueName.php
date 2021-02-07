<?php

$findByUniqueName = function ($mongoModel, $args = null) {
    $name = $args[0];
    $getData = $mongoModel->DBaggregate([
            [
                '$match'=> [
                'name' => $name
            ]
        ]
    ]);
    return $getData['result'];
};
