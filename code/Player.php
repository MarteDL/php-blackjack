<?php

declare(strict_types=1);

class Player
{
    protected array $cards = [];
    protected bool $lost = false;
    protected $score;

    CONST MAX_NUMBER = 21;

    public function __construct(Deck $deck)
    {
        array_push($this->cards, $deck->drawCard());
        array_push($this->cards, $deck->drawCard());
    }

    public function getScore(): int
    {
        $this->score = 0;
        foreach ($this->cards as $card) {
            $this->score += $card->getValue();
        }

        return $this->score;
    }

    public function hit(Deck $deck) : void
    {
        array_push($this->cards, $deck->drawCard());

        if ($this->getScore() > self::MAX_NUMBER){
            $this->lost = true;
        }
    }

    public function setLost(): void
    {
        $this->lost = true;
    }

    public function hasLost() : bool
    {
        return $this->lost;
    }

    public function surrender() : void
    {
        $this->lost = true;
    }

    public function displayCards() : void {
        foreach($this->cards AS $card) {
            echo $card->getUnicodeCharacter(true);
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}