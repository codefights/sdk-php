<?php

class Arena {

	private $fighters=array();
	private $commentator;

	function __construct(){
		$this->commentator = new Commentator();
	}

	function registerFighter(IFighter $fighter, $name) {
		$this->fighters[$name]=$fighter;
		return $this;
	}

	function stageFight() {
		if (count($this->fighters) != 2)
			throw new ProtocolException("Must be 2 fighters!");

		foreach($this->fighters as $f1name=>$fighter1)
			break;
		unset($this->fighters[$f1name]);

		foreach($this->fighters as $f2name=>$fighter2)
			break;
		unset($this->fighters[$f2name]);

		$this->commentator->setFighterNames($f1name, $f2name);

		$f1Move = null;
		$f2Move = null;

		$score1 = 0;
		$score2 = 0;

		$f1Lifepoints = GameScoringRules::LIFEPOINTS;
		$f2Lifepoints = GameScoringRules::LIFEPOINTS;

		while ($f1Lifepoints > 0 && $f2Lifepoints > 0) {
			$move1 = $fighter1->makeNextMove($f2Move, $score1, $score2);
			$move2 = $fighter2->makeNextMove($f1Move, $score2, $score1);

			$score1 = GameScoringRules::calculateScore($move1->getAttacks(),
								$move2->getBlocks());
			$score2 = GameScoringRules::calculateScore($move2->getAttacks(),
								$move1->getBlocks());

			$this->commentator->describeRound($move1, $move2, $score1, $score2);

			$f1Lifepoints -= $score2;
			$f2Lifepoints -= $score1;

			$f1Move = $move1;
			$f2Move = $move2;
		}

		$this->commentator->gameOver($f1Lifepoints, $f2Lifepoints);
	}

	function setCommentator(Commentator $c) {
		$this->commentator = $c;
		return $this;
	}

}

?>