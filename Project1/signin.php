<?php

session_start();
// signin.php


// retrieve the username & password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];


    // check if valid credentials
    if (isset($_SESSION["users"][$username]) && $_SESSION["users"][$username]["password"] === $password) {
        $_SESSION["username"] = $username;
        // echo "login successful";
        $_SESSION["auth"] = true;
        header("Location: deal.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}



?>