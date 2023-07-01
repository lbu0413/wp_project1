<?php
if (!isset($_SESSION['cases'])) {
    session_register('cases');

    $_SESSION['cases'] = array(
    0.01, 1, 5, 10, 25, 50, 75,
    100, 200, 300, 400, 500, 750,
    1000, 5000, 10000, 25000,
    50000, 75000, 100000, 200000,
    300000, 400000, 500000, 750000,
    1000000
    );
    shuffle($_SESSION['cases']);
    $_SESSION['valid'] = array_fill(count: count($_SESSION['cases']), value: true);
} else {
    $case_no = $_GET['case'];
    $open = $_SESSION['cases'][$case_no];
    if ($_SESSION['valid'][$case_no]) {
        // on the game screen if the case number is 0 
        // show a closed case otherwise display the value
        $_POST['cases'][$case_no] = $open;
        // TODO: implement banker offer func
        $_POST['offer'] = get_offer();
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

// Reload the game board with the updated gamestate
$protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
  'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$currentURL = $protocol . $host;
header("Location: $currentURL/game.php");
?>
