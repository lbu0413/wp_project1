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
