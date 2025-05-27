<?php

namespace Game;

require_once 'Card.php';
require_once 'Deck.php';

use Game\Card;
use Game\Deck;

class Player {
    /**
     * Summary of hand
     * @var Card[]
     */
    public array $hand = [];
    public int $total = 0;
    /**
     * Summary of hit
     * @param Deck $deck
     * @return void
     */
    public function hit($deck) {
        $card = $deck->draw($this->hand);
        if ($card->value == 1) {
            $card = $this->evaluateAce($card);
        }

        array_push($this->hand,$card);
    }

    public function hold() {

    }
    /**
     * Summary of evaluateAce
     * @param Card $card
     * @return Card
     */
    public function evaluateAce($card): Card {
        if ($this->total + 11 > 21) return $card; //1 is best for the ace so just return an ace with 1 (the default)
        $response = null;
        while ($response == null) {
            echo "\nYou have drawn an \033[38;2;255;215;0mAce\033[0m\nShould it be 1 or 11?\n\033[38;2;255;215;0m\n";
            $response = trim(fgets(STDIN));
            if ($response !== "1" && $response !== "11") {
                $response = null;
                echo "\n\033[0m\033[38;5;160mInvalid response, please try again.\033[0m";
            }
        }
        $card->value = (int)$response;
        return $card;
    }
}