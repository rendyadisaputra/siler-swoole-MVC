<?php

use App\Models\MongoModel;

$postRules = function ($C) {
    if (is_null($data = json_decode($C->postBody, 1))) {
        return $C->createResponse(['error' => 'unsupported format'], 402);
    }

    /* Checking minimum needed specs */
    if (!$C->checkArraySpecs(['name', 'description', 'route'], $data)) {
        return $C->createResponse(['error' => 'invalid minimum json requirement'], 402);
    }

    /* name should be unique in lower case */
    $data['name'] = strtolower(trim($data['name']));
    $data['route'] = strtolower(trim($data['route']));

    $RulesModel = MongoModel::RulesModel();

    /** Checking is Rules available */
    $check = $RulesModel->findByUniqueName($data['name']);
    if (!empty($check)) {
        return $C->createResponse(['error' => 'the rule already exist'], 403);
    }

    $membersData = $RulesModel->createRules($data);

    try {
        $C->sendResponse($membersData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
