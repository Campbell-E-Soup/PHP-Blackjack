<?php

namespace Game;

class AIPlayer extends Player {
    public int $type;
    public static $types = [1,2,2,3]; //mostly normal players

    public function __construct($name = 'Your hand:') {
        $this->name = $name;
        //randomly assign personality
        $this->type = self::$types[random_int(0,count(self::$types)-1)];
    }
    public function take_turn($players,$position,$deck) {
        $this->display_hand();
        echo "\033[1mIt is player $this->id's turn.\033[0m\n";
        sleep(3);
        $finished = false;
        $lost = false;
        while (!$finished) {
            if ($this->total >= 22) {
                $lost = true;
                $finished = true;
            }
            else if ($this->type == 1) { //dumb
                if ($this->total > 17) {
                    if (random_int(0,2) == 1) {
                        $this->hit($deck);
                        $this->display_hand();
                        echo "Player $this->id hits.\n";
                        sleep(3);
                    }
                    $finished = true;
                }
                else {
                    $this->hit($deck);
                    $this->display_hand();
                    echo "Player $this->id hits.\n";
                    sleep(3);
                }
            } else if ($this->type == 2) { //ok
                if ($this->total > 17) {
                    if (random_int(0,2) == 1 && $this->total != 21) {
                        $this->hit($deck);
                        $this->display_hand();
                        echo "Player $this->id hits.\n";
                        sleep(3);
                    }
                    $finished = true;
                }
                else {
                    $this->hit($deck);
                    $this->display_hand();
                    echo "Player $this->id hits.\n";
                    sleep(3);
                }
            } else { //better
                if ($this->total < 17) {
                    $this->hit($deck);
                    $this->display_hand();
                    echo "Player $this->id hits.\n";
                    sleep(3);
                }
                else {
                    if ($this->total == 17) {
                        $ace = false;
                        foreach ($this->hand as $card) 
                        {
                            if ($card->value == 11) {
                                $ace = true;
                                break;
                            }
                        }
                        if ($ace) {
                            $this->hit($deck);
                            $this->display_hand();
                            echo "The player $this->id hits on a soft 17.\n";
                            sleep(3);
                        }
                    }
                    $finished = true;
                }
            }
        }
        if ($this->total > 21) $lost = true;
        if (!$lost) {
            $this->display_hand();
            echo "Player $this->id stands.\n";
        }
        else {
            $this->display_hand();
            echo "\033[38;5;160mPlayer $this->id lost!\nThey went over 21!\033[0m\n";
        }
        sleep(3);
    }

    private function evaluate_ace($card): Card { //I have created the most powerful AI in the world
        if ($this->total + 11 > 21) return $card; //1 is best for the ace so just return an ace with 1 (the default)
        $card->value = 11;
        return $card;
    }

    public function hit($deck) {
        $card = $deck->draw($this->hand);
        if ($card->value == 1) {
            $card = $this->evaluate_ace($card);
        }
        array_push($this->hand,$card);
        //revaluate total and display
        $this->display_hand();
    }
}