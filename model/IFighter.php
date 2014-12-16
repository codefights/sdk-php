<?php

require_once 'Area.php';
require_once 'Move.php';
require_once 'GameScoringRules.php';

interface IFighter
{
    public function makeNextMove(Move $opponentsLastMove, $myLastScore, $opponentsLastScore);
}
