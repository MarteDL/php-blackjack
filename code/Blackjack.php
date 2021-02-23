<?php

declare(strict_types=1);

class Blackjack
{
    private Player $player;
    private Player $dealer;
    private Deck $deck;

    public function __construct() {

        $this->deck = new Deck();
        $this->deck->shuffle();

        $this->player = new Player($this->deck);
        $this->dealer = new Dealer($this->deck);
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getDealer(): Player
    {
        return $this->dealer;
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    function checkForWinner() : bool
    {
        if ($this->getDealer()->hasLost()) {
            return true;
        }

        if ($this->getPlayer()->hasLost()) {
            return true;
        }

        return false;
    }

    public function getWinner() : string
    {
        if ($this->getDealer()->hasLost()) {
            return 'dealer';
        }

        if ($this->getPlayer()->hasLost()) {
            return 'player';
        }
    }
}