<?php

require_once "model/IFighter.php";
require_once "samples/Boxer.php";
require_once "samples/Kickboxer.php";
require_once "boilerplate/SDK.php";

/***                                                                        <br>
 *                                                                          <br>
 *                                                                          <br>
 * @author Mantas Urbonas                                                   <br>
 *                                                                          <br>
 * (C) UAB Visma Lietuva                                                    <br>
 */

class MyFighter implements IFighter
{
    /**
     * analyze your opponent's last move and make your next move.
     * @return fighter's next Move
     *
     * NOTE: rules allow max 3 actions per Move.
     * I.e. attack nose (1), attack groin (2) and defend nose (3).
     * The areas are Area::NOSE (10pts), Area::JAW (8pts), Area::BELLY (6pts), Area::GROIN(4pts) and Area::LEGS(3 pts)
     *
     * @param  Move $opponentsLastMove
     * @param  int  $myLastScore
     * @param  int  $opponentsLastScore
     * @return Move
     */
    public function makeNextMove(Move $opponentsLastMove = null, $myLastScore = 0, $opponentsLastScore = 0)
    {
        $move = new Move();
        $move->addAttack(Area::NOSE)->addBlock(Area::GROIN)->addAttack(Area::BELLY);

        return $move;
    }
}

// do NOT remove this line
SDK::run($argv);
