<?php

namespace Game;

require_once __DIR__ . '/../Helpers/JSON_Parse.php';

use Helpers\JSON_Parse;

class Deck {
    /** @var Card[] */
    public array $cards = [];
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
}