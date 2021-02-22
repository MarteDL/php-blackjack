<?php
declare(strict_types=1);

require 'Suit.php';
require 'Card.php';
require 'Deck.php';

$deck = new Deck();

var_dump($deck);

$deck->shuffle();
foreach($deck->getCards() AS $card) {
    echo $card->getUnicodeCharacter(true);
    echo '<br>';
}
