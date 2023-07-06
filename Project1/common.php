<?php
function check_auth()
{
    $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
        'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $currentURL = $protocol . $host;

    // redirect to login page if no authentication from login
    if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
        header("Location: signin.html");
    } else {
        return $currentURL;
    }

}

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


?>