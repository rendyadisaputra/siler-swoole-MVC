<?php

use App\Models\MongoModel;

$postRoles = function ($C) {
    if (is_null($data = json_decode($C->postBody, 1))) {
        return $C->createResponse(['error' => 'unsupported format'], 402);
    }

    /* Checking minimum needed specs */
    if (!$C->checkArraySpecs(['name', 'description'], $data)) {
        return $C->createResponse(['error' => 'invalid minimum json requirement'], 402);
    }

    /* name should be unique in lower case */
    $data['name'] = strtolower(trim($data['name']));

    $RolesModel = MongoModel::RolesModel();

    /** Checking is Roles available */
    $check = $RolesModel->findByUniqueName($data['name']);
    if (!empty($check)) {
        return $C->createResponse(['error' => 'the role already exist'], 403);
    }
    $data['rules_group'] = [];
    $membersData = $RolesModel->createRoles($data);

    try {
        $C->sendResponse($membersData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
