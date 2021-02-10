<?php

$init = function ($model) {
    $model->table = 'Email_templates';
    $model->collectionName = $model->table;
    // var_dump($model);

    return $model;
};
