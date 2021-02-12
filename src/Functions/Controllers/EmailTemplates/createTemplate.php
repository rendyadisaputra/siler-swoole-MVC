<?php
namespace App\Functions\Controllers\EmailTemplates;

use App\Functions\Model\EmailTemplatesModel;
use App\Functions\Controllers\Controller;

function createTemplate ($req, $resp) {
    
    if (is_null($data = json_decode($req->postBody, 1))) {
        return Controller\errorResponse( 'unsupported format', 402);
    }

    /* Checking minimum needed specs */
    if (!Controller\checkArraySpecs(['content', 'title'], $data)) {
        return Controller\errorResponse( 'invalid minimum json requirement', 402);
    }

    /** setup the default data */
    $defaultData = [
        'title' => 'your title is here ', // unique
        'content' => 'your content is here',
        'hashtags' => ['test', 'trial'],
        'category' => 'no-category',
        'slug' => 'no-slug', // unique
    ];

    /* title should be unique in lower case */
    $data['title'] = strtoupper(trim($data['title']));
   
    try{
        // $dataTemplate = EmailTemplatesModel\findEmailTemplates($filterQuery);
    
        /** Checking is EmailTemplates available */
        $check = EmailTemplatesModel\findByTemplateTitle($data['title']);
        if (!empty($check)) {
            return  Controller\errorResponse('the article already exist', 403);
        }

        $data = array_merge($defaultData, $data);
        $membersData = EmailTemplatesModel\createEmailTemplates($data);

        return  Controller\successResponse($membersData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};