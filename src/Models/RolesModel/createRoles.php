<?php

$createRoles = function ($mongoModel, $args = null) {
    $data = $args[0];
    $rolesData = $mongoModel->DBInsert($data);

    return ['success' => true, $rolesData];
};
