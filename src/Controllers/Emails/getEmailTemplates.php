<?php

use App\Models\MongoModel;
use function App\Functions\Model\DBAggregate;

$getEmailTemplates = function ($resp) {
    return $resp->sendResponse([false], 200);
    
    $filterQuery = $resp->clientRequest->get;

    // $EmailsModel = MongoModel::EmailTemplatesModel();
    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }
    
    $EmailsData = DBAggregate([], 'Email_templates', 'chitatoz');
    // $EmailsData = $EmailsModel->findEmailTemplates($filterQuery);
    // var_dump($BookModel->table);
    try {
        return $resp->sendResponse($EmailsData, 200);
        
    } catch (Error $e) {
        var_dump('EERRROR HERE');
        return false;
    }
};

