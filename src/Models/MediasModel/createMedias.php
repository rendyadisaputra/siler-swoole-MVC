<?php

$createMedias = function ($mongoModel, $args = null) {
    $data = $args[0];
    $MediasData = $mongoModel->DBInsert($data);

    return ['success' => true, $MediasData];
};
