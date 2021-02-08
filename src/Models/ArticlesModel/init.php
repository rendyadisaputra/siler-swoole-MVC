<?php

$init = function ($model) {
    $model->table = 'Articles';
    $model->collectionName = $model->table;
    // var_dump($model);

    return $model;
};
