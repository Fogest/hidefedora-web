<?php
	include_once ("../../setup.php");
	
	$page->page_header = $_SESSION['username'];
	$page->page_title = $_SESSION['username'];
	
	$page->html .= 'yo';
	
	$page->privilege = 1;
	$page->display();
?>