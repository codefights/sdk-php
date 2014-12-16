<?php

class ServerResponse
{
    public $move;
    public $score1;
    public $score2;
}

class Protocol
{
    const HANDSHAKE = "I-AM ready";

    const REQUEST_HEADER = "";

    const YOUR_SCORE = "YOUR-SCORE";
    const OPPONENT_SCORE = "OPPONENT-SCORE";
    const ENEMY_MOVE = "ENEMY-MOVE";
    const MOVE_COMMENT = "COMMENT";

    private $outStream;

    private $inStream;

    /**
     * @param $inStream
     * @param $outStream
     */
    public function __construct($inStream, $outStream)
    {
        $this->outStream = $outStream;
        $this->inStream = $inStream;
    }

    public function handshake()
    {
        fputs($this->outStream, self::HANDSHAKE."\n");
        fflush($this->outStream);
    }

    /**
     * @param Move $move
     */
    public function sendRequest(Move $move)
    {
        fputs($this->outStream, self::REQUEST_HEADER.self::serializeMove($move)."\n");
        fflush($this->outStream);
    }

    public function readResponse()
    {
        return self::parse(fgets($this->inStream));
    }

    /**
     * @param  Move   $move
     * @return string
     */
    public static function serializeMove(Move $move)
    {
        $rez = '';

        foreach ($move->getAttacks() as $attack) {
            $rez = $rez.'a'.$attack[0];
        }

        foreach ($move->getBlocks() as $block) {
            $rez = $rez.'b'.$block[0];
        }

        if ($move->getComment() != null) {
            $rez = $rez.'c'.self::sanitizeComment($move->getComment());
        }

        return strtolower($rez);
    }

    /**
     * @param $input
     * @return Move
     * @throws ProtocolException
     */
    public static function parseMove($input)
    {
        if (!isset($input) || $input == null) {
            throw new ProtocolException("Input stream was closed");
        }

        $input = trim($input);

        $rez = new Move();

        $index = 0;

        while ($index < strlen($input)) {
            $type = $input[$index++];

            switch ($type) {
                case 'a': $rez->addAttack(self::getArea($input, $index++)); break;
                case 'b': $rez->addBlock(self::getArea($input, $index++)); break;
                case '.':
                case 'c': $rez->setComment(substr($input, $index));
                          $index = strlen($input) + 1;
                break;
                case ' ':
                case '\t':
                    continue;
                default:
                    throw new ProtocolException('Unrecognized input: '.$type);
            }
        }

        return $rez;
    }

    /**
     * @param $comment
     * @return mixed|string|void
     */
    public static function sanitizeComment($comment)
    {
        if (!isset($comment) || $comment == null) {
            return;
        }

        $breaks = array("\t", "\n", "\"");
        $result = str_replace($breaks, " ", $comment);
        $result = trim($result);

        if (strlen($result) > 150) {
            $result = substr($result, 0, 150);
        }

        return $result;
    }

    /**
     * @param $line
     * @return ServerResponse
     * @throws ProtocolException
     */
    protected static function parse($line)
    {
        $result = new ServerResponse();

        $words = explode(' ', $line);
        $index = 0;

        while ($index < count($words)) {
            $firstKeyword = $words[$index++];

            if ($index >= count($words)) {
                throw new ProtocolException(
                        'Insufficient params in {'.$line.'}. Syntax is [YOUR-SCORE area] [OPPONENT-SCORE area] [ENEMY-MOVE move]');
            }

            $nextKeyword = $words[$index++];

            if (!strcasecmp(self::YOUR_SCORE, $firstKeyword)) {
                $result->score1 = intval($nextKeyword);
            } elseif (!strcasecmp(self::OPPONENT_SCORE, $firstKeyword)) {
                $result->score2 = intval($nextKeyword);
            } elseif (!strcasecmp(self::ENEMY_MOVE, $firstKeyword)) {
                $result->move = self::parseMove($nextKeyword);
            } else {
                throw new ProtocolException(
                        "invalid keyword $firstKeyword. Syntax is [YOUR-SCORE area] [OPPONENT-SCORE area] [ENEMY-MOVE move]");
            }
        }

        return $result;
    }

    /**
     * @param $line
     * @param $index
     * @return string
     * @throws ProtocolException
     */
    private static function getArea($line, $index)
    {
        if ($index >= strlen($line)) {
            throw new ProtocolException('Must also specify attack/defence area!');
        }

        switch ($line[$index]) {
        case 'n':
            return Area::NOSE;
        case 'j':
            return Area::JAW;
        case 'b':
            return Area::BELLY;
        case 'g':
            return Area::GROIN;
        case 'l':
            return Area::LEGS;
        default:
            throw new ProtocolException('Unrecognized area: '.$line[$index]);
        }
    }
}
