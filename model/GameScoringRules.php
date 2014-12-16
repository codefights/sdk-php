<?php

require_once 'Area.php';

/***
 * scoring rules of the game: knows what hit causes what damage.
 *
 * @author Mantas Urbonas
 *
 */
class GameScoringRules
{
    const NOSE_SCORE = 10;
    const JAW_SCORE = 8;
    const BELLY_SCORE = 6;
    const GROIN_SCORE = 4;
    const LEGS_SCORE = 3;

    const LIFEPOINTS = 150;

    /**
     * @param $attacks
     * @param $blocks
     * @return int
     * @throws Exception
     */
    public static function calculateScore($attacks, $blocks)
    {
        $rez = 0;

        if (isset($attacks)) {
            foreach ($attacks as $attack) {
                if (in_array($attack, $blocks)) {
                    continue;
                }

                $rez = $rez + self::getAttackSeverity($attack);
            }
        }

        return $rez;
    }

    /**
     * @param $attack
     * @return int
     * @throws Exception
     */
    public static function getAttackSeverity($attack)
    {
        if ($attack == Area::NOSE) {
            return self::NOSE_SCORE;
        }

        if ($attack == Area::JAW) {
            return self::JAW_SCORE;
        }

        if ($attack == Area::GROIN) {
            return self::GROIN_SCORE;
        }

        if ($attack == Area::BELLY) {
            return self::BELLY_SCORE;
        }

        if ($attack == Area::LEGS) {
            return self::LEGS_SCORE;
        }

        throw new Exception('Unknown attack vector: '.$attack);
    }

    /**
     * @param  Move $move
     * @return bool
     */
    public static function isMoveLegal(Move $move)
    {
        $attacks = $move->getAttacks();
        $blocks =  $move->getBlocks();

        return (count($attacks) + count($blocks) <= 3);
    }
}
