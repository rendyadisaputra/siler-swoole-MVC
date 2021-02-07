<?php

use App\Models\MongoModel;

$getRules = function ($resp) {
    $RulesModel = MongoModel::RulesModel();
    $rulesData = $RulesModel->findRules();
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($rulesData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
