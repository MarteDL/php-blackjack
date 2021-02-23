<?php

declare(strict_types=1);

require('Deck.php');

class Player
{
    private array $cards = [];
    private bool $lost = false;

    CONST MAX_NUMBER = 21;

    public function __construct(Deck $deck)
    {
        $this->cards += $deck->drawCard();
        $this->cards += $deck->drawCard();
    }

    public function getScore(): int
    {
        foreach ($this->cards as $card) {
            $score += $card->getValue();
        }

        return $score;
    }

    public function hit(Deck $deck)
    {
        $this->cards += $deck->drawCard();

        if ($this->cards->getScore() > self::MAX_NUMBER){
            $this->lost = true;
        }

    }

    public function hasLost()
    {
        return $this->lost;
    }

    public function surrender()
    {
        $this->lost = true;
    }

}