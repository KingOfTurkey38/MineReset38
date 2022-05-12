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
use SOFe\AwaitGenerator\Await;

class MineRemoveBlockSubCommand extends BaseSubCommand{
	protected function prepare() : void{
		$this->registerArgument(0, new RawStringArgument("name"));
		$this->registerArgument(1, new IntegerArgument("index"));
	}

	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		if(!$p instanceof Player) return;

		$mine = MineRegistry::getInstance()->getMine($args["name"]);

		if($mine === null){
			$p->sendMessage(Main::PREFIX . "Invalid mine name");
			return;
		}

		if(isset($mine->blocks[$args["index"]])){
			$block = $mine->blocks[$args["index"]];

			unset($mine->blocks[$args["index"]]);
			$p->sendMessage(Main::PREFIX . "Removed block: §c{$block->block->getName()}§7, chance: §c{$block->chance}");
		} else $p->sendMessage(Main::PREFIX . "No blocks found at index §c{$args["index"]}");
	}
}
