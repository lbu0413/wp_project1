<?php

session_start();


// check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];


    function pass_username($username)
    {
        return $username;
    }


    if (!isset($_SESSION["users"][$username])) {
        // store the user data in the session array
        $_SESSION["users"][$username] = array(
            "username" => $username,
            "password" => $password
        );

        $_SESSION["current_username"] = $username;


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