<?php

require __DIR__."/gamestate.php";
require __DIR__."/banker.php";

session_start();
require __DIR__."/common.php";

check_auth();
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
  <title>Deal or No Deal</title>
  <link rel="stylesheet" type="text/css" href="./styles/main_style.css">
  <link rel="stylesheet" type="text/css" href="./styles/game_style.css">
</head>

<body style="flex-direction: column;">

    <div class="content-body">

      <?php
        generate_offer_panel();
        generate_case_grid();
        ?> 

    </div>
</body>

</html>
