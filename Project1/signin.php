<?php



session_start();
// signin.php


// retrieve the username & password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];



    // checking if user's data is already in the cookie
    // if so redirect them straight to deal.php without valid credentials check.
    $checker = filter_input_array(INPUT_POST, $_COOKIE);
    foreach ($checker as $check) {
        if ($checker["username"] == $username) {
            $_SESSION["username"] = $username;
            $_SESSION["auth"] = true;
            header("Location: deal.php");
            return;
        }
    }




    // check if valid credentials
    if (isset($_SESSION["users"][$username]) && $_SESSION["users"][$username]["password"] === $password) {
        $_SESSION["username"] = $username;
        $_SESSION["auth"] = true;
        echo "login successful";

        // $cookieExpiration = time() + (30 * 24 * 60 * 60); // cookie is stored for 30 days from now
        // setcookie("username", $username, $cookieExpiration); // username is set using cookie
        // setcookie("auth", true, $cookieExpiration); // user authentication is set using cookie

        // header("Location: deal.php");
    } else {
        echo "Invalid username or password.";
    }

}




?>