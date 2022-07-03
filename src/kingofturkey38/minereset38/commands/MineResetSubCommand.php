<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

class MineResetSubCommand extends BaseSubCommand{
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

		Await::f2c(function() use ($mine, $p){
			$p->sendMessage(Main::PREFIX . "Trying to reset mine §c{$mine->name}");
			$result = yield $mine->tryReset();

			if(!$p->isOnline()) return;

			if($result === false){
				$p->sendMessage(Main::PREFIX . "Failed to reset mine §c{$mine->name}");
				return;
			}

			$p->sendMessage(Main::PREFIX . "Mine §c{$mine->name}§7 has been reset.");
		});
	}
}
