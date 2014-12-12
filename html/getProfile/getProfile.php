<?php
	include_once ("../../setup.php");
	if(isset($_POST['id'])) {
		$jsonurl = "https://www.googleapis.com/plus/v1/people/". $_POST['id'] ."?key=".GOOGLE_PLUS_API_KEY;
		$json = file_get_contents($jsonurl);
		echo $json;
	}
?>