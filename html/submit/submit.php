<?php
include_once ("../../setup.php");

if(isset($_POST['submit'])) {
	$regex = "/((https|http):\/\/plus\.google\.com\/\d+)|(^\d+$)/"; 
	$profileurl = $_POST['profileUrl'];

	if(!isset($_POST['profileUrl']))
		echo 'Profile URL not filled.';
	else if(trim($profileurl) == '')
		echo 'Profile URL not filled.';
	else if(!preg_match($regex,$profileurl))
		echo 'URL must be from YouTube or Google+';
	else {
		$regex = "/\d+/";
		$id = array();
		if(!preg_match_all($regex,$profileurl,$id))
			echo 'Error finding ID in url.';
		else {
			$query = "SELECT id,count,approvalStatus FROM blockedusers WHERE id=" . $database->clean_data($id[0][0]) . ";";
			$result = $database->execute($query);

			if(isset($result[0]['id'])) {
				if($result[0]['approvalStatus'] != 1) {
					if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
						$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
					}
					if($_SERVER['REMOTE_ADDR'] != "::1" && $_SERVER['REMOTE_ADDR'] != NULL)
						$args['ip'] = ip2long($_SERVER['REMOTE_ADDR']);

					$table = 'blockedusers';
					$args['count'] = $result[0]['count'] + 1;
					$args['approvalStatus'] = 0;
					$args['approvingUser'] = "NULL";
					$args['hasBeenEmailed'] = 0;
					$args['approvalDate'] = "NULL";
					$args['date'] = date("Y-m-d H:i:s");
					if(isset($_POST['comment']))
						if($_POST['comment'] != NULL || trim($_POST['comment']) != '')
							$args['comment'] = $_POST['comment'];

					$where['id'] = $id[0][0];

					$result = $database->update($table, $args, $where);
					if(!$result)
						echo 'Failed to save to database!';
					else
						echo 'URL saved; Now in review process!';
				} else {
					echo 'User has already been blocked!';
				}
			} else {

				$table = 'blockedusers';
				$args['id'] = $id[0][0];
				$args['date'] = date("Y-m-d H:i:s");

				if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
					$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				}
				if($_SERVER['REMOTE_ADDR'] != "::1" && $_SERVER['REMOTE_ADDR'] != NULL)
					$args['ip'] = ip2long($_SERVER['REMOTE_ADDR']);

				if(isset($_POST['comment']))
					if($_POST['comment'] != NULL || trim($_POST['comment']) != '')
						$args['comment'] = $_POST['comment'];

				$result = $database->insert($table,$args);
				if(!$result)
					echo 'Failed to save to database!';
				else
					echo 'URL saved; Now in review process!';
			}
		}
	}
}

?>