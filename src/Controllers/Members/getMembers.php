<?php

use App\Models\MongoModel;

$getMembers = function ($resp) {
    $MembersModel = MongoModel::MembersModel();
    $membersData = $MembersModel->findMembers();
    // var_dump($BookModel->table);
    try {
        $resp->sendResponse($membersData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
