<?php

$init = function ($model) {
    $model->table = 'Auth_rules';
    $model->collectionName = $model->table;
    // var_dump($model);

    return $model;
};
