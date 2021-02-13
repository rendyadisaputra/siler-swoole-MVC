<?php
/**
 * GET /emails/templates/ | Get email template lists.
 * GET /emails/templates?slug=no-slug | You can filtering using the Query string
 * example of query strings:
 * ?id=987a8df6y298y9asdf98
 * ?title=MEMBER+REGISTRATION
 * ?slug=no-slug
 */
use App\Functions\Controllers\EmailTemplates;
use App\Functions\Controllers\Auth;

$run = function ($req, $resp) { 
    $modifiedReq = $req;
    return (!Auth\checkValidation($modifiedReq)) ? 
        $modifiedReq->errorResponse : EmailTemplates\getEmailTemplates($modifiedReq, $resp) ;
};