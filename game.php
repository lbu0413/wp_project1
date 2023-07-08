<?php
session_start();
require __DIR__."/common.php";

check_auth();
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
  <title>Deal or No Deal</title>
  <link rel="stylesheet" type="text/css" href="game_style.css">
</head>

<body style="flex-direction: column;">
<body style="flex-direction: column;">
  

  <div class="content-body">
    <?php
    if (isset($_SESSION['offer']) && $_SESSION['offer'] !== 0 && $_SESSION['score'] === 0) {
      ?>
      <h2>Banker offer: $
        <?php echo number_format($_SESSION['offer'], 2) ?>
      </h2>
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
          if ($_SESSION['opened'][$i] !== 0) {
            echo "<div style=\"width:100%;height:100%;grid-column:" . ($i % 7 + 1) . ";\">$"
              . number_format($_SESSION['opened'][$i], 2) .
              "</div>";
            continue;
          }
          $j = $i + 1;
         echo
            "<button style=\"
			background: url('mycase.png');
			
			border: none;
                width:65%;
                height:250%;
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
        if ($_SESSION['score'] !== 0) {
            $res = update_leaderboard();
            if ($res) {
                $str_res = ordinal($res);
                echo "<h2>You got a new $str_res place highscore!</h2>";
            }
            ?>
        <h2>You Won $<?php echo number_format($_SESSION['score'], 2)?>!!!</h2>
        <button>Play Again?</button>
            <?php 
            $_SESSION['score'] = 0;
        }
        ?>
     </form>

    </div>
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