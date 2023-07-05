<?php session_start();
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
        if (isset($_SESSION['offer'])&&$_SESSION['offer']!== 0&&$_SESSION['score']===0) {
            ?>
      <h2>Banker offer: $<?php echo number_format($_SESSION['offer'], 2)?></h2>
      <form style="margin: auto;" action="deal.php" method="get">
        <button value="-1" name="case">Accept Offer</button>
      </form>
            <?php
        }
        ?>
      <form style="margin:auto;" action="deal.php" method="get">
        <div class="cases-grid">
          <?php
            for ($i = 0; $i < 26; $i++) {
                if ($_SESSION['opened'][$i] !== 0 ) {
                    echo "<div style=\"width:100%;height:100%;grid-column:".($i%7+1).";\">$"
                    .number_format($_SESSION['opened'][$i], 2).
                    "</div>";
                    continue;
                }
                $j = $i+1;
                echo 
                "<button style=\"
                width:100%;
                height:100%;
                grid-column:".($i%7+1).";\" 
                value=".$i." 
                name=\"case\"
                >"
                .$j.
                "</button>";
            }
            ?>
        </div>
        <?php 
        if ($_SESSION['score'] !== 0) {
            ?>
        <h2>You Won $<?php echo number_format($_SESSION['score'], 2)?>!!!</h2>
        <button>Play Again?</button>
            <?php 
        }
        ?>
     </form>
      <div>
          <?php print_r($_SESSION);?>
      </div>
    </div>
  </body>
</html>
