<?php
include_once ("../../setup.php");

$page->page_title = 'Appeal Ban';
$page->page_header = 'Appeal Ban';

if(isset($_POST['id'])) {
	$id = $database->clean_data($_POST['id']);
	$sql = "SELECT id FROM `appeals` WHERE `id` = '". $id ."';";

	$result = $database->execute($sql);;

	$sql = "SELECT * FROM `blockedusers` WHERE `id` = '".$id."' AND `approvalStatus` = 1";

	$result2 = $database->execute($sql);

	if(isset($result[0]['id']))
		$page->html .= $alert->displayError("There is already an appeal on record");
	else if(!isset($result2[0]['id']))
		$page->html .= $alert->displayError("This id is not banned!");
	else {
		$table = 'appeals';
		$args['id'] = $id;
		$args['date'] = date("Y-m-d H:i:s");

		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		if($_SERVER['REMOTE_ADDR'] != "::1" && $_SERVER['REMOTE_ADDR'] != NULL)
			$args['ip'] = ip2long($_SERVER['REMOTE_ADDR']);

		$result = $database->insert($table,$args);
		if(!$result)
			$page->html .= $alert->displayError("Failed to submit appeal!");
		else
			$page->html .= $alert->displaySuccess("Appeal has been made!");
		
	} 
}
	$page->html .= '
	<form method="POST">
	<!-- Text input-->
	<div class="control-group">
	  <label for="profileId">Profile ID</label>
	    <input id="profileId" name="id" type="text" placeholder="12345678987654321" class="input-xlarge" required="">
	</div>

	<!-- Button -->
	<div class="control-group">
	    <button id="submit-appeal" name="submit-appeal" class="btn btn-primary">Submit</button>
	</div></form>';


$page->display();
?>