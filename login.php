<?php
$table = "users";
$select['username'] = $_POST['username_login'];
$sort['user_id'] = "ASC";
$result = $database->select($table, $select, $sort);
$error = false;
if (count($result) != 1) {
    $page->html .= $alert->displayError("Error locating username");
    $error = true;
}

if (! $error) {
    if (! ($result[0]['password'] == hash('sha256', $_POST['password_login']))) {
        $page->html .= $alert->displayError(
                "Password did not match one on file!");
    } else {
        // Success
        if (isset($_POST['rememberMe'])) {
            $expire = time() + 60 * 60 * 24 * 30;
            setcookie('user_id', $result[0]['user_id'], $expire);
            setcookie('persist', md5($result[0]['username'] + $result[0]['password']), $expire);
        }
        $_SESSION['user_id'] = $result[0]['user_id'];
        $_SESSION['username'] = $result[0]['username'];
        $_SESSION['loggedIn'] = true;
        $_SESSION['user_level'] = $result[0]['user_level'];
        $page->html .= $alert->displaySuccess(
                "You have been logged in successfully!");
    }
}
?>