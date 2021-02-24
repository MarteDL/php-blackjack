<?php


class Dealer extends Player
{
    public function hit($deck) : void
    {
        parent::hit($deck);
        if ($this->getScore() <= 15) {
            parent::hit($deck);
        }
    }
}