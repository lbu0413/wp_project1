<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>signup-submit</title>
    <link rel="stylesheet" href="./styles/register_submit_style.css">
</head>

<body class="register_submit_body">
    <?php
    function username_exists($username)
    {

        $userData = explode("\n", file_get_contents('./user_data/users.txt'));
        foreach ($userData as $sub_userData) {
            $username_password = explode(",", $sub_userData);
            if ($username_password[0] == $username) {
                return true;
            }
        }
        return false;

    }
    ?>

    <?php
    $failed = false;
    if (empty($_POST["username"])) {
        echo "please enter a username you would like to use";
        return $failed;
    }
    if (username_exists($_POST["username"])) {
        echo "Username already exists, please choose another username";
        return $failed;
    }
    if (!$failed) {
        $userInfo = $_POST["username"] . "," . $_POST["password"];
        file_put_contents("users.txt", "\n" . $userInfo, FILE_APPEND);
    }

    ?>
    <div>
        <img src="./assets/logo.png" alt="" />
        <h1>Congrats,
            <span class="username">
                <?php echo $_POST["username"] ?>
            </span><br />
        </h1>
        <h2>Now, <a href="./signin.php">Sign in</a> to play the game</h2>
    </div>
</body>

</html>
