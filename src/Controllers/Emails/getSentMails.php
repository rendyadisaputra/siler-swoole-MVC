<?php

use App\Models\MongoModel;

$getSentMails = function ($resp) {
    $filterQuery = $resp->clientRequest->get;

    $EmailsModel = MongoModel::EmailsModel();
    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }
    $EmailsData = $EmailsModel->findEmails($filterQuery);
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($EmailsData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
