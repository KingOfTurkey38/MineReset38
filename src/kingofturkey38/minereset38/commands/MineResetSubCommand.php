<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use SOFe\AwaitGenerator\Await;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;

use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;

class MineResetSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("reset");
		$this->setPermission("minereset38.mine");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerArgument(0, new RawStringArgument("name"));
	}

	/**
	 * @param CommandSender $p
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args): void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::getPrefix() . "Invalid mine name");
			return;
		}

		Await::f2c(function() use ($mine, $p){
			$p->sendMessage(Main::getPrefix() . "Trying to reset mine §c{$mine->name}");
			$result = yield from $mine->tryReset();

			if(!$p->isOnline()) return;

			if($result === false){
				$p->sendMessage(Main::getPrefix() . "Failed to reset mine §c{$mine->name}");
				return;
			}

			$p->sendMessage(Main::getPrefix() . "Mine §c{$mine->name}§7 has been reset.");
		});
	}
}
