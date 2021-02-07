<?php

use App\Models\MongoModel;

$setRoles = function ($C) {
    if (is_null($data = json_decode($C->postBody, 1))) {
        return $C->createResponse(['error' => 'unsupported format'], 402);
    }

    /* Checking minimum needed specs */
    if (!$C->checkArraySpecs(['id'], $data)) {
        return $C->createResponse(['error' => 'invalid minimum json requirement'], 402);
    }

    /* name should be unique in lower case */
    if (isset($data['name'])) {
        $data['name'] = strtolower(trim($data['name']));
    }

    $RolesModel = MongoModel::RolesModel();

    /** Checking is Roles available */
    $check = $RolesModel->findBy(['_id' => $data['id']]);

    if (empty($check['result'])) {
        return $C->createResponse(['error' => 'the role not found'], 403);
    }

    $membersData = $RolesModel->updateRoles($data['id'], $data);

    return $C->createResponse($membersData, 200);
};
