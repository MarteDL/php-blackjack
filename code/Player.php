<?php

declare(strict_types=1);

class Player
{
    protected array $cards = [];
    protected bool $lost = false;

    CONST MAX_NUMBER = 21;

    public function __construct(Deck $deck)
    {
        $this->cards[] = $deck->drawCard();
        $this->cards[] = $deck->drawCard();
    }

    public function getScore(): int
    {
        $score = 0;
        foreach ($this->cards as $card) {
            $score += $card->getValue();
        }

        return $score;
    }

    public function hit(Deck $deck) : void
    {
        $this->cards[] = $deck->drawCard();

        $this->lost = ($this->getScore() > self::MAX_NUMBER);
    }

    public function setLost(): void
    {
        $this->lost = true;
    }

    public function surrender() : void
    {
        $this->setLost();
    }

    public function hasLost() : bool
    {
        return $this->lost;
    }

    public function displayCards() : string {
        $str = '';
        foreach($this->cards AS $card) {
            $str .= $card->getUnicodeCharacter(true);
        }
        return $str;
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}