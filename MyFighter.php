<?php

require_once "model/IFighter.php";
require_once ("samples/Boxer.php");
require_once ("samples/Kickboxer.php");
require_once "boilerplate/SDK.php";


/***                                                                        <br>
 *                                                                          <br>
 *                                                                          <br>
 * @author Mantas Urbonas                                                   <br>
 *                                                                          <br>
 * (C) UAB Visma Lietuva                                                    <br>
 */

class MyFighter implements IFighter{

    /**
     * analize your opponent's last move and make your next move.
     * @return fighter's next Move
	 * 
	 * NOTE: rules allow max 3 actions per Move.
	 * I.e. attack nose (1), attack groin (2) and defend nose (3).
	 * The areas are Area::NOSE (10pts), Area::JAW (8pts), Area::BELLY (6pts), Area::GROIN(4pts) and Area::LEGS(3 pts)
     */
    public function makeNextMove(Move $opponentsLastMove=null, $myLastScore=0, $opponentsLastScore=0){
		if (is_null($opponentsLastMove)) {
			$move = new Move();
			$move->addAttack(Area::NOSE);
			return $move;
		} else {
			return $opponentsLastMove;
		}
    }
	
}


// do NOT remove this line
SDK::run($argv);

?>
