<?php

namespace Game;

require_once __DIR__ . '/../Helpers/JSON_Parse.php';

use Helpers\JSON_Parse;

class Deck {
    /** @var Card[] */
    public array $cards = [];

    /** @var Card[] */
    public array $discard = [];
    public function __construct($size) {
        $json = new JSON_Parse("card-data/card-info.json");
        $json_data = $json->json_data;
        for ($i = 0; $i < $size; $i++) {
            foreach ($json_data["fresh-deck"] as $arr) {
                $this->cards[] = new Card($arr);
            }
        }
    }

    //shuffle
    public function shuffle() {
        $n = count($this->cards);  
        while ($n > 1) {  
            $n--;
            $k = random_int(0,$n);  
            $value = $this->cards[$k];  
            $this->cards[$k] = $this->cards[$n];  
            $this->cards[$n] = $value;  
        } 
    }

    //draw
    /**
     * Summary of draw
     * @param Card[] $hand
     * @param bool $face_down
     * @return void
     */
    public function draw($hand, $face_up = true): Card {
        $card = array_shift($this->cards);
        $card->face_up = $face_up;
        array_push($hand,$card);
        array_push($this->discard,$card);

        return $card;
    }
}