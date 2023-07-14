<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;

use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;

class MineToggleDiffResetSubCommand extends BaseSubCommand{
	
	public function __construct(){
		parent::__construct("diffreset", "", []);
		$this->setPermission("minereset38.mine");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new BooleanArgument("enabled", true));
	}

	/**
	 * @phpstan-param array{name: string}|array{name: string, enabled: bool} $args
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::getPrefix() . "Invalid mine name");
			return;
		}

		$enabled = $mine->diffReset = $args["enabled"] ?? !$mine->diffReset;
		$p->sendMessage(Main::getPrefix() . "Set the {$mine->name} reset mode to " . ($enabled
				? "§aOn Changed"
				: "§rAlways"
			)
		);
	}
}
