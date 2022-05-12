<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MineDeleteSubCommand extends BaseSubCommand{
	protected function prepare() : void{
		$this->registerArgument(0, new RawStringArgument("name"));
	}

	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::PREFIX . "Invalid mine name");
			return;
		}

		MineRegistry::getInstance()->removeMine($mine->name);

		$p->sendMessage(Main::PREFIX . "Deleted mine: {$mine->name}");
	}
}
