<?php
	include_once ("../../setup.php");
	$page->privilege = 1;
	if(isset($_POST['id']) && isset($_POST['status'])) {
		$table = 'blockedusers';
		if($_POST['status'] == 1) {
			deleteCache();
			$args['approvalStatus'] = 1;
		}
		else if($_POST['status'] == -1)
			$args['approvalStatus'] = -1;
		else
			$args['approvalStatus'] = 0;
		$args['approvalDate'] = date("Y-m-d H:i:s");
		$args['approvingUser'] = $_SESSION['username'];
		$where['id'] = $_POST['id'];
		$result = $database->update($table, $args, $where);
		if(!$result)
			echo 'Failed to update database';
		else
			echo 'Updated database';
	}
	if(isset($_POST['unban'])) {
		$table = 'appeals';
		$args = array();
		$args['approvalStatus'] = $_POST['unban'];

		$where['id'] = $_POST['id'];
		$result = $database->update($table, $args, $where);
		if(!$result)
			echo 'Failed to update database';
		else
			echo 'Updated database';
	}

function deleteCache() {
	$file = "../../cache/cached-getJSON.html";
	if(file_exists($file))
		unlink($file);
}
?>