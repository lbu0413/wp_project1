<?php
session_start();

require __DIR__ . "/common.php";

// check if the returning user has cookies in their local system.
if (isset($_COOKIE["username"]) && (isset($_COOKIE["password"]))) {
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $user_data = get_existing_users();

    if (array_key_exists($username, $user_data) && $user_data[$username] === $password) {
        $_SESSION["username"] = $username;
        $_SESSION["auth"] = true;
        header("Location: deal.php"); // valid credentials, user can go straight to play game.
    } else {
        header("Location: signin.html"); // invalid credentials, redirect users to sign-in page.
    }
} else {
    // cookie expired, user needs to sign-in again
    header("Location: signin.html");
}


?>
