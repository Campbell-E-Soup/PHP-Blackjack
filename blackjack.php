<?php
$json = file_get_contents("card-data/card-art.json");
if ($json === false) {
    die('Error reading the JSON file');
}

$json_data = json_decode($json, true); 

if ($json_data === null) {
    die('Error decoding the JSON file');
}

class Card {
    public $pip;
    public $suit;
    public function __construct($pip,$suit) {
        $this->pip = $pip;
        $this->suit = $suit;
    }
}
$cards = [new Card("A","♠"), new Card("A","♥"), new Card("A","♣"), new Card("A","♦")];

function draw_cards($cards,$json_data) {
    for ($i = 0; $i < 6; $i++) {
        foreach ($cards as $card) {
            draw_card($card->pip,$card->suit,$i,$json_data);
        }
        echo "\n";
    } 
}

function draw_card($pip,$suit,$index,$json_data) {
    $card_art = $json_data[$pip];
    $card_art = str_replace("S",$suit,$card_art[$index]);
    echo $card_art . " ";
}

draw_cards($cards,$json_data);