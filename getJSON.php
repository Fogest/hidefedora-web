<?php
	include_once ("setup.php");

	$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = 1\n"
    . "ORDER BY `blockedusers`.`date` ASC";

    $result = $database->execute($sql);

    $ids = array();
    foreach($result as $value) {
    	$ids[] = $value['id'];
    }

    $jsonOutput = array("fedoras" => $ids);

	echo json_encode($jsonOutput);
?>