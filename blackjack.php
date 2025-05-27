<?php
require_once 'Game/Card.php';
require_once 'Game/Deck.php';
require_once 'Game/Player.php';

use Game\Player;
use Game\Card;
use Game\Deck;

$deck = new Deck(8);
$rand = random_int(0,count($deck->cards));
//$deck->shuffle();
$players = [new Player()]; 
$players[0]->hit($deck);
Card::display_cards($players[0]->hand);
