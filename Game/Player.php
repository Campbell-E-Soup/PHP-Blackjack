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
            echo "\033[1mActions:\033[0m\n";
            echo "\033[1mView a hand:\033[0m enter a number between \033[1m\033[38;2;255;215;0m1\033[0m and \033[38;2;255;215;0m\033[1m$max\033[0m (You are \033[1m\033[38;2;255;215;0m$position\033[0m). Dealer is \033[1m\033[38;5;160m$max\033[0m\n";
            echo "\033[1mHit:\033[0m \"\033[1m\033[38;2;255;215;0mhit\033[0m\" or \"\033[1m\033[38;2;255;215;0mh\033[0m\"\n";
            echo "\033[1mStand:\033[0m \"\033[1m\033[38;2;255;215;0mstand\033[0m\" or \"\033[1m\033[38;2;255;215;0ms\033[0m\"\n\n";
            $valid_answers = ["hit","stand","h","s"];
            for ($i = 0; $i <= $max; $i++) {
                array_push($valid_answers,"$i");
            }
            $response = Input::get_input(valid_answers: $valid_answers);

            echo "\033[2J\033[H";
            if (is_numeric($response)) {
                $players[((int)$response)-1]->display_hand();
            }
            else if ($response == "hit" || $response == "h") {
                $this->hit($deck);
            } else {
                $finished = true;
            }
            if ($this->total > 21) {
                //you lose!!
                echo "\033[38;5;160mYou lose!\nYou went over 21!\033[0m\n";
                $finished = true;
                sleep(3);
            }
        }
    }

    public function reveal_cards() {
        //turn all cards face up
        foreach ($this->hand as $card) {
            $card->face_up = true;
        }
    }
}