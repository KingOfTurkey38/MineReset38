<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;

class MineCommand extends BaseCommand{
	protected function prepare() : void{
		$this->setPermission("minereset38.mine");

		$this->registerSubCommand(new MineCreateSubCommand("create"));
		$this->registerSubCommand(new MineInfoSubCommand("info"));
		$this->registerSubCommand(new MineListSubCommand("list"));
		$this->registerSubCommand(new MineResetSubCommand("reset"));
		$this->registerSubCommand(new MineAddBlockSubCommand("addblock"));
		$this->registerSubCommand(new MineRemoveBlockSubCommand("removeblock"));
		$this->registerSubCommand(new MineResetTimeSubCommand("setresettime"));
		$this->registerSubCommand(new MineDeleteSubCommand("delete"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{ }
}
