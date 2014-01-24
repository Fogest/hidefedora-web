<?php
include_once ("../../setup.php");

$page->page_title = 'Contact';
$page->page_header = 'test';

if (isset($_POST['name_contact'])) {
    require_once ('../../phplib/recaptchalib.php');
    $privatekey = "6LeJf-0SAAAAAIbFlnjv1KCyGBmj4h3dpQ7N_fEP";
    $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], 
            $_POST["recaptcha_challenge_field"], 
            $_POST["recaptcha_response_field"]);
    
    if (! $resp->is_valid) {
        // What happens when the CAPTCHA was entered incorrectly
        $errors[] = "The reCAPTCHA wasn't entered correctly. Go back and try it again." .
                 "(reCAPTCHA said: " . $resp->error . ")";
    }
    
    if (! isset($_POST['name_contact']) ||
             strlen(trim($_POST['name_contact'])) == 0)
        $errors[] = "No name inputed";
    if (! isset($_POST['email_contact']) ||
             strlen(trim($_POST['email_contact'])) == 0)
        $errors[] = "No email inputed";
    if (! isset($_POST['message_contact']) ||
             strlen(trim($_POST['message_contact'])) == 0)
        $errors[] = "No message inputed";
    
    if (! isset($errors) && ! (count($errors) > 0)) {
        $mail = new Email();
        
        if (! $mail->sendContactMail('fogestjv@gmail.com', 'Contact Form', 
                $_POST['message_contact'], $_POST['email_contact'], 
                $_POST['name_contact'])) {
            $page->html .= $alert->displayError("Error sending email!");
        } else {
            $page->html .= $alert->displaySuccess("Yay! Submission successful!");
        }
    } else {
        $page->html = "";
        for ($i = 0; $i < count($errors); $i ++) {
            $page->html .= $alert->displayError($errors[$i], true);
        }
    }
} else {
    
    $page->html .= '	<div class="span1"></div>
			<div class="span10 center">
			<div class="well well-small">
			<h3>Fill in the form below, and I will get a hold of you shortly.</h3>
			<hr/>
			<form name="contact" action="' . HTML_PATH .
             'footer/contact.php" method="post">
					<label>Name</label>
					<input type="text" class="input-xlarge required" placeholder="Name" name="name_contact" maxlength="230">
					<label>Email</label>
					<input type="email" class="input-xlarge required" placeholder="Email" name="email_contact" maxlength="230">
					<label>Message</label>
					<textarea rows="3" class="input-xlarge required" name="message_contact"></textarea><br/>';
    require_once ('../../phplib/recaptchalib.php');
    $publickey = "6LeJf-0SAAAAAD3NWdzJ-jcArmbPGCS1Mol6fL1X"; // you got this
                                                             // from the signup
                                                             // page
    $page->html .= recaptcha_get_html($publickey);
    $page->html .= '
			<button type="submit" class="btn btn-primary">Submit Message</a>
			</form>
			</div>
			</div>
			<div class="span1"></div>';
}

$page->display();
?>