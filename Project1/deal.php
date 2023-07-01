<?php
/** 
 * get sum of squares 
 *
 * @return float 
 */
function sum_squares(float: $carry, float:$item):float 
{
    return $carry + ($item * $item)
}
/** 
 * Get banker un adjusted offer
 *
 * @return float sqrt of mean of squares
 */
function get_offer(float: $remaining = null): float 
{
    if ($remaining === null) {
        $remaining = array_filter(array_combine($_SESSION['cases'], $_SESSION['valid']));
    }
    $offer = array_reduce($remaining, "sum_squares")/count($remaining);
    $offer = sqrt($offer);
    return sqrt($offer);
}
/**
 * Adjust banker offer by random factor
 *
 * @return float banker offer 
 */
function get_adj_offer():float
{
    //TODO: Adjust based on how far into the game player is 
    // Farther in == higher offers
    $remaining = array_filter(array_combine($_SESSION['cases'], $_SESSION['valid']));
    // never offer more than the final remaining case
    return min(get_offer()*(0.5 + rand()/getrandmax()*0.75), max($remaining));
}
if (! isset($_SESSION['cases'])) {
    // set up new game
    session_register(
        'cases', // hidden values inside of cases
        'valid', // mask for cases which havent been opened
        'opened', // values of cases which are open and visible to player
        'offer', // banker offer for current turn
        'counter', // tracks counter offer availability
        'counter_offer', // the amount counter offered by player
        'score', //final score - set to 0 to indicate ongoing game
        'prev' // track action on previous turn
    );

    $_SESSION['cases'] = [
        0.01, 1, 5, 10, 25, 50, 75,
        100, 200, 300, 400, 500, 750,
        1000, 5000, 10000, 25000,
        50000, 75000, 100000, 200000,
        300000, 400000, 500000, 750000,
        1000000,
    ];
    shuffle($_SESSION['cases']);
    $_SESSION['opened'] = array_fill(count: count($_SESSION['cases']), value: 0);
    $_SESSION['valid'] = array_fill(count: count($_SESSION['cases']), value: true);
    $_SESSION['counter'] = 0;
    $_SESSION['counter_offer'] = -1;
    $_SESSION['score'] = 0;
} else {
    // check if selected case is valid or if counter offer made
    $case_no = $_GET['case'];
    $value = $_SESSION['cases'][$case_no];
    if ($_SESSION['valid'][$case_no]) {
        // on the game screen if the case number is 0
        // show a closed case otherwise display the value
        $_SESSION['opened'][$case_no] = $value;
        $_SESSION['offer'] = get_adj_offer();

    } elseif ($_SESSION['counter'] === 1) {
        // counter === 0 if counter available and unused
        // counter === 1 if counter available and used 
        // counter === 2 if counter unavailable
        $_SESSION['counter']++;

        if ($_SESSION['counter_offer'] < get_offer()*1.1) {
            // open all cases and submit final score 
            $_SESSION['score'] = $_SESSION['counter_offer'];
        }
    
    }
}
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

// Reload the game board with the updated gamestate
$protocol = ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
  'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$currentURL = $protocol.$host;
header("Location: $currentURL/game.php");
?>
