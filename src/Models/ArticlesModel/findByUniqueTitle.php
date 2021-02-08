<?php

$findByUniqueTitle = function ($mongoModel, $args = null) {
    $title = $args[0];
    $getData = $mongoModel->DBaggregate([
            [
                '$match'=> [
                'title' => $title
            ]
        ]
    ]);
    return $getData['result'];
};
