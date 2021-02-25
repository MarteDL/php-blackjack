<?php


class Dealer extends Player
{
    private CONST DRAW_UNTIL = 15;

    public function hit($deck) : void
    {
        while ($this->getScore() < self::DRAW_UNTIL) {
            parent::hit($deck);
        }
        $this->lost = ($this->getScore() > self::MAX_NUMBER);
    }
}