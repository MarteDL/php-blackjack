<?php

declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

class Blackjack
{
    private CONST MAX_SCORE = 21;

    private Player $player;
    private Dealer $dealer;
    private Deck $deck;

    #[NoReturn] public function __construct()
    {

        $this->deck = new Deck();
        $this->deck->shuffle();

        $this->player = new Player($this->deck);
        $this->dealer = new Dealer($this->deck);

        $this->checkForBlackjack();
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

    #[NoReturn] public function checkForBlackjack () : void
    {
        if ($this->getDealer()->getScore() === self::MAX_SCORE && $this->getPlayer()->getScore() === self::MAX_SCORE) {
            $this->getPlayer()->setLost();
            $this->getDealer()->setLost();
            return;
        }
        if ($this->getDealer()->getScore() === self::MAX_SCORE) {
            $this->getPlayer()->setLost();
            return;
        }
        if ($this->getPlayer()->getScore() === self::MAX_SCORE) {
            $this->getDealer()->setLost();
            return;
        }
    }

    #[Pure] public function checkForWinner(): bool
    {
        return !(!$this->getDealer()->hasLost() && !$this->getPlayer()->hasLost());
    }

    #[Pure] public function getWinner(): string
    {
        if ($this->getDealer()->hasLost() && $this->getPlayer()->hasLost()){
            return "no one because it's a tie";
        }
        if ($this->getDealer()->hasLost()) {
            return 'you';
        }
        if ($this->getPlayer()->hasLost()) {
            return 'the dealer';
        }
    }
}