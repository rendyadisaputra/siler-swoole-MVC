<?php

use App\Models\MongoModel;

$setEmails = function ($C) {
    $emailsData = false;
    // if (is_null($data = json_decode($C->postBody, 1))) {
    //     return $C->createResponse(['error' => 'unsupported format'], 402);
    // }

    // /* Checking minimum needed specs */
    // if (!$C->checkArraySpecs(['id'], $data)) {
    //     return $C->createResponse(['error' => 'invalid minimum json requirement'], 402);
    // }

    // /* name should be unique in lower case */
    // if (isset($data['name'])) {
    //     $data['name'] = strtolower(trim($data['name']));
    // }

    // $EmailsModel = MongoModel::EmailsModel();

    // /** Checking is Emails available */
    // $check = $EmailsModel->findBy(['_id' => $data['id']]);

    // if (empty($check['result'])) {
    //     return $C->createResponse(['error' => 'the role not found'], 403);
    // }

    // $emailsData = $EmailsModel->updateEmails($data['id'], $data);

    return $C->createResponse($emailsData, 200);
};
