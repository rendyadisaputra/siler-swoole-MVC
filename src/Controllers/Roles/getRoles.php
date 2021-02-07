<?php

use App\Models\MongoModel;

$getRoles = function ($resp) {
    $RolesModel = MongoModel::RolesModel();
    $rolesData = $RolesModel->findRoles();
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($rolesData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
