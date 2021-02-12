<?php
/**
 * POST /emails/templates/ | Creating new Template emails
 */

use App\Functions\Controllers\EmailTemplates;

$run = function ($req, $resp) { 
    return EmailTemplates\createTemplate($req, $resp); 
};
