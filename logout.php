<?php
session_start();
$_SESSION['loggedIn'] = false;
setcookie("persist", "", time()-3600);
setcookie("user_id", "", time()-3600);
session_destroy();
include_once ("setup.php");

$page->privilege = 0;
$page->html .= 'Logged out!';
$page->display();
?>