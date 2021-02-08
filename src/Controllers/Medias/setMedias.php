<?php

use App\Models\MongoModel;

$setMedias = function ($C) {
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

    $MediasModel = MongoModel::MediasModel();

    /** Checking is Medias available */
    $check = $MediasModel->findBy(['_id' => $data['id']]);

    if (empty($check['result'])) {
        return $C->createResponse(['error' => 'the role not found'], 403);
    }

    $membersData = $MediasModel->updateMedias($data['id'], $data);

    return $C->createResponse($membersData, 200);
};
