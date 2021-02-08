<?php
namespace App\Services;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService{

    public $mail; 

    static function set(array $smtpJson){
        $mail = new PHPMailer();

        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = 'smtp';
        
        $mail->SMTPDebug = $smtpJson['SMTPDebug'];
        $mail->SMTPAuth = $smtpJson['SMTPAuth'];
        $mail->SMTPSecure = $smtpJson['SMTPSecure'];
        $mail->Port = $smtpJson['Port'];
        $mail->Host = $smtpJson['Host'];
        $mail->Username = $smtpJson['Username'];
        $mail->Password = $smtpJson['Password'];
        $mail->SetFrom($smtpJson['email'], $smtpJson['name']);
        $mail->IsHTML(true);

        $EmailService = new EmailService();
        $EmailService->mail = $mail;

        return $EmailService;
    }

    function send(string $receiverEmail, string $receiverName,string $subject, string $htmlContent ){
        $mail = $this->mail;
        $mail->AddAddress($receiverEmail, $receiverName);
        $mail->Subject = $subject;
        $mail->MsgHTML($htmlContent);
        
        if (!$mail->Send()) {
            return false;
        } else {
            return true;
        }
    }
    
}