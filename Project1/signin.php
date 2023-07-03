<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . "/common.php";



// retrieve the username & password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user_data = get_existing_users();
    if (array_key_exists($username, $user_data) && $user_data[$username] === $password) {
        setcookie("username", $username, time() + (86400 * 30), "/"); // Cookie expires in 30 days
        setcookie("password", $password, time() + (86400 * 30), "/"); // Cookie expires in 30 days

        $_SESSION["username"] = $username;
        $_SESSION["auth"] = true;

        header("Location: deal.php");
    } else {
        // invalid username/pass
        header("Location: signin.html");
    }


}




?>
