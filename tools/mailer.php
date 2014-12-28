<?php
include_once ("../setup.php");

$sql = "SELECT COUNT(*) as numberOfReports FROM `blockedusers` WHERE `approvalStatus` = 0 ORDER BY `date` DESC";
$result = $database->execute($sql);
$result = $result[0]['numberOfReports'];

if($result > 0) {
	$mail = new Email();
	$to      = 'jhvisser@sympatico.ca';
	$toName = 'Justin';

	$subject = 'There are ' . $result . ' reports to review';
	$body = 'You need to review '. $result . ' items. Check <a href="https://jhvisser.com/hidefedora/html/review/review.php">the review page</a> and review the item(s)!';

	$mail->sendReminderEmail($to,$toName,$subject,$body);
}

?> 
