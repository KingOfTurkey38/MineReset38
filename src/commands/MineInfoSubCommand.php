<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineBlock;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

class MineInfoSubCommand extends BaseSubCommand{
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

		$p->sendMessage(Main::PREFIX . "§7Mine info {$mine->name}:");
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
