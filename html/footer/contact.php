<?php
include_once ("../../setup.php");

$page->page_title = 'Contact';
$page->page_header = 'Contact';

if(isset($_POST['name_contact'])) {
	$mail = new PHPMailer();
	$mail->SetFrom('jhvisser@sympatico.ca', 'Justin');
	$mail->AddReplyTo($_POST['emailAddress_Request'], $_POST['name_contact']);
	$mail->AddAddress('fogestjv@gmail.com', 'Justin');
	$mail->Subject = 'Contact Form Message';
	$mail->Body = $_POST['message_contact'];
	$mail->Body .= '</br>From '.$_POST['email_contact'].'';
	$mail->AltBody = $_POST['message_contact'];
	$mail->AltBody .= 'From '.$_POST['email_contact'].'';
	
	if(!$mail->Send()) {
		$page->html .= $alert->DisplayError("Error sending email!");
	}
} else {

	$page->html .= '	<div class="span1"></div>
					<div class="span10 center">
						<div class="well well-small">
							<h3>Fill in the form below, and I will get a hold of you shortly.</h3>
							<hr/>
							<form name="contact" action="'.HTML_PATH.'footer/contact.php" method="post">
								<label>Name</label>
								<input type="text" class="input-xlarge required" placeholder="Name" name="name_contact" maxlength="230">
								<label>Email</label>
								<input type="email" class="input-xlarge required" placeholder="Email" name="email_contact" maxlength="230">
								<label>Message</label>
								<textarea rows="3" class="input-xlarge required" name="message_contact"></textarea><br/>
								<button type="submit" class="btn btn-primary">Submit Message</a>
							</form>
						</div>
					</div>
					<div class="span1"></div>';
}

$page->display();
?>