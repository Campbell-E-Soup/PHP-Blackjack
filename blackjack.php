<?php
require_once 'Game/Card.php';
require_once 'Game/Deck.php';
require_once 'Game/Player.php';
require_once 'Game/Dealer.php';

use Game\Dealer;
use Game\Player;
use Game\Card;
use Game\Deck;

$deck = new Deck(8);
$rand = random_int(0,count($deck->cards));
//shuffle
$deck->shuffle();

//generate players
$players = [new Player(), new Dealer];

$players[0]->hit($deck);
$players[1]->hit($deck);
$players[1]->hit($deck);

Card::display_cards($players[0]->hand);
Card::display_cards($players[1]->hand);
