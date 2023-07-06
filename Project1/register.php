<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];



    $checker = filter_input_array(INPUT_POST, $_COOKIE);
    print_r(count($checker["username"]));






    if (!isset($_SESSION["users"][$username])) {
        // store the user data in the session array
        $_SESSION["users"][$username] = array(
            "username" => $username,
            "password" => $password
        );

        $_SESSION["current_username"] = $username;

        $cookieExpiration = time() + (30 * 24 * 60 * 60); // cookie is stored for 30 days from now
        setcookie("username", $username, $cookieExpiration); // username is set using cookie
        setcookie("password", $password, $cookieExpiration); // password is set using cookie
        setcookie("auth", true, $cookieExpiration); // user authentication is set using cookie

        // session_start();

        // // Clear all session variables
        // session_unset();

        // // Destroy the session
        // session_destroy();

        // echo "<pre>";
        // print_r($_SESSION);
        // echo "</pre>";



        header("Location: register_success.php");
    } else {
        echo "Username already taken.";
    }


}




?>