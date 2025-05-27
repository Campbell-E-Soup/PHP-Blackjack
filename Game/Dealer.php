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
    public static function deal($deck,$players) : void {
        for ($i = 0; $i < 2; $i++) {
            foreach ($players as $player) {
                //echo "\033[2J\033[H";
                $player->hit($deck);
                //Input::get_input("Continue? ");
                sleep(seconds: 3);
            }
        }
    }

    public function display_hand() {
        echo "\033[2J\033[H";
        $this->count_values();
        echo $this->name . "\n";
        Card::display_cards($this->hand);
    }
}