<?php
session_start();
date_default_timezone_set('America/Toronto');
require_once ("phplib/config.php");

include_once ("phplib/Alert.php");
$alert = new Alert();

include_once ("phplib/Database.php");

include_once ("phplib/Email.php");

include_once ("phplib/mail/class.phpmailer.php");

include_once ("phplib/page.php");

$database = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$page = new Page();

if (isset($_POST['username_login'])) {
    include_once ("login.php");
}

if (isset($_POST['username_register'])) {
    include_once ("register.php");
}

if (isset($_COOKIE['persist'])) {
    include_once("phplib/loginPersist.php");
}

?>
