<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\BaseSubCommand;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MineListSubCommand extends BaseSubCommand{
	protected function prepare() : void{
	}

	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		if(!$p instanceof Player) return;

		$all = MineRegistry::getInstance()->getAllMines();

		$p->sendMessage(Main::PREFIX . "§7all mines: ");
		foreach($all as $mine){
			$p->sendMessage("   §7- §a{$mine->name}");
		}
	}
}
