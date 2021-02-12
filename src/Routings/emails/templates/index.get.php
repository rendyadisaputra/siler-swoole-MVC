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

$run = function ($req, $resp) { 
    return EmailTemplates\getEmailTemplates($req, $resp); 
};
