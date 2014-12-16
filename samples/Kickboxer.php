<?php

class Kickboxer implements IFighter
{
    private $attack1;
    private $attack2;
    private $defence;

    private $opponentName = "";
    private $comment = "";

    public function __construct()
    {
        $this->attack1 = Area::GROIN;
        $this->attack2 = Area::NOSE;
        $this->defence = Area::NOSE;
    }

    /**
     * @param  Move $opponentsLastMove
     * @param  null $iLost
     * @param  null $iScored
     * @return Move
     */
    public function makeNextMove(Move $opponentsLastMove = null, $iLost = null, $iScored = null)
    {
        if ($opponentsLastMove != null) {
            if (in_array($this->defence, $opponentsLastMove->getAttacks())) {
                $this->comment = 'haha, blocked your attack to my $this->defence';
            }
        } else {
            $this->comment = 'ouch';
        }
        // don't care - will only defend my head either way

        $this->attack2 = self::createRandomArea();

        if ($opponentsLastMove != null) {
            if (in_array($this->attack1, $opponentsLastMove->getBlocks())) {
                $this->attack1 = self::createRandomArea();
            }
        }

        $move = new Move();
        $move->addAttack($this->attack1)
             ->addAttack($this->attack2)
             ->addBlock($this->defence)
             ->setComment($this->comment);

        return $move;
    }

    /**
     * @return string
     */
    private static function createRandomArea()
    {
        $random = rand(0, 100);

        if ($random<30) {
            return Area::NOSE;
        }

        if ($random<70) {
            return Area::JAW;
        }

        if ($random<90) {
            return Area::GROIN;
        } // oh yeah

        return Area::LEGS;
    }
}
