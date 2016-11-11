<?php

class Commentator {

	private $fighter1 = "Fighter1";
	private $fighter2 = "Fighter2";

	private $lp1 = GameScoringRules::LIFEPOINTS;
	private $lp2 = GameScoringRules::LIFEPOINTS;

	function setFighterNames( $fighter1name,  $fighter2name) {
		$this->fighter1 = $fighter1name;
		$this->fighter2 = $fighter2name;
	}

	function describeRound(Move $move1, Move $move2,  $score1,  $score2) {
		self::describeMove($this->fighter1, $move1, $score1, $move2);
		self::describeMove($this->fighter2, $move2, $score2, $move1);

		$this->lp1 -= $score2;
		$this->lp2 -= $score1;

		echo("$this->fighter1 vs $this->fighter2: $this->lp1 to $this->lp2\n\n");
	}

	function gameOver( $f1Lifepoints,  $f2Lifepoints) {
		echo("FIGHT OVER\n");
		
		if ($f1Lifepoints > $f2Lifepoints)
			echo("THE WINNER IS $this->fighter1\n");
		else 
		if ($f2Lifepoints > $f1Lifepoints)
			echo("THE WINNER IS $this->fighter2\n");
		else
			echo("IT'S A DRAW!!!\n");
	}

	private function describeMove( $fighterName, Move $move, $score, Move $counterMove) {
		echo($fighterName
				.self::describeAttacks($move, $counterMove, $score)
				.self::describeDefences($move)
				.self::describeComment($move)
				."\n");
	}

	private static function describeAttacks(Move $move, Move $counterMove, $score) {
		$attacks = $move->getAttacks();
	
		if (count ( $attacks ) <= 0)
			return " did NOT attack at all ";

		 $rez = " attacked ";
		
		foreach ( $attacks as $attack) {
			$rez = $rez.$attack;
			
			$blocked = in_array($attack, $counterMove->getBlocks());
			
			if ($blocked)
				$rez = $rez. "(-), ";
			else
				$rez = $rez."(+), ";
		}
		return $rez =$rez." scoring ".$score;
	}

	private static function describeDefences(Move $move) {
		$blocks = $move->getBlocks();
	
		if ( count ( $blocks ) <= 0)
			return "  and was NOT defending at all.";

		$rez = " while defending ";
		foreach ($blocks as $block)
			$rez = $rez.$block.", ";

		return $rez;
	}

	private static function describeComment(Move $move){
		$comment = $move->getComment();
		
		if (!isset($comment) || $comment == null || strlen($comment)<=0)
			return "";
			
		return " Also said \"".Protocol::sanitizeComment($comment)."\"";
	}

}

?>
