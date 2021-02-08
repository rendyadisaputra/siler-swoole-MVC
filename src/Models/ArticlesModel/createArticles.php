<?php

$createArticles = function ($mongoModel, $args = null) {
    $data = $args[0];
    $ArticlesData = $mongoModel->DBInsert($data);

    return ['success' => true, $ArticlesData];
};
