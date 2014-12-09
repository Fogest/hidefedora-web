<?php
include_once ("../../setup.php");

$page->page_title = 'Unban';
$page->page_header = 'Unban';

$page->privilege = 1;

$page->html .= "This is a private internal page to be utilized to unban a user. Simply enter their ID
				and hit \"Submit\" and the user will be switched from \"Approved\" to \"Rejected\" within the hour";

$page->display();
?>