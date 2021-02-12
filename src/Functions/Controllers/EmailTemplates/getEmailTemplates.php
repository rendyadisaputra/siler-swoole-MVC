<?php
namespace App\Functions\Controllers\EmailTemplates;
use App\Functions\Model\EmailTemplatesModel;
use App\Functions\Controllers\Controller;

function getEmailTemplates($req, $resp){
    $filterQuery = $req->get;

    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }
    
    try {
        $EmailsData = EmailTemplatesModel\findEmailTemplates($filterQuery);
        return Controller\successResponse($EmailsData, 200);
        
    } catch (Error $e) {
        return Controller\errorResponse($e->getMessage, 500);
    }
    return false;
}
