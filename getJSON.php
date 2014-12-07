<?php
	include_once ("setup.php");

	$cacheDir = 'cache/';
	$url = $_SERVER["SCRIPT_NAME"];
	$break = Explode('/', $url);
	$file = $break[count($break) - 1];
	$cachefile = $cacheDir.'cached-'.substr_replace($file ,"",-4).'.html';
	//Every hour update cache.
	$cachetime = 3600;

	// Serve from the cache if it is younger than $cachetime
	if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
	    echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
	    include($cachefile);
	    exit;
	}
	ob_start(); // Start the output buffer

	$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = 1\n"
    . "ORDER BY `blockedusers`.`date` ASC";

    $result = $database->execute($sql);

    $ids = array();
    foreach($result as $value) {
    	$ids[] = $value['id'];
    }

    $jsonOutput = array("fedoras" => $ids);

	echo json_encode($jsonOutput);
	// Cache the contents to a file
	$cached = fopen($cachefile, 'w');
	fwrite($cached, ob_get_contents());
	fclose($cached);
	ob_end_flush(); // Send the output to the browser
?>