<?php
include ("../setup.php");

$sql = "SELECT * FROM `blockedusers` WHERE `displayName` IS NULL OR `profilePictureUrl` IS NULL ORDER BY `date` DESC LIMIT 100";
$result = $database->execute($sql);

foreach($result as $value) {
	$data = fetchProfileInfo($value['id']);

	//Handle case where profile does not exist (drop record)
	if(!$data) {
		$where['pkey'] = $value['pkey'];
		$database->delete('blockedusers',$where);
		echo 'Deleted user with id: ' . $value['id'] . " as their profile does not exist\n\r";
	} else {
		$table = 'blockedusers';
		$args['displayName'] = $data['displayName'];
		$args['profilePictureUrl'] = substr($data['image']['url'], 0, -2) . '150';

		$where['id'] = $value['id'];

		$result = $database->update($table, $args, $where);
		if(!$result)
			echo 'Failed to save to database ('.$value['id'].")!\n\r";
		else
			echo 'ID Updated with new information ('.$value['id'].").\n\r";
	}
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