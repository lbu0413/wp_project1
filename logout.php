<?php

session_start();
if (isset($_COOKIE['username'])) {
    unset($_COOKIE['username']);
}
if (isset($_COOKIE['password'])) {
    unset($_COOKIE['password']);
}
session_unset();
header("Location: signin.html");
?>
