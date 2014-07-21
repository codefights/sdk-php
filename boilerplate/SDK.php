<?php

require_once ("ProtocolException.php");
require_once ("server/Protocol.php");
require_once ("server/ServerMode.php");
require_once ("Commentator.php");
require_once ("Human.php");
require_once ("Arena.php");


const FIGHT_HUMAN_SWITCH = "--fight-me";
const FIGHT_BOT_SWITCH = "--fight-bot";
const RUN_ON_SERVER_SWITCH = "--fight-on-server";

define('USAGE_INSTRUCTIONS', 
				FIGHT_HUMAN_SWITCH."\t\truns your bot against you in interactive mode\n".
				FIGHT_BOT_SWITCH." boxer\truns your bot against a built-in boxer bot\n".
				FIGHT_BOT_SWITCH." kickboxer\truns your bot against a built-in kickboxer bot\n".
				RUN_ON_SERVER_SWITCH."\truns your bot in codefights engine environment\n");


class SDK {

	static function run($argv){
		array_splice($argv, 0, 1); // removing the script name
	
		if (self::isFightHumanMode($argv)){
			$arena = new Arena();
				$arena -> registerFighter(new Human(), "You");
				$arena -> registerFighter(new MyFighter(), "Your bot");
			$arena->stageFight();
		}
		else 
		if (self::isFightBotMode($argv)){
			$arena = new Arena();
				$arena -> registerFighter(new MyFighter(), "Your bot");
				$arena -> registerFighter(self::createBot($argv), $argv[1]);
			$arena -> stageFight();
		}
		else 
		if (self::isRunInServerMode($argv)){
		    $serverMode = new ServerMode();
			$serverMode -> run(new MyFighter());
		}
		else
			self::printUsageInstructions($argv);
	}

	private static function isRunInServerMode($args) {
		return (count ($args) == 1)
				&& !strcasecmp ($args[0], RUN_ON_SERVER_SWITCH);
	}

	private static function isFightBotMode($args) {
		return (count ($args) >= 2) 
				&& !strcasecmp ($args[0], FIGHT_BOT_SWITCH);
	}

	private static function isFightHumanMode($args) {
		return (count ($args) == 1) 
				&& !strcasecmp ($args[0], FIGHT_HUMAN_SWITCH);
	}

	private static function printUsageInstructions($args) {
		if (count ($args) > 0) {
			echo('unrecognized option(s): ');

			foreach ($args as $arg)
				echo($arg.' ');

			echo("\n");
		}
		echo(USAGE_INSTRUCTIONS);
	}

	private static function createBot($args) {
		if (!strcasecmp("boxer", $args[1]))
			return new Boxer();

		if (!strcasecmp("kickboxer", $args[1]))
			return new Kickboxer();

		throw new ProtocolException("unrecognized built-in bot: $args[1]");
	}
}


?>
