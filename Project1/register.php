<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__."/common.php";

// check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (array_key_exists($username, get_existing_users())) {
        echo "Username already exists";
        header("Location: ./register.html");
    } else {
        // write user data to users.txt
        $userdata = $username . "," . $password;
        file_put_contents("./user_data/users.txt", "\n" . $userdata, FILE_APPEND);

        // redirect to register_success.php with the username as a query parameter
        header("Location: register_success.php?username=" . urlencode($username));
    }
}
?>
