<?php

namespace Game;

require_once __DIR__ . '/../Helpers/Input.php';

use Game\Player;
use Game\Deck;
use Game\Card;
use Helpers\Input;

class Dealer extends Player {

    public function __construct() {
        $this->name = "Dealer's hand:";
    }

    /**
     * Summary of hit
     * @param Deck $deck
     * @return void
     */
    public function hit($deck){
        $face_up = true;
        if (count($this->hand) == 1) { //second one is facedown
            $face_up = false;
        }
        $card = $deck->draw($this->hand,$face_up);
        if ($card->value == 1) {
            $card = $this->evaluate_ace($card);
        }
        array_push($this->hand,$card);
        //revaluate total and display
        $this->display_hand();
    }

    private function evaluate_ace($card): Card { //I have created the most powerful AI in the world
        if ($this->total + 11 > 21) return $card; //1 is best for the ace so just return an ace with 1 (the default)
        $card->value = 11;
        return $card;
    }

    /**
     * Summary of deal
     * @param Deck $deck
     * @param Player[] $players
     * @return void
     */
    public static function deal($deck,$players) : bool {
        for ($i = 0; $i < 2; $i++) {
            foreach ($players as $player) {
                //echo "\033[2J\033[H";
                $player->hit($deck);
                //Input::get_input("Continue? ");
                sleep(seconds: 3);
            }
        }
        if ($players[count($players)-1]->total == 21) {
            return true;
        }
        return false;
    }

    public function display_hand() {
        echo "\033[2J\033[H";
        $this->count_values();
        echo $this->name . "\n";
        Card::display_cards($this->hand);
    }

    /**
     * Summary of take_turn
     * @param Player[] $players
     * @param int $position
     * @return void
     */
    public function take_turn($players,$position,$deck) {
        while ($this->total < 17) {
            $this->hit($deck);
            $this->display_hand();
            echo "The dealer hits.\n";
            $color = "\033[38;2;255;215;0m";
            if ($this->total > 21) {
                $color = "\033[38;5;160m";
            }
            echo "Total:$color $this->total" . "\033[0m" . "\n\n";
            sleep(3);
        }
        //check if soft 17
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
                echo "The dealer hits on a soft 17.\n";
                $color = "\033[38;2;255;215;0m";
                if ($this->total > 21) {
                    $color = "\033[38;5;160m";
                }
                echo "Total:$color $this->total" . "\033[0m" . "\n\n";
                sleep(3);
            }
        }
            $this->display_hand();
            echo "The dealer stands.\n";
            $color = "\033[38;2;255;215;0m";
            if ($this->total > 21) {
                $color = "\033[38;5;160m";
            }
            echo "Total:$color $this->total" . "\033[0m" . "\n\n";
            sleep(2);
        //resolve game
        $max = count($players)-1;
        for ($i = 0; $i < $max; $i++) {
            $x = $i + 1;
            $player = $players[$i];
            $value = $player->total;
            if (($value <= 21) && ($this->total > 21 || $value > $this->total)) {
                if ($player->name == "Your hand:") {
                    //this is the player display it as such
                    echo "\033[38;2;255;215;0m\033[1mYou win with a total of $value! \n\033[0m";
                }
                else {
                    echo "\033[38;2;255;215;0m\033[1mPlayer $x wins with a total of $value! \n\033[0m";
                }
            }
            else {
                if ($player->name == "Your hand:") {
                    //this is the player display it as such
                    echo "\033[38;5;160m\033[1mYou lose with a total of $value!\n";
                    if ($value == $this->total) {
                        echo "The house always wins!\n";
                    }
                    echo "\033[0m";
                }
                else {
                    echo "\033[38;5;160m\033[1mPlayer $x loses with a total of $value!\n";
                }
            }
        }
    }

    public function reveal_cards() {
        //turn all cards face up
        foreach ($this->hand as $card) {
            $card->face_up = true;
        }
        $this->display_hand();
        echo "The dealer reveals their hand.\n";
        $color = "\033[38;2;255;215;0m";
        if ($this->total > 21) {
            $color = "\033[38;5;160m";
        }
        echo "Total:$color $this->total" . "\033[0m" . "\n\n";
        sleep(3);
    }
}