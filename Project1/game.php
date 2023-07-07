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
      <div>
        
        <?php
        // if offer is declined remove offer
        if (isset($_GET['offer']) && $_GET['offer'] == -1) {
            $_SESSION['banker']->reject_offer();
        }
        // if offer available load offer options
        if (isset($_SESSION['banker']) && $_SESSION['banker']->offer && !$_SESSION['gamestate']->score) {
            ?>
      <h2>Banker offer: $
            <?php echo number_format($_SESSION['banker']->offer, 2) ?>
      </h2>
      <form style="margin: auto;" action="deal.php" method="get">
        <button value="-1" name="case">Accept Offer</button>
      </form>
      <form style="margin: auto;" action="game.php" method="get">
        <button value="-1" name="offer">Decline Offer</button>
      </form>
            <?php
            if ($_SESSION['gamestate']->counter) {
                ?>
      <h2>Counter Offer:</h2>
      <p>Only 1 use per game</p>
      <form action="deal.php" method="POST">
        <input type="number" name="c_offer" required match="\d+">
        <button type="submit">Submit</button>
      </form>
                <?php
            }
        } else {
            // load grid of cases
            ?>
      </div>
    <form style="margin:auto;" action="deal.php" method="get">
      <div class="cases-grid">
            <?php
            for ($i = 0; $i < 26; $i++) {
                if ($_SESSION['gamestate']->opened[$i]) {
                    echo "<div style=\"width:100%;height:100%;grid-column:" . ($i % 7 + 1) . ";\">$"
                    . number_format($_SESSION['gamestate']->opened[$i], 2) .
                    "</div>";
                    continue;
                }
                $j = $i + 1;
                echo
                "<button style=\"
                width:100%;
                height:100%;
                grid-column:" . ($i % 7 + 1) . ";\" 
                value=" . $i . " 
                name=\"case\"
                >"
                .$j.
                "</button>";
            }
            ?>
        </div>
            <?php 
        }
        // if game is completed show result
        if ($_SESSION['gamestate']->score) {
            $res = update_leaderboard();
            if ($res) {
                $str_res = ordinal($res);
                echo "<h2>You got a new $str_res place highscore!</h2>";
            }
            ?>
        <h2>You Won $<?php echo number_format($_SESSION['gamestate']->score, 2)?>!!!</h2>
        <a href="deal.php"> Play Again? </a>           
        <a href="index.php"> Return Home? </a>
            <?php 
            unset($_SESSION['banker']);
        }
        ?>
     </form>

    </div>
  </div>
</body>

</html>
