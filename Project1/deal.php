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
session_start();
define('NO_CASES', 26);
require __DIR__ . "/common.php";

if (!isset($_SESSION['username'])) {
    // all your highscore are belong to me
    $_SESSION['username'] = 'Jack Morris';
}
// check for session authentication
check_auth();
// get the server url -- needed for testing locally
/**
 *  Opens all cases 
 *
 * @return null 
 */
function open_all_cases()
{
    for ($j = 0; $j < NO_CASES; $j++) {
        $_SESSION['opened'] = $_SESSION['cases'];
    }
}
/** 
 * get sum of squares 
 *
 * @return float 
 */
function sum_squares($carry, $item): float
{
    return $carry + ($item * $item);
}
/**
 * glorified not operator
 *
 * @return null
 */
function filter_opposite($value): bool
{
    return (bool) (!$value);
}
/** 
 * get all unopened cases
 *
 * @return array of unopened cases
 */
function get_remaining(): array
{
    return array_keys(
        array_filter(
            array_combine(
                $_SESSION['cases'],
                $_SESSION['opened']
            ),
            "filter_opposite"
        )
    );
}
/** 
 * Get banker un adjusted offer
 *
 * @return float sqrt of mean of squares
 */
function get_offer(array $remaining = null): float
{
    if ($remaining === null) {
        $remaining = get_remaining();
    }
    $offer = array_reduce($remaining, "sum_squares") / count($remaining);
    return sqrt($offer);
}
/**
 * Adjust banker offer by factor proportional to how many cases remain unopened
 *
 * @return float banker offer 
 */
function get_adj_offer(): float
{
    $remaining = get_remaining();
    if (count($remaining) === 0) {
        return 0;
    }
    // never offer more than the final remaining case
    // + rand()/getrandmax()
    return min(
        get_offer($remaining) * pow(1 - $_SESSION['no_left'] / NO_CASES, 2),
        max($remaining)
    );
}
//MAIN GAME SETUP AND LOGIC
if (!isset($_SESSION['cases'])) {
    // set up new game

    // session_register(
    //     'cases', // hidden values inside of cases
    //     'opened', // values of cases which are open and visible to player
    //     'offer', // banker offer for current turn
    //     'counter', // tracks counter offer availability
    //     'counter_offer', // the amount counter offered by player
    //     'score', //final score - set to 0 to indicate ongoing game
    //     'prev' // track action on previous turn
    // );

    $_SESSION['cases'] = [
        0.01, 1, 5, 10, 25,50,75, 100,
        200, 300, 400, 500, 750, 1000,
        5000, 10000, 25000, 50000, 75000,
        100000, 200000, 300000, 400000,
        500000, 750000, 1000000,
    ];
    shuffle($_SESSION['cases']);
    $_SESSION['opened'] = array_fill(0, NO_CASES, 0);
    $_SESSION['counter'] = true;
    $_SESSION['score'] = 0;
    $_SESSION['no_left'] = NO_CASES;
    $_SESSION['offer'] = 0;

} elseif ($_SESSION['counter'] && isset($_POST['c_offer']) && is_numeric($_POST['c_offer'])) {
    error_log(print_r($_GET, true));
    // counter === 1 if counter available and used 
    // counter === 2 if counter unavailable
    $_SESSION['counter'] = false;

    if ($_POST['c_offer'] < get_offer() ) {
        // open all cases and submit final score 
        $_SESSION['score'] = (float)$_POST['c_offer'];
    }
} elseif (isset($_GET['case'])) {
    // check if selected case is valid or if counter offer made
    $case_no = $_GET['case'];

    if ($case_no >= 0 && $case_no < NO_CASES) {
        $value = $_SESSION['cases'][$case_no];
        if (!$_SESSION['opened'][$case_no]) {
            // on the game screen if the case number is 0
            // show a closed case otherwise display the value
            $_SESSION['opened'][$case_no] = $value;
            $_SESSION['no_left']--;
            if ($_SESSION['no_left'] === 1) {
                $_SESSION['score'] = get_remaining()[0]; // get final unopened case
                // TODO: add session variable or get to get big reveal for last case
            } else {
                $_SESSION['offer'] = get_adj_offer();
            }
        } 
    } elseif ($case_no == -1) {
        // offer accepted end game
        $_SESSION['score'] = $_SESSION['offer'];
    }
}
if ($_SESSION['score'] !== 0) {
        open_all_cases();
        unset($_SESSION['cases']);
}

// Reload the game board with the updated gamestate
header("Location: game.php");
?>
