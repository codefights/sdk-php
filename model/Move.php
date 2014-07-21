<?php

require_once 'Area.php';

class Move {
	private $attacks = array();
	private $blocks = array();
	private $comment;

	public function getAttacks() {
		return $this->attacks;
	}

	public function getBlocks() {
		return $this->blocks;
	}

	public function getComment() {
		return $this->comment;
	}

	public function setComment($comment) {
		$this->comment = $comment;
		return $this;
	}

	public function addAttack( $area) {
		array_push($this->attacks, $area);
    	return $this;
	}

	public function addBlock($area) {
		array_push($this->blocks, $area);
    	return $this;
	}

	public function __toString() {
    	$rez= 'Move ';

    	foreach ($this->attacks as $attack)
    		$rez=$rez.' ATTACK '.$attack;

    	foreach ($this->blocks as $block)
    		$rez=$rez.' BLOCK '.$block;

    	if ($this->comment!=null)
    		$rez=$rez.' COMMENT '.$this->comment;

    	return $rez;
	}
}

?>