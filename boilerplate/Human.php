<?php 

class Human implements IFighter {
	private $consoleOut;
	private $consoleIn;

	public function __construct(){
		$this->consoleOut = fopen("php://stdout", "w");
		$this->consoleIn = fopen("php://stdin", "r");
	}

	public function makeNextMove(Move $oppMove=null, $iScored=0, $oppScored=0) {
		self::printInstructions();

		while (true)
			try {
			
				return self::parseInput( trim ( fgets ( $this->consoleIn ) ) );

			} catch (ProtocolException $ipe) {
				echo("Human error: ".$ipe->getMessage());
			} catch (Exception $oce) {
				die("Bye");
			}
	}

	private function printInstructions() {
		fputs($this->consoleOut, "Make your move by (A)ttacking and (B)locking (N)ose, (J)aw, (B)elly, (G)roin, (L)egs");
		fputs($this->consoleOut, "  (for example, BN BB AN)");
		fputs($this->consoleOut, ": ");
	}

	private static function parseInput($input) {
		$input = str_replace(" ", "", $input);
		$input = strtolower($input);

		if (self::startsWith($input, "q"))
			throw new Exception("Exiting");

		$move = Protocol::parseMove($input);
		if (!GameScoringRules::isMoveLegal($move))
			throw new ProtocolException( "Can make max 3 things at a time!");

		return $move;
	}
	
	private static function startsWith($haystack, $needle)
	{
	     $length = strlen($needle);
	     return (substr($haystack, 0, $length) === $needle);
	}

}
?>
