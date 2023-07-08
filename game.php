<?php

require __DIR__."/gamestate.php";
require __DIR__."/banker.php";
require __DIR__."/game_text.php";
session_start();
require __DIR__."/common.php";

check_auth();
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
  <title>Deal or No Deal</title>
  <!-- <link rel="stylesheet" type="text/css" href="./styles/main_style.css"> -->
  <link rel="stylesheet" type="text/css" href="./styles/game_style.css">
</head>

<body style="flex-direction: column;">
    <div style="position: fixed;top: 10;right: 10;">
      <a href="logout.php">Log out</a>
    </div>
    <div class="content-body">

      <?php
        generate_offer_panel();
        generate_case_grid();
        ?> 
    </div>

    <table class = "low">
      <td>$0.01</td>
      <td>$1</td>
      <td>$5</td>
      <td>$10</td>
      <td>$25</td>
      <td>$50</td>
      <td>$75</td>
      <td>$100</td>
      <td>$200</td>
      <td>$300</td>
      <td>$400</td>
      <td>$500</td>
      <td>$750</td>
    </table>

    <table class = "high">
      <td>$1,000</td>
      <td>$5,000</td>
      <td>$10,000</td>
      <td>$25,000</td>
      <td>$50,000</td>
      <td>$75,000</td>
      <td>$100,000</td>
      <td>$200,000</td>
      <td>$300,000</td>
      <td>$400,000</td>
      <td>$500,000</td>
      <td>$750,000</td>
      <td>$1,000,000</td>
    </table>

</body>

</html>
