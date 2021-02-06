<?php

$init = function ($model) {
    $model->table = 'Membership';
    $model->collectionName = $model->table;
    // var_dump($model);

    return $model;
};
