<?php
/**
 * glorified not operator
 *
 * @return null
 */
function filter_opposite($value): bool
{
    return (bool) (!$value);
}


const NO_CASES = 26;

class GameState
{
    private $cases;
    private $opened;
    public $counter;
    private $score;
    private $no_left;

    const OFFER_ROUNDS = array_flip([20,15,11,8]);

    public function __construct()
    {
        $this->cases =  [
        0.01, 1, 5, 10, 25,50,75, 100,
        200, 300, 400, 500, 750, 1000,
        5000, 10000, 25000, 50000, 75000,
        100000, 200000, 300000, 400000,
        500000, 750000, 1000000,
        ];
        shuffle($this->cases);
        $this->opened = array_fill(0, NO_CASES, 0);
        $this->counter = true;
        $this->score = 0;
        $this->no_left = NO_CASES;
    }
    public function reset():void
    {
        shuffle($this->cases);
        $this->opened = array_fill(0, NO_CASES, 0);
        $this->counter = true;
        $this->score = 0;
        $this->no_left = NO_CASES;

    }
    public function __get($name)
    {
        return $this->$name;
    }
    /** 
     * get all unopened cases
     *
     * @return array of unopened cases
     */
    public function get_remaining(): array
    {
        return array_keys(
            array_filter(
                array_combine(
                    $this->cases,
                    $this->opened
                ),
                "filter_opposite"
            )
        );
    }
    private function open_all_cases()
    {
        $this->opened = $this->cases;
        $this->no_left = 0;
    }
    private function open_case(int $case)
    {
        $this->opened[$case] = $this->cases[$case];
    }
    public function update_game_state()
    {
        if ($this->counter && isset($_POST['c_offer']) && is_numeric($_POST['c_offer'])) {
            $this->counter = false;

            if ((float)$_POST['c_offer'] < $_SESSION['banker']->get_offer() ) {
                // open all cases and submit final score 
                $this->score = (float)$_POST['c_offer'];
            }
        } elseif (isset($_GET['case'])) {
            // check if selected case is valid or if counter offer made
            $case_no = $_GET['case'];

            if ($case_no >= 0 && $case_no < NO_CASES) {
                $value = $this->cases[$case_no];
                if (!$this->opened[$case_no]) {
                    // on the game screen if the case number is 0
                    // show a closed case otherwise display the value
                    $this->open_case($case_no);
                    $this->no_left--;
                    if ($this->no_left === 1) {
                        // end game on final round
                        $this->score = $this->get_remaining()[0]; // get final unopened case
                        // TODO: add session variable or get to get big reveal for last case
                    } elseif ($this->no_left <= 6 || array_key_exists($this->no_left, self::OFFER_ROUNDS)) {
                        // make offer on certain rounds
                        $_SESSION['banker']->get_adj_offer();
                    } else {
                        // remove unaccepted offers
                        $_SESSION['banker']->reject_offer();
                    }
                } 
            } elseif ($case_no == -1) {
                // offer accepted end game
                $this->score = $_SESSION['banker']->offer;
            }
        }
        if ($_SESSION['score'] !== 0) {
            $this->open_all_cases();
            return true;
                // unset($_SESSION['cases']);
        }
        return false;
    }
}


class Banker
{
    private $offer;
    private $game_state;

    public function __construct(GameState $game)
    {

        $this->game_state = $game;
        $this->offer= 0;

    }

    public function __get($name)
    {
        return $this->$name;
    }
    /** 
     * get sum of squares 
     *
     * @return float 
     */
    static function sum_squares($carry, $item): float
    {
        return $carry + ($item * $item);
    }

    /** 
     * Get banker un adjusted offer
     *
     * @return float sqrt of mean of squares
     */
    private function get_offer(array $remaining = null): float
    {
        if ($remaining === null) {
            $remaining = $this->game_state->get_remaining();
        }
        $offer = array_reduce($remaining, "self::sum_squares") / count($remaining);
        return sqrt($offer);
    }
    /**
     * Adjust banker offer by factor proportional to how many cases remain unopened
     *
     * @return float banker offer 
     */
    public function update_adj_offer():void
    {
        $remaining = $this->game_state->get_remaining();
        if (count($remaining) === 0) {
            return 0;
        }
        // never offer more than the final remaining case
        $this->offer = min(
            $this->get_offer($remaining) * pow(1 - $this->no_left / NO_CASES, 2),
            max($remaining)
        );
    }
    public function reject_offer():void
    {
        $this->offer = 0;
    }
}


?>
