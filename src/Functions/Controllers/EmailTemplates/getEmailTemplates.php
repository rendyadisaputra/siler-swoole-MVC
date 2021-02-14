<?php
namespace App\Functions\Controllers\EmailTemplates;
use App\Functions\Model\EmailTemplatesModel;
use App\Functions\Controllers\Controller;
use App\Functions\Services\InternalCacher;

function getEmailTemplates($req, $resp){
    $filterQuery = $req->get;
    
    if (isset($filterQuery['id'])) {
        $filterQuery['_id'] = $filterQuery['id'];
        unset($filterQuery['id']);
    }

    try {

        /**
         * Internal Cache was enabled, it will help us to speed up the client request;
         **/ 

        $key = $req->server['request_method'].
            $req->server['request_uri'].
            $req->server['query_string'];
        $EmailsData = InternalCacher\get($key, 
            function() use ($filterQuery){
                return EmailTemplatesModel\findEmailTemplates($filterQuery);
            }, 
            time()+60);

        return Controller\successResponse($EmailsData, 200);
        
    } catch (Error $e) {
        return Controller\errorResponse($e->getMessage, 500);
    }
    return false;
}
