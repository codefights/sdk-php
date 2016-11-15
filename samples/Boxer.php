<?php

class Boxer implements IFighter{

    private $attack1;
    private $attack2;
    private $defence;

    private $myScoreTotal = 0;
    private $opponentScoreTotal = 0;
    private $comment="";

    public function __construct(){
        $this->attack1=Area::NOSE;
        $this->attack2=Area::JAW;
        $this->defence=Area::NOSE;
    }

    public function makeNextMove(Move $opponentsLastMove=null, $myLastScore=null, $opponentsLastScore=null) {

        $rez = new Move();

        $rez->addAttack($this->attack1)
            ->addAttack($this->attack2)
			->setComment('la la la');


		if ($opponentsLastMove != null)
	        if (in_array($this->defence, $opponentsLastMove->getAttacks()))
	            $rez->setComment("blocked your move to my $this->defence... hahaha");//good !
	        else
	            $this->changeDefence();

        $this->myScoreTotal += $myLastScore;
        $this->opponentScoreTotal += $opponentsLastScore;

        if ($this->myScoreTotal<$this->opponentScoreTotal){
            $rez->setComment('okay, meat, me is mad now... going berserk');
            $rez->addAttack(self::createRandomAttack());
        }
        else
            $rez->addBlock($this->defence);

        return $rez;
    }

    private function changeDefence() {
        if ($this->defence==Area::NOSE)
            $this->defence=Area::JAW;

        $this->defence=Area::NOSE;
    }

    private static function createRandomAttack() {
        $random = rand(0, 10);
        if ($random>=5)
            return Area::BELLY;
        return Area::JAW;
    }
}

?>
