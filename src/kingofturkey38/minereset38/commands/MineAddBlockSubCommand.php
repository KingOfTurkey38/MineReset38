<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineBlock;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\block\BlockFactory;
use pocketmine\block\UnknownBlock;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MineAddBlockSubCommand extends BaseSubCommand{
	protected function prepare() : void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new IntegerArgument("blockID"));
		$this->registerArgument(2, new IntegerArgument("chance"));
	}

	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::PREFIX . "Invalid mine name");
			return;
		}

		$block = BlockFactory::getInstance()->get($args["blockID"], 0);
		if($block instanceof UnknownBlock){
			$p->sendMessage(Main::PREFIX . "Invalid block id");
			return;
		}

		if($args["chance"] > 100){
			$p->sendMessage(Main::PREFIX . "Chance cannot be greater than 100");
			return;
		}

		$mine->blocks[] = new MineBlock($block, $args["chance"]);

		$p->sendMessage(Main::PREFIX . "Added new block to mine {$mine->name}, block: {$block->getName()}, chance: {$args["chance"]}");
	}
}
