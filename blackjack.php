<?php
require_once 'Game/Card.php';
require_once 'Game/Deck.php';
require_once 'Game/Player.php';
require_once 'Game/AIPlayer.php';
require_once 'Game/Dealer.php';

use Game\AIPlayer;
use Game\Dealer;
use Game\Player;
use Game\Card;
use Game\Deck;
use Helpers\Input;

/**
 * Summary of game_loop
 * @param Deck $deck
 * @return void
 */
function game_loop($deck) {
    //clean up (shuffle discard into deck if deck has been 75% used)
    $deck_count = (float)count($deck->cards);
    $discard_count = (float)count($deck->discard);
    if ($discard_count / ($discard_count + $deck_count) >= 0.75) {
        array_push($deck->cards,$deck->discard);
        $deck->discard = [];
        $deck->shuffle();
    }

    $player_count = 6; //n additional players
    
    $response = Input::get_input("How many players do you want to play with (\033[38;5;220m0-6\033[0m)? ",["1","2","3","4","5","6","0"]);
    $player_count = (int)$response;

    $players = [new Player()];
    for ($i = 1; $i <= $player_count; $i++) {
        array_push($players,new AIPlayer("X"));
    }
    shuffle($players);
    array_push($players,new Dealer());
    //assign ids to players
    foreach ($players as $index => $player) {
        $i = $index+1;
        if ($player->name == "X") {
            $player->name = "Player $i's hand: ";
            $player->id = $i;
        }
    } 
    $instalose = Dealer::deal($deck,$players);
    //sleep(3);
    if (!$instalose) {
        $max = count($players);
        for ($i = 0; $i < $max; $i++) {
            $players[$i]->reveal_cards();
            $players[$i]->display_hand();
            $players[$i]->take_turn($players,$i,$deck);
        }
    }
    else {
        //dealer had 21 you instalose
        $players[count($players)-1]->reveal_cards();
        echo "\033[38;5;160m\033[1mThe dealer has 21, everyone loses!\033[0m";
    }
}
echo "\033[1m";
echo "WELCOME TO ";
sleep(1);
echo "\033[38;5;27mPHP ";
sleep(1);
echo "\033[38;5;220mBLACKJACK\n\033[0m";
sleep(2);
$response = Input::get_input("How many decks will you be playing with (\033[38;5;220m1-8\033[0m)? ",["1","2","3","4","5","6","7","8"]);
$deck = new Deck((int)$response);
$end = false;
while (!$end) {
    game_loop($deck);
    $response = Input::get_input("\n\n\033[0m\033[1mWould you like to play again?\033[0m (\033[1m\033[38;2;255;215;0mY\033[0m/\033[1m\033[38;2;255;215;0mN\033[0m)\n",["y","n"]);
    if ($response == "n") {
        $end = true;
    }
    echo "\033[2J\033[H";
}
echo "\033[1m\033[38;2;255;215;0mThanks for Playing!!\033[0m";
