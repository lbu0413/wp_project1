<?php
// require __DIR__ . "/gamestate.php";

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
    public function get_offer(array $remaining = null): float
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
            return;
        }
        // never offer more than the final remaining case
        $this->offer = min(
            $this->get_offer($remaining) * pow(1 - $this->game_state->no_left / GameState::NO_CASES, 2),
            max($remaining)
        );
    }
    /**
     * Set offer to 0 
     *
     * @return void 
     */
    public function reject_offer():void
    {
        $this->offer = 0;
    }
}


?>
