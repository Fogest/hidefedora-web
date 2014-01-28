<?php
session_start();
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

if (isset($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
    if (! isset($_SESSION['loggedIn']))
        $_SESSION['loggedIn'] = true;
    elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == false)
        $_SESSION['loggedIn'] = true;
    if (! isset($_SESSION['user_level'])) {
        $table = "users";
        $select['username'] = $_SESSION['username'];
        $sort['user_id'] = "ASC";
        $result = $database->select($table, $select, $sort);
        if (count($result) != 1) {
            $page->html .= $alert->displayError("Error locating username");
        }
        $_SESSION['user_level'] = $result[0]['user_level'];
    }
}

?>