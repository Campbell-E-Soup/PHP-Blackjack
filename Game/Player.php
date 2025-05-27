<?php

namespace Game;

require_once 'Card.php';
require_once 'Deck.php';
require_once __DIR__ . '/../Helpers/Input.php';

use Game\Card;
use Game\Deck;
use Helpers\Input;

class Player {
    /**
     * Summary of hand
     * @var Card[]
     */
    public string $name;
    public array $hand = [];
    public int $total = 0;

    public function __construct($name = "Your hand:") {
        $this->name = $name;
    }

    /**
     * Summary of hit
     * @param Deck $deck
     * @return void
     */
    public function hit($deck) {
        $card = $deck->draw($this->hand);
        if ($card->value == 1) {
            $card = $this->evaluate_ace($card);
        }
        array_push($this->hand,$card);
        //revaluate total and display
        $this->display_hand();
    }

    public function stand() {

    }
    /**
     * Summary of evaluateAce
     * @param Card $card
     * @return Card
     */
    private function evaluate_ace($card): Card {
        if ($this->total + 11 > 21) return $card; //1 is best for the ace so just return an ace with 1 (the default)
        $response = null;
        while ($response == null) {
            $response = Input::get_input("You have drawn an \033[38;2;255;215;0mAce\033[0m\nShould it be 1 or 11?\n\n",["1","11"]);
        }
        $card->value = (int)$response;
        return $card;
    }

    public function count_values() {
        $this->total = 0;
        foreach ($this->hand as $card) {
            $this->total += $card->value;
        }
    }

    public function display_hand() {
        echo "\033[2J\033[H";
        $this->count_values();
        echo $this->name . "\n";
        Card::display_cards($this->hand);
        $color = "\033[38;2;255;215;0m";
        if ($this->total > 21) {
            $color = "\033[38;5;160m";
        }
        echo "Total:$color $this->total" . "\033[0m" . "\n\n";
    }

    /**
     * Summary of take_turn
     * @param Player[] $players
     * @param int $position
     * @return void
     */
    public function take_turn($players,$position,$deck) {
        $max = count($players);
        $finished = false;
        while (!$finished) {
            echo "What would you like to do?\n";
            echo "Actions:\n";
            echo "View a hand: enter a number between 1 and $max (You are $position). Dealer is \"D\"\n";
            echo "Hit: \"hit\"";
            echo "Hold: \"hold\"\n\n";
            $valid_answers = ["hit","hold"];
            for ($i = 0; $i <= $max; $i++) {
                array_push($valid_answers,"$i");
            }
            $response = Input::get_input(valid_answers: $valid_answers);

            echo "\033[2J\033[H";
            if (is_numeric($response)) {
                $players[((int)$response)-1]->display_hand();
            }
            else if ($response == "hit") {
                $this->hit($deck);
            } else {
                $finished = true;
            }
            
        }
    }
}