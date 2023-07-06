<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include __DIR__ . '/signin.php';
// var_dump($_COOKIE["username"]);
echo '<pre>';
print_r($_COOKIE);
echo '</pre>';


function validate_user($username, $password)
{
    $userData = explode("\n", file_get_contents("./user_data/users.txt"));
    foreach ($userData as $sub_userData) {
        $username_password = explode(",", $sub_userData);

        $valid_username = $username_password[0] === $username;
        $valid_password = $username_password[1] === $password;

        if ($valid_username && $valid_password) {
            return true;
        }

    }
    return false;
}

// check if the returning user has cookies in their local system.
if (isset($_COOKIE["username"]) && (isset($_COOKIE["password"]))) {
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];



    if (validate_user($username, $password)) {
        $_SESSION["auth"] = true;
        header("Location: deal.php");
    } else {
        echo "invalid username or password, please try again.";
    }
} else {
    // cookie expired, user needs to sign-in again
    header("Location: signin.php");
}


?>