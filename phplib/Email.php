<?php

class Email
{

    public function __construct ()
    {}

    public function sendContactMail ($to, $subject, $message, $from, $fromName)
    {
        $mail = new PHPMailer();
        $mail->SetFrom('admin@jhvisser.com', 'Justin');
        $mail->AddReplyTo($from, $fromName);
        $mail->AddAddress($to, $toName);
        $mail->Subject = 'Contact Form Message';
        $mail->Body = $message;
        $mail->Body .= ' - From ' . $from . '';
        $mail->AltBody = $message;
        $mail->AltBody .= ' - From ' . $from . '';
        
        if (! $mail->Send()) {
            return false;
        } else {
            return true;
        }
    }
}
?>