<?php


use App\Models\MongoModel;

$getArticles = function ($resp) {
    $filterQuery = $resp->clientRequest->get;

    $ArticlesModel = MongoModel::ArticlesModel();
    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }
    $ArticlesData = $ArticlesModel->findArticles($filterQuery);
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($ArticlesData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
