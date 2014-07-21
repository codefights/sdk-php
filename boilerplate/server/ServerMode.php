<?php

class ServerMode {

	private $inStream ;
	private $outStream;
	private $cancelFlag = false;

	public function __construct(){
		$this->inStream  = fopen("php://stdin", "r");
		$this->outStream = fopen("php://stdout", "w");
	}

	public function run(IFighter $fighter){
		$protocol = new Protocol($this->inStream, $this->outStream);
		$protocol->handshake();

		$resp = new ServerResponse();

		while (!$this->cancelFlag) {
			$move = $fighter->makeNextMove($resp->move, $resp->score1, $resp->score2);
			$protocol->sendRequest($move);
			$resp = $protocol->readResponse();
		}
	}
	
	public function setInputStream($istream){
		$this->inStream=$istream;
	}
	
	public function setOutputStream($ostream){
		$this->outStream = $ostream;
	}
	
	public function cancel(){
		$this->cancelFlag = true;
	}

}

?>