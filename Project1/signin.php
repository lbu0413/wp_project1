<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();



// retrieve the username & password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];


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

    if (validate_user($username, $password)) {
        setcookie("username", $username, time() + (86400 * 30), "/"); // Cookie expires in 30 days
        setcookie("password", $password, time() + (86400 * 30), "/"); // Cookie expires in 30 days
        $_SESSION["auth"] = true;
        header("Location: deal.php");
    } else {
        echo "invalid username or password, please try again";
    }


}




?>