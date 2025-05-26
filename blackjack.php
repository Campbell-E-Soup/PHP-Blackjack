<?php
require_once 'Game/Card.php';
require_once 'Game/Deck.php';

use Game\Card;
use Game\Deck;

$deck = new Deck(8);
$rand = random_int(0,count($deck->cards));
$cards = [$deck->cards[0],$deck->cards[1],$deck->cards[2],$deck->cards[$rand]];

Card::draw_cards($cards);