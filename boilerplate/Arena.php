<?php

class Arena
{
    private $fighters = array();
    private $commentator;

    public function __construct()
    {
        $this->commentator = new Commentator();
    }

    /**
     * @param  IFighter $fighter
     * @param $name
     * @return $this
     */
    public function registerFighter(IFighter $fighter, $name)
    {
        $this->fighters[$name] = $fighter;

        return $this;
    }

    public function stageFight()
    {
        if (count($this->fighters) != 2) {
            throw new ProtocolException("Must be 2 fighters!");
        }

        foreach ($this->fighters as $f1name => $fighter1) {
            break;
        }
        unset($this->fighters[$f1name]);

        foreach ($this->fighters as $f2name => $fighter2) {
            break;
        }
        unset($this->fighters[$f2name]);

        $this->commentator->setFighterNames($f1name, $f2name);

        $f1Move = null;
        $f2Move = null;

        $score1 = 0;
        $score2 = 0;

        $f1LifePoints = GameScoringRules::LIFEPOINTS;
        $f2LifePoints = GameScoringRules::LIFEPOINTS;

        while ($f1LifePoints > 0 && $f2LifePoints > 0) {
            $move1 = $fighter1->makeNextMove($f2Move, $score1, $score2);
            if (GameScoringRules::isMoveLegal($move1) == false) {
                throw new InvalidArgumentException($f1name." made an illegal move: ".$move1);
            }

            $move2 = $fighter2->makeNextMove($f1Move, $score2, $score1);
            if (GameScoringRules::isMoveLegal($move2) == false) {
                throw new InvalidArgumentException($f2name." made an illegal move: ".$move2);
            }

            $score1 = GameScoringRules::calculateScore($move1->getAttacks(),
                                $move2->getBlocks());
            $score2 = GameScoringRules::calculateScore($move2->getAttacks(),
                                $move1->getBlocks());

            $this->commentator->describeRound($move1, $move2, $score1, $score2);

            $f1LifePoints -= $score2;
            $f2LifePoints -= $score1;

            $f1Move = $move1;
            $f2Move = $move2;
        }

        $this->commentator->gameOver($f1LifePoints, $f2LifePoints);
    }

    /**
     * @param  Commentator $c
     * @return $this
     */
    public function setCommentator(Commentator $c)
    {
        $this->commentator = $c;

        return $this;
    }
}
