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
        header("Location: signin.html");
    } 
}
/**
 * Get the ordinal suffix for integer value 
 *
 * @param  number integer to get suffix for 
 * @return string suffix 
 */
function ordinal(int $number):string
{
    return match($number) {
        1 => "1st",
        2 => "2nd",
        3 => "3rd",
        default => $number."th"
    };
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
    $_SESSION['leaderboard'] = $out;
    return $out;
}


function validate_user($username, $password)
{
    $userData = explode("\n", file_get_contents("./user_data/users.txt"));
    foreach ($userData as $sub_userData) {
        $username_password = explode(",", $sub_userData);

        $valid_username = $username_password[0] === $username;
        $valid_password = $username_password[1] === $password;

        if ($valid_username && $valid_password) {
            return true;
        }

    }
    return false;
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
        array_push($leaderboard, array($_SESSION['username'], $_SESSION['score']));
        $new_hs++;
    } else {
        for ($i = 0; $i < 10 ; $i++) {
            // add to leaderboard if slot is empty or less than score
            if (! $leaderboard[$i] || (float)$leaderboard[$i][1] < $_SESSION['score']) {
                array_splice($leaderboard, $i, 0, [ [$_SESSION['username'], $_SESSION['score']] ]);
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
