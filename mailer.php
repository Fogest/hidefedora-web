<?php
include_once ("setup.php");

$sql = "SELECT COUNT(*) as numberOfReports FROM `blockedusers` WHERE `hasBeenEmailed` = 0 AND `approvalStatus` = 0 ORDER BY `date` DESC";
$result = $database->execute($sql);
$result = $result[0]['numberOfReports'];

if($result > 0) {
	$to      = 'jhvisser@sympatico.ca';
	$subject = 'There are ' . $result . ' reports to review';
	$body = 'You need to review '. $result . ' items. Check <a href="https://jhvisser.com/hidefedora/html/review/review.php">the review page</a> and review the item(s)!';

	$headers = 'From: "Hide Fedora" <admin@jhvisser.com>' . "\r\n";
	$headers .= 'Reply-To: "Hide Fedora" <admin@jhvisser.com>' . "\r\n";

	$headers  .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html;charset=iso-8859-1' . "\r\n";

	$headers .= 'X-Mailer: PHP/' . phpversion(). "\r\n";
	$headers .= 'Delivery-Date: ' . date("r") . "\r\n";
	// Additional headers

	if(mail($to, $subject, $body, $headers)) {
		$table = 'blockedusers';
		$args['hasBeenEmailed'] = 1;
		$where['hasBeenEmailed'] = 0;
		$database->update($table, $args, $where);
	}
}

?> 
