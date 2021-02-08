<?php

use PHPMailer\PHPMailer\PHPMailer;
use App\Services\EmailService;

$postEmail = function ($C) {
    $emailsData = ['error'=> 'failed to send email'];

    //get SMTP Set from SMTP JSON
    $smtpSet = json_decode(SMTP_SET, 1);
    $toEmail = "rendyadisaputra@gmail.com";
    $toName  = "rendy";
    $subject = "say hello email";
    $htmlContent = "<strong> hi </strong> <em> broh </em>, I just wanna say thank u";

    $sendEmail = EmailService::set($smtpSet)->send($toEmail, $toName, $subject, $htmlContent);
    if($sendEmail){
        return $C->createResponse(['success'=> true], 200);
    }
    
    return $C->createResponse(['error'=> 'failed to send email'], 501);
    
};
