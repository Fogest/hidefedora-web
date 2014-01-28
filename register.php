<?php
$table = "users";

$username = $_POST['username_register'];
$password = $_POST['password_register'];
$passwordConfirm = $_POST['passwordConfirm_register'];
$email = $_POST['email_register'];

$formFields = array(
        $username,
        $password,
        $passwordConfirm,
        $email
);
$errors = "";

if (! isset($username))
    $errors .= "Username is not set<br/>";
if (! isset($password))
    $errors .= "Password is not set<br/>";
if (! isset($passwordConfirm))
    $errors .= "Password confirmation is not set<br/>";
if (! isset($email))
    $errors .= "Email is not set<br/>";
if ($password != $passwordConfirm)
    $errors .= "Passwords do not match<br/>";
if (! empty($errors))
    die($errors);

$values['username'] = $username;
$values['password'] = hash("sha256", $password);
$values['email'] = $email;
$values['account_creation_date'] = date("Y-m-d H:m:s");
$values['account_creation_ip'] = ip2long(getenv('REMOTE_ADDR'));

$database->insert($table, $values);
$page->html = $alert->displaySuccess("Account Created!");
?>