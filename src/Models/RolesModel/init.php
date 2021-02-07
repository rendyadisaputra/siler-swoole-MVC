<?php

$init = function ($model) {
    $model->table = 'Auth_roles';
    $model->collectionName = $model->table;
    // var_dump($model);

    return $model;
};
