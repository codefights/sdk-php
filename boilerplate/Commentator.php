<?php

class Commentator
{
    private $fighter1 = "Fighter1";
    private $fighter2 = "Fighter2";

    private $lp1 = GameScoringRules::LIFEPOINTS;
    private $lp2 = GameScoringRules::LIFEPOINTS;

    public function setFighterNames($fighter1name,  $fighter2name)
    {
        $this->fighter1 = $fighter1name;
        $this->fighter2 = $fighter2name;
    }

    /**
     * @param Move $move1
     * @param Move $move2
     * @param $score1
     * @param $score2
     */
    public function describeRound(Move $move1, Move $move2,  $score1,  $score2)
    {
        self::describeMove($this->fighter1, $move1, $score1, $move2);
        self::describeMove($this->fighter2, $move2, $score2, $move1);

        $this->lp1 -= $score2;
        $this->lp2 -= $score1;

        echo("$this->fighter1 vs $this->fighter2: $this->lp1 to $this->lp2\n");
    }

    /**
     * @param $f1LifePoints
     * @param $f2LifePoints
     */
    public function gameOver($f1LifePoints,  $f2LifePoints)
    {
        echo("FIGHT OVER\n");

        if ($f1LifePoints > $f2LifePoints) {
            echo("THE WINNER IS $this->fighter1\n");
        } elseif ($f2LifePoints > $f1LifePoints) {
            echo("THE WINNER IS $this->fighter2\n");
        } else {
            echo("IT'S A DRAW!!!\n");
        }
    }

    /**
     * @param $fighterName
     * @param Move $move
     * @param $score
     * @param Move $counterMove
     */
    private function describeMove($fighterName, Move $move, $score, Move $counterMove)
    {
        echo($fighterName
                .self::describeAttacks($move, $counterMove, $score)
                .self::describeDefences($move)
                .self::describeComment($move)
                ."\n");
    }

    /**
     * @param  Move   $move
     * @param  Move   $counterMove
     * @param $score
     * @return string
     */
    private static function describeAttacks(Move $move, Move $counterMove, $score)
    {
        $attacks = $move->getAttacks();

        if (count($attacks) <= 0) {
            return " did NOT attack at all ";
        }

        $rez = " attacked ";

        foreach ($attacks as $attack) {
            $rez = $rez.$attack;

            $blocked = in_array($attack, $counterMove->getBlocks());

            if ($blocked) {
                $rez = $rez."(-), ";
            } else {
                $rez = $rez."(+), ";
            }
        }

        return $rez = $rez." scoring ".$score;
    }

    /**
     * @param  Move   $move
     * @return string
     */
    private static function describeDefences(Move $move)
    {
        $blocks = $move->getBlocks();

        if (count($blocks) <= 0) {
            return "  and was NOT defending at all.";
        }

        $rez = " while defending ";
        foreach ($blocks as $block) {
            $rez = $rez.$block.", ";
        }

        return $rez;
    }

    /**
     * @param  Move   $move
     * @return string
     */
    private static function describeComment(Move $move)
    {
        $comment = $move->getComment();

        if (!isset($comment) || $comment == null || strlen($comment) <= 0) {
            return "";
        }

        return " Also said \"".Protocol::sanitizeComment($comment)."\"";
    }
}
