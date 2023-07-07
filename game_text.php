<?php
/**
 * Generate a button for a given case 
 *
 * @param  $i the case number (zero indexed)
 * @return void
 */
function generate_case_button(int $i)
{
    $offer_round = "";
    if ($_SESSION['banker']->offer 
        || ( $_SESSION['gamestate']->keep!==-1 
        && $i === $_SESSION['gamestate']->keep 
        && $_SESSION['gamestate']->no_left > 2 )
    ) {
        $offer_round = "disabled";
    }
    
    echo "<button style=\"
      background: url('./assets/mycase.png');
      border: none;
      width:65%;
      height:250%;
      grid-column:";
    echo ($i % 7 + 1) . ";\" name=\"case\"";
    echo "value=\"$i\"";
    echo $offer_round.">";
    echo $i+1;
    echo "</button>";
}
/**
 * Display game over info and leaderboard position if applicable
 *
 * @return void
 */
function generate_game_over()
{
    ?><div class="end-box"><?php
    // if game is completed show result
if ($_SESSION['gamestate']->score) {
    $res = update_leaderboard();
    if ($res) {
        $str_res = ordinal($res);
        echo "<h2>You got the new $str_res place global highscore!</h2>";
    }
    $l_res = update_local_leaderboard();
    if ($l_res) {
        $l_str_res = ordinal($l_res);
        echo "<h2>You got a new $l_str_res place local highscore!</h2>";
    }
    ?>
        <h2>You Won $<?php echo number_format($_SESSION['gamestate']->score, 2)?>!!</h2>
        <a href="deal.php"> Play Again? </a>|<a href="index.php"> Return Home? </a>
        <?php 
        $_SESSION['gamestate']->reset();
        $_SESSION['banker']->reject_offer();
}
?></div><?php
}
/**
 * create html grid layout for briefcases
 * and the game over screen if required
 *
 * @return void 
 */
function generate_case_grid()
{
    if ($_SESSION['gamestate']->keep===-1) {
        echo "<h2> Select your case to keep </h2>";
    }
    ?>
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
                generate_case_button($i);
            }
            ?>
        </div>
    <?php generate_game_over(); ?>
</form>
    <?php
}
/**
 * generate panel to display banker offer 
 * and counter offer if available 
 *
 * @return void 
 */
function generate_offer_panel()
{
    ?>
    <div class="offer-box">
 
          <?php
            if (!isset($_SESSION['gamestate'])) {
                header("Location: deal.php");
            }
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
            } 
            ?>
      </div>
    <?php
}
?>
