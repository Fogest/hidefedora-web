<?php
	include_once ("../../setup.php");
	if(isset($_POST['id']) && isset($_POST['status'])) {
		$table = 'blockedusers';
		if($_POST['status'] == 1)
			$args['approvalStatus'] = 1;
		else if($_POST['status'] == -1)
			$args['approvalStatus'] = -1;
		else
			echo 'Approval Status Error';
		$args['approvalDate'] = date("Y-m-d H:i:s");
		$args['approvingUser'] = $_SESSION['username'];
		$where['id'] = $_POST['id'];
		$result = $database->update($table, $args, $where);
		if(!$result)
			echo 'failed to save to db';
		else
			echo 'saved';
	}
?>