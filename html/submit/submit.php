<?php
include ("../../setup.php");

////////////////////////////////////////////////
//Executes submit function on POST (via ajax) //
////////////////////////////////////////////////
if(isset($_POST['submit'])) {
	submit($database);
}

/**
 * Checks when a user last submitted an ID. Used to
 * check if the user is submitting too fast
 * @param  Object $database The database object from setup.php
 * @return boolean           Returns true if user is safe, false if submitting too fast.
 */
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

/**
 * Fetches the profile information for an ID and then decodes the json response from Google API
 * @param  string $id The id string for the profile
 * @return Array      An array with the decoded json response.
 */
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

/**
 * Submits the ID into the database, or updates it. 
 * @param  Object $database The database object which is created in setup.php
 * @return null
 */
function submit($database) {
	$regex = "/((https|http):\/\/plus\.google\.com\/\d+)|(^\d+$)/"; 
	$profileurl = $_POST['profileUrl'];

	//Applies cooldown if user is submitting too fast.
	if(!submissionCooldownCheck($database))
		die("URL saved; Now in review process!");

	//Kill execution if field empty or not valid id.
	if(!isset($_POST['profileUrl']))
		die('Profile URL not filled.');
	else if(trim($profileurl) == '')
		die('Profile URL not filled.');
	else if(!preg_match($regex,$profileurl))
		die('URL must be from YouTube or Google+');

	//Check if there are numbers in ID, otherwise kill execution. 
	$regex = "/\d+/";
	$id = array();
	if(!preg_match_all($regex,$profileurl,$id))
		die('Error finding ID in url.');

	//Sanitize the URL/ID and fetch profile data.
	$cleaned = $database->clean_data($id[0][0]);
	$profileData = fetchProfileInfo($cleaned);
	//If profile no profile data kill.
	if (!$profileData)
		die('Profile does not exist.');
	
	//Lookup ID in database to see if it exists or not.
	$query = "SELECT id,count,approvalStatus FROM blockedusers WHERE id=" . $cleaned . ";";
	$result = $database->execute($query);

	//The ID exists in the datbase. UPDATE record
	if(isset($result[0]['id'])) {
		//Only update if the user isn't already approved.
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
			//User already approved, don't do anything
			echo 'User has already been blocked!';
		}
	}
	//The ID was not found in the database, INSERT into database
	else {
		$table = 'blockedusers';
		$args['id'] = $id[0][0];
		$args['displayName'] = $profileData['displayName'];
		$args['profilePictureUrl'] = substr($profileData['image']['url'], 0, -2) . '150';
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

?>