<?php
session_start();
$_SESSION['loggedIn'] = false;
session_destroy();
include_once ("setup.php");

$page->privilege = 0;
$page->html .= 'Logged out!';
$page->display();
?>