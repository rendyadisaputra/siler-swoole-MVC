<?php

use App\Models\MongoModel;

$getRoles = function ($resp) {
    $filterQuery = $resp->clientRequest->get;

    $RolesModel = MongoModel::RolesModel();
    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }
    $rolesData = $RolesModel->findRoles($filterQuery);
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($rolesData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
