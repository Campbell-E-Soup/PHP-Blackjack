<?php
require_once 'Game/Card.php';
require_once 'Game/Deck.php';
require_once 'Game/Player.php';
require_once 'Game/Dealer.php';

use Game\Dealer;
use Game\Player;
use Game\Card;
use Game\Deck;

/**
 * Summary of game_loop
 * @param Deck $deck
 * @return void
 */
function game_loop($deck) {
    //clean up (shuffle discard into deck if deck has been 75% used)
    $deck_count = count($deck->cards);
    if (count($deck->cards)) {
        array_push($deck->cards,$deck->discard);
        $deck->discard = [];
        $deck->shuffle();
    }

    //initilize player (1 (you) for now)
    $player_count = 0;

    $players = [new Player()];
    shuffle($players);
    array_push($players,new Dealer());

    Dealer::deal($deck,$players);
    //sleep(3);
    $players[0]->display_hand();
    $players[0]->take_turn($players,1,$deck);
}

$deck = new Deck(8);

game_loop($deck);
