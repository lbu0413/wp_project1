<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>signup-submit</title>
    <link rel="stylesheet" href="./styles/register_success_style.css">
</head>

<body class="register_submit_body">
    <div>
        <img src="./assets/logo.png" alt="" />
        <h1>Congrats!
            <span class="username">
                <?php
                if (isset($_GET["username"])) {
                    $username = $_GET["username"];
                    echo "Welcome, " . htmlspecialchars($username) . "!";
                }
                ?>
            </span><br />
        </h1>
        <h2>Now, <a href="./signin.html">Sign in</a> to play the game</h2>
    </div>
</body>

</html>