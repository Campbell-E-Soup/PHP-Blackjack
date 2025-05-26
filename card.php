<?php

class Card {
    private static $init = false;
    private static $json_data;
    //values
    public function __construct($cardInfo) {
        if (!self::$init) {
            self::initilize();
        }
    }


    //static functions
    static function initilize() {
        $json = file_get_contents("card-art.json");
        if ($json === false) {
            die('Error reading the JSON file');
        }

        self::$json_data = json_decode($json, true); 

        if (self::$json_data === null) {
            die('Error decoding the JSON file');
        }
        self::$init = true;
    }
}