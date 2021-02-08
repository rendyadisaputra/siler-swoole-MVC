<?php

$init = function ($model) {
    $model->table = 'Medias';
    $model->collectionName = $model->table;
    // var_dump($model);

    return $model;
};
