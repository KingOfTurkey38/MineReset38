<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;

use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineBlock;
use kingofturkey38\minereset38\mine\MineRegistry;

class MineInfoSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("info");
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

		$p->sendMessage(Main::getPrefix() . "§7Mine info {$mine->name}:");
		$p->sendMessage("§7World: §c" . $mine->world);
		$p->sendMessage("§7Pos1: §c" . $mine->pos1->getX() . "x " . $mine->pos1->getY() . "y " . $mine->pos1->getZ() . "z");
		$p->sendMessage("§7Pos2: §c" . $mine->pos2->getX() . "x " . $mine->pos2->getY() . "y " . $mine->pos2->getZ() . "z");
		$p->sendMessage("§7Reset time: §c" . $mine->resetTime . " seconds");
		$p->sendMessage("§7Seconds left till next reset: §c" . (($mine->lastReset + $mine->resetTime) - time()));
		$p->sendMessage("§7Blocks: §c");

		/** @var MineBlock $block */
		foreach($mine->blocks as $k => $block){
			$p->sendMessage("§c$k §7=>  §7Block: §c{$block->block->getName()}, §7Chance: §c{$block->chance}");
		}
	}
}
