<?php
/**
 * Check that session is authenticated
 *
 * @return null || string 
 */
function check_auth()
{
    $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
        'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $currentURL = $protocol . $host;

    // redirect to login page if no authentication from login
    if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
        header("Location: signin.html");
    } else {
        return $currentURL;
    }
}

/** 
 * Get scores from score file 
 *
 * @return array of arrays of [name,score]
 */
function read_leaderboard():array
{
    $scores = [];
    $name = '';
    $fptr = fopen("./user_data/scores.txt", 'r')or die("Unable to open file!");
    for ($i = 0; ! feof($fptr); $i++){
        if ($i%2 === 0) {
            $name = fgets($fptr);
        }else {
            $scores += [$name, (int)fgets($fptr)];
        }
    }
  
    fclose($fptr);
    return $scores;
  
}
function write_leaderboard(array $leaderboard)
{
    $fptr = fopen("./user_data/scores.txt", 'w')or die("Unable to open file!");
    for ($i = 0;! feof($fptr);$i++) {
        fwrite($fptr, $leaderboard[$i][0].'\n'.$leaderboard[$i][1].'\n');
    }
    fclose($fptr);
}
function update_leaderboard($new_score)
{
    $leaderboard = read_leaderboard();
    if (! count($leaderboard)) {
        $leaderboard += [$_SESSION['username'], $new_score];
        return $leaderboard;
    }
    for ($i = count($leaderboard)-1; $i >= 0 ; $i--) {
        // insert value into leaderboard if its deserving
        if (!isset($leaderboard[$i]) || $leaderboard[$i][1] < $new_score) {
            array_splice($leaderboard, $i, 0, [$_SESSION['username'], $new_score]);
            break;
        }
    }
    // only keep top 10 in leaderboard
    while (count($leaderboard) > 10) {
        array_pop($leaderboard);
    }
  
    write_leaderboard($leaderboard);
}

?>
