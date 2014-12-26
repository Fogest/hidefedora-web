<?php

class Email
{

    public function __construct ()
    {}

    public function sendContactMail ($to, $toName, $subject, $message, $from, $fromName)
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
    public function sendReminderEmail ($to, $toName, $subject, $message)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.mandrillapp.com';
        $mail->Port = 587;
        $mail->Username = 'fogestjv@gmail.com';
        $mail->Password = '_440zB2bFIO6jiVHMg-Ejw';


        $mail->SetFrom('admin@jhvisser.com', 'Hide Fedora Staff');
        $mail->AddReplyTo('admin@jhvisser.com', 'Hide Fedora Staff');
        $mail->AddAddress($to, $toName);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        
        if (! $mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo; 
            return false;
        } else {
            return true;
        }
    }
}
?>