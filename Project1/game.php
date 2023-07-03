<?php session_start();?>
<!DOCTYPE HTML>

<html lang="en">
  <head>
    <title>Deal or No Deal</title>
    <link rel="stylesheet" type="text/css" href="./main_style.css">
    <link rel="stylesheet" type="text/css" href="game_style.css">
  </head>
  <body>
    <pre>
    <?php print_r($_SESSION);?>
    </pre>
    <div class="content-body">
      <form style="margin:auto;" action="deal.php" method="get">
        <div class="cases-grid">
          <?php
            for ($i = 0; $i < 26; $i++) {
                if ($_SESSION['opened'][$i] !== 0 ) {
                    echo "<div style=\"width:100%;height:100%;grid-column:".($i%7+1).";\">"
                    .($_SESSION['opened'][$i]).
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
      </form>
    </div>
  </body>
</html>
