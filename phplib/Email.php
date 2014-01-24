<?php

class Email
{

    public function __construct ()
    {}

    public function sendMail ($to, $subject, $message, $from)
    {
        $headers = "From:" . $from;
        mail($to, $subject, $message, $headers);
    }
}
?>