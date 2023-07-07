<?php

/**
 * Check that session is authenticated
 *
 * @return null || string 
 */
function check_auth()
{
    // redirect to login page if no authentication from login
    if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
        header("Location: cookie_check.php");
    } 
}
/**
 * update the game state based on player input 
 * 
 * @return void 
 */
// TODO: add session variable or get to get big reveal for last case

function update_game_state()
{
    if ($_SESSION['gamestate']->counter && isset($_POST['c_offer']) && is_numeric($_POST['c_offer'])) {
        $_SESSION['gamestate']->counter = false;

        if ((float)$_POST['c_offer'] < $_SESSION['banker']->get_offer() ) {
            // open all cases and submit final score 
            $_SESSION['gamestate']->score = (float)$_POST['c_offer'];
        }
    } elseif (isset($_GET['case'])) {
        // check if selected case is valid or if counter offer made
        $case_no = $_GET['case'];

        if ($case_no >= 0 && $case_no < GameState::NO_CASES) {
            // select case to keep on first round
            if ($_SESSION['gamestate']->keep === -1) {
                $_SESSION['gamestate']->keep_case($case_no);
            }
            // Select case to open 
            elseif (!$_SESSION['gamestate']->opened[$case_no]) {
                $_SESSION['gamestate']->open_case($case_no);
                // end game on final round
                if ($_SESSION['gamestate']->no_left === 1) {
                    // get final unopened case
                    $_SESSION['gamestate']->score = $_SESSION['gamestate']->get_remaining()[0];

                } elseif ($_SESSION['gamestate']->no_left <= 6  
                    || array_key_exists(
                        $_SESSION['gamestate']->no_left,
                        GameState::OFFER_ROUNDS
                    )
                ) {
                    // make offer on certain rounds
                    $_SESSION['banker']->update_adj_offer();
                } else {
                    // remove unaccepted offers
                    $_SESSION['banker']->reject_offer();
                }
            }
        } elseif ($case_no == -1) {
            // offer accepted end game
            $_SESSION['gamestate']->score = $_SESSION['banker']->offer;
        }
    }
    if ($_SESSION['gamestate']->score) {
        $_SESSION['gamestate']->open_all_cases();
    }
}
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

    echo "<button style=\"width:100%;height:100%;grid-column:";
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
    // if game is completed show result
    if ($_SESSION['gamestate']->score) {
        $res = update_leaderboard();
        if ($res) {
            $str_res = ordinal($res);
            echo "<h2>You got a new $str_res place highscore!</h2>";
        }
        ?>
        <h2>You Won $<?php echo number_format($_SESSION['gamestate']->score, 2)?>!!</h2>
        <a href="deal.php"> Play Again? </a>|<a href="index.php"> Return Home? </a>
        <?php 
        $_SESSION['gamestate']->reset();
        $_SESSION['banker']->reject_offer();
    }
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
        } 
        ?>
      </div>
    <?php
}
/**
 * Get the ordinal suffix for integer value 
 *
 * @param  number integer to get suffix for 
 * @return string suffix 
 */
function ordinal(int $number):string
{
    if ($number === 1) {
        return "1st";
    }
    elseif ($number === 2) {
        return "2nd";
    }
    elseif ($number === 3) {
        return "3rd";
    }
    else {
        return $number."th";
    }
}
/** 
 * Convert array of strings into associative array of arrays 
 * where each value is a username score pair
 *
 * @param  data array of strings 
 * @return array associative array of arrays of strings 
 */
function read_into_associative(array $data):array
{

    $out = array();
    $values = array();
    $keys = array();
    // reads alternating keyval array of strings into assoc array
    for ($i = 0; $i < count($data); $i+=2) {
        array_push($keys, $data[$i]);
    }
    for ($i = 1; $i < count($data); $i+=2) {
        array_push($values, $data[$i]);
    }
    for ($i = 0; $i < count($values); $i++) {
        $out[$i] = array($keys[$i], $values[$i]);
    }
    unset($keys, $values);
    return $out;
}
/**
 * Get array of user data from userdata file 
 *
 * @return array
 */
function get_existing_users():array
{
    $userInfo = file_get_contents("./user_data/users.txt");
    $userArray = [];

    $lines = explode("\n", $userInfo);
    foreach ($lines as $line) {
        $name_password = explode(",", $line);
        $userArray[$name_password[0]] = $name_password[1];
    }
    return $userArray;
}
/** 
 * Get scores from score file 
 *
 * @return array of arrays of [name,score]
 */
function read_leaderboard():array
{
    if (!file_exists("./user_data/scores.txt")) {
        touch("./user_data/scores.txt");
    }
    $fstr = explode("\n", file_get_contents("./user_data/scores.txt"));
    if (!$fstr[count($fstr)-1]) {
        array_pop($fstr);
    }
    return read_into_associative($fstr);
  
}
/**
 * Convert associative array into string and write to score file 
 *
 * @param  leaderboard associative array with array values 
 * @return void 
 */
function write_leaderboard(array $leaderboard):void
{
    if (!file_exists("./user_data/scores.txt")) {
        touch("./user_data/scores.txt");
    }  
    $leader_string = "";
    foreach ($leaderboard as $data) {
        $leader_string .= implode("\n", $data)."\n";
    }
    // prevent others from r/w while writing
    file_put_contents("./user_data/scores.txt", $leader_string, LOCK_EX);
}
/**
 * Update leaderboard with session score 
 *
 * @return int position of score in updated leaderboard (1 indexed)
 */
function update_leaderboard():int
{
    $leaderboard = read_leaderboard();
    $new_hs = 0;
    if (! count($leaderboard)) {
        array_push($leaderboard, array($_SESSION['username'], $_SESSION['gamestate']->score));
        $new_hs++;
    } else {
        for ($i = 0; $i < 10 ; $i++) {
            // add to leaderboard if slot is empty or less than score
            if (! $leaderboard[$i] || (float)$leaderboard[$i][1] < $_SESSION['gamestate']->score) {
                array_splice($leaderboard, $i, 0, [ [$_SESSION['username'], $_SESSION['gamestate']->score] ]);
                $new_hs = $i+1;
                break;
            }
        }
        // only keep top 10 in leaderboard
        while (count($leaderboard) > 10) {
            array_pop($leaderboard);
        }
    }
    
    write_leaderboard($leaderboard);
    return $new_hs;
}

?>
