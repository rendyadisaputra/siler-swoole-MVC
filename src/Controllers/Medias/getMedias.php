<?php


use App\Models\MongoModel;

$getMedias = function ($resp) {
    $filterQuery = $resp->clientRequest->get;

    $MediasModel = MongoModel::MediasModel();
    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }
    $MediasData = $MediasModel->findMedias($filterQuery);
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($MediasData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
