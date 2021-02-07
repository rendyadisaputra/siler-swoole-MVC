<?php

$createRoles = function ($mongoModel, $args = null) {
    $data = $args[0];
    $getMembersData = $mongoModel->DBInsert($data);
    return ['success'=>true];
};
