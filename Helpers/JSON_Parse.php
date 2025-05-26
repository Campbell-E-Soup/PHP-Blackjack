<?php

namespace Helpers;

class JSON_Parse {
    public $json_data;

    public function __construct($filepath) {
        $json = file_get_contents($filepath);
        if ($json === false) {
            die('Error reading the JSON file');
        }

        $this->json_data = json_decode($json, true); 

        if ($this->json_data === null) {
            die('Error decoding the JSON file');
        }
    }
}