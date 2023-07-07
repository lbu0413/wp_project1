<?php
// get request to open briefcase
// check if briefcase has already been opened
// return the value and the next banker offer
// if no cases remain after selection then set the
//    session score to the case value
// if banker offer accepted send all cases and set the
//    session score to the banker offer
// if counter offer made check if its under the max
//    acceptable offer from the banker, if so accept and
//    end seesion and set banker offer as session score
//    else reject and set counter available to false
// if only 1 case remaining after selection dont make offer
//    or ignore offer
require __DIR__."/gamestate.php";
require __DIR__."/banker.php";
session_start();
require __DIR__ . "/common.php";
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
            elseif ($_SESSION['gamestate']->check_openable($case_no)) {
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

if (!isset($_SESSION['username'])) {
    // all your highscore are belong to me
    $_SESSION['username'] = 'Jack Morris';
}
// check for session authentication
check_auth();
// check if new session
if (!isset($_SESSION['gamestate'])) {
    $_SESSION['gamestate'] = new GameState();
} 
// check if game over and is being reset
if (!isset($_SESSION['banker']) && isset($_SESSION['gamestate'])) {
    $_SESSION['banker'] = new Banker($_SESSION['gamestate']);
} 
// update gamestate with player action
else {
     update_game_state();
}
// Reload the game board with the updated gamestate
header("Location: game.php");
?>
