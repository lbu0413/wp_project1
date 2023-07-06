<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require __DIR__ . "/common.php";




// var_dump($_COOKIE["username"]);
// echo '<pre>';
// print_r($_COOKIE);
// echo '</pre>';




// check if the returning user has cookies in their local system.
if (isset($_COOKIE["username"]) && (isset($_COOKIE["password"]))) {
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];



    if (validate_user($username, $password)) {
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