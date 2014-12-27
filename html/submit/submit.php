<?php
include ("../../setup.php");

if(isset($_POST['submit'])) {
	$regex = "/((https|http):\/\/plus\.google\.com\/\d+)|(^\d+$)/"; 
	$profileurl = $_POST['profileUrl'];

	if(!submissionCooldownCheck($database))
		die("URL saved; Now in review process!");

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
			$cleaned = $database->clean_data($id[0][0]);
			$profileData = fetchProfileInfo($cleaned);

			if (!$profileData)
				echo 'Profile does not exist.';
			else {
				
				$query = "SELECT id,count,approvalStatus FROM blockedusers WHERE id=" . $cleaned . ";";
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
						$args['approvalDate'] = "NULL";
						$args['date'] = date("Y-m-d H:i:s");

						$regex = "/(https|http):\/\/(www.)?youtube.com\/.+/";
						if(isset($_POST['youtubeUrl']) && preg_match($regex, $_POST['youtubeUrl']))
							$args['youtubeUrl'] = $_POST['youtubeUrl'];

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
					if(isset($_POST['youtubeUrl']))
						$args['youtubeUrl'] = $_POST['youtubeUrl'];

					$result = $database->insert($table,$args);
					if(!$result)
						echo 'Failed to save to database!';
					else
						echo 'URL saved; Now in review process!';
				}
			}
		}
	}
}

function submissionCooldownCheck($database) {
	$ip = 0;
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	if($_SERVER['REMOTE_ADDR'] != "::1" && $_SERVER['REMOTE_ADDR'] != NULL)
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
	$sql = "SELECT `date` FROM `blockedusers` WHERE `ip` = ". $ip ."\n"
    . "ORDER BY `blockedusers`.`date` DESC LIMIT 1";

    $date = $database->execute($sql);
    if(isset($date[0]['date']))
    	$date = $date[0]['date'];
    else {
    	return true;
    }
    //60 seconds.
    if ((time() - strtotime($date)) > 60)
		return true;
	else
		return false;
}

function fetchProfileInfo($id) {
	$jsonurl = "https://www.googleapis.com/plus/v1/people/". $id ."?key=".GOOGLE_PLUS_API_KEY;
	//use @ to surpress warning.
	$json = @file_get_contents($jsonurl);
	if(!$json)
		return false;

	//Convert JSON to an array
	$data = json_decode($json,true);

	return $data;
}

?>