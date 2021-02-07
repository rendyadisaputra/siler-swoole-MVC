<?php

use App\Models\MongoModel;

$getRulesGroup = function ($resp) {
    $RulesGroupModel = MongoModel::RulesGroupModel();
    $rulesGroupData = $RulesGroupModel->findRulesGroup();
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($rulesGroupData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
