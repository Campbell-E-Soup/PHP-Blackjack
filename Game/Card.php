<?php
namespace Game;

require_once __DIR__ . '/../Helpers/JSON_Parse.php';

use Helpers\JSON_Parse;

class Card {
    //static vars
    private static $init = false;
    private static $json_data;
    //values
    public int $value;
    public string $pip;
    public string $suit;
    public bool $face_up;
    /**
     * Summary of __construct
     * @param array $cardInfo
     */
    public function __construct($cardInfo) {
        if (!self::$init) {
            self::initilize();
        }
        $this->pip = $cardInfo[0];
        $this->suit = $cardInfo[1];
        $this->value = (int)$cardInfo[2];

        $this->face_up = true;
    }


    //static functions
    static function initilize() {
        $json = new JSON_Parse("card-data/card-art.json");

        self::$json_data = $json->json_data; 
        self::$init = true;
    }

    //functions
    public static function draw_card($pip,$suit,$index) {
        $card_art = self::$json_data["art"][$pip];
        $card_art = str_replace("S",$suit,$card_art[$index]);

        if ($pip == "B") $suit = "B";

        $color_ansi = self::$json_data["colors"][$suit];

        return $color_ansi[0] . $card_art . " " . $color_ansi[1];
    }

    public static function draw_cards($cards) {
        $drawn_cards = "";
        for ($i = 0; $i < 6; $i++) {
            foreach ($cards as $card) {
                $pip = $card->pip;
                if ($card->face_up === false) $pip = "B";
                $drawn_cards = $drawn_cards . self::draw_card($pip,$card->suit,$i);
            }
            $drawn_cards = $drawn_cards . "\n";
        }
        echo $drawn_cards;
    }
}