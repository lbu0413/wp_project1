<?php
function check_auth()
{
    $protocol = ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
    'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $currentURL = $protocol.$host;

    // redirect to login page if no authentication from login
    if (! isset($_SESSION['auth']) || !$_SESSION['auth'] ) {
        header("Location: $currentURL/login.php");
    } else {
        return $currentURL;
    }

}




?>