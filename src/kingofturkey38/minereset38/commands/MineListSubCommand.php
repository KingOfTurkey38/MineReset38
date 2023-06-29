<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;

use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;

class MineListSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("list");
		$this->setPermission("minereset38.mine");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
	}

	/**
	 * @param CommandSender $p
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $p, string $aliasUsed, array $args): void{
		if(!$p instanceof Player) return;

		$all = MineRegistry::getInstance()->getAllMines();

		$p->sendMessage(Main::getPrefix() . "§7all mines: ");
		foreach($all as $mine){
			$p->sendMessage("   §7- §a{$mine->name}");
		}
	}
}
