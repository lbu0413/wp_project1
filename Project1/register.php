<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];



    // function existing_user($username)
    // {
    //     $userInfo = explode("\n", file_get_contents("./user_data/users.txt"));
    //     foreach ($userInfo as $sub_user_info) {
    //         $name_password = explode(",", $sub_user_info);
    //         if ($name_password[0] == $username) {
    //             return true; // existing user
    //         }

    //     }
    //     return false; // new user
    // }

    function existing_user($username)
    {
        $userInfo = file_get_contents("./user_data/users.txt");
        $userArray = [];

        $lines = explode("\n", $userInfo);
        foreach ($lines as $line) {
            $name_password = explode(",", $line);
            $userArray[$name_password[0]] = true;
        }
        // check if the username exists in the array
        return array_key_exists($username, $userArray);
    }


    if (existing_user($username)) {
        echo "Username already exists";
    } else {
        // write user data to users.txt
        $userdata = $username . "," . $password;
        file_put_contents("./user_data/users.txt", "\n" . $userdata, FILE_APPEND);


        // redirect to register_success.php with the username as a query parameter
        header("Location: register_success.php?username=" . urlencode($username));
    }

}




?>