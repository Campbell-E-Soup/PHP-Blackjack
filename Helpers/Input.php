<?php

namespace Helpers;

class Input {
    public static function get_input($prompt = "", $valid_answers = null) : string {
        $valid = false;
        $response = "";
        while (!$valid) {
            echo $prompt;
            echo "\033[38;2;255;215;0m";
            
            $response = strtolower(trim(fgets(STDIN)));
            echo "\033[0m\n";
            if ($valid_answers != null) {
                //validate
                if (in_array($response,$valid_answers)) {
                    $valid = true;
                }
                else {
                    echo "\n\033[38;5;160mInvalid response, did not regonize '$response' please try again.\033[0m\n";
                }
            }
            else {
                $valid = true;
            }
        }
        return $response;
    }
}