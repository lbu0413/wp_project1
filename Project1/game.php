<?php
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
            $_SESSION['offer'] = 0;
        }
        // if offer available load offer options
        if (isset($_SESSION['offer']) && $_SESSION['offer'] !== 0 && $_SESSION['score'] === 0) {
            ?>
      <h2>Banker offer: $
            <?php echo number_format($_SESSION['offer'], 2) ?>
      </h2>
      <form style="margin: auto;" action="deal.php" method="get">
        <button value="-1" name="case">Accept Offer</button>
      </form>
      <form style="margin: auto;" action="game.php" method="get">
        <button value="-1" name="offer">Decline Offer</button>
      </form>
            <?php
            if ($_SESSION['counter']) {
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
                if ($_SESSION['opened'][$i] !== 0) {
                    echo "<div style=\"width:100%;height:100%;grid-column:" . ($i % 7 + 1) . ";\">$"
                    . number_format($_SESSION['opened'][$i], 2) .
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
        if ($_SESSION['score'] !== 0) {
            $res = update_leaderboard();
            if ($res) {
                $str_res = ordinal($res);
                echo "<h2>You got a new $str_res place highscore!</h2>";
            }
            ?>
        <h2>You Won $<?php echo number_format($_SESSION['score'], 2)?>!!!</h2>
        <a href="deal.php"> Play Again? </a>           
        <a href="index.php"> Return Home? </a>
            <?php 
            $_SESSION['score'] = 0;
        }
        ?>
     </form>

    </div>
  </div>
</body>

</html>
