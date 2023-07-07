<?php

class GameState
{
    private $cases;
    private $opened;
    public $counter;
    public $score;
    private $no_left;

    const NO_CASES = 26;
    const OFFER_ROUNDS = [20=>0,15=>0,11=>0,8=>0];

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
        $this->opened = array_fill(0, self::NO_CASES, 0);
        $this->counter = true;
        $this->score = 0;
        $this->no_left = self::NO_CASES;
    }
    public function reset():void
    {
        shuffle($this->cases);
        $this->opened = array_fill(0, self::NO_CASES, 0);
        $this->counter = true;
        $this->score = 0;
        $this->no_left = self::NO_CASES;

    }
    public function __get($name)
    {
        return $this->$name;
    }
    /**
     * glorified not operator
     *
     * @return null
     */
    static function filter_opposite($value): bool
    {
        return (bool) (!$value);
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
                "self::filter_opposite"
            )
        );
    }
    public function open_all_cases()
    {
        $this->opened = $this->cases;
        $this->no_left = 0;
    }
    public function open_case(int $case)
    {
        $this->opened[$case] = $this->cases[$case];
        $this->no_left--;
    }
}



?>
