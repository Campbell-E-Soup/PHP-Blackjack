<?php

namespace Game;

use Game\Player;
use Game\Deck;
use Game\Card;

class Dealer extends Player {
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
            $card = $this->evaluateAce($card);
        }
        array_push($this->hand,$card);
    }

    private function evaluateAce($card): Card { //I have created the most powerful AI in the world
        if ($this->total + 11 > 21) return $card; //1 is best for the ace so just return an ace with 1 (the default)
        $card->value = 11;
        return $card;
    }
}