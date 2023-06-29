<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;

use kingofturkey38\minereset38\Main;

class MineCommand extends BaseCommand{

	public function __construct(){
		parent::__construct(Main::getInstance(), "mine");
		$this->setPermission("minereset38.mine");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerSubCommand(new MineCreateSubCommand("create"));
		$this->registerSubCommand(new MineInfoSubCommand("info"));
		$this->registerSubCommand(new MineListSubCommand("list"));
		$this->registerSubCommand(new MineResetSubCommand("reset"));
		$this->registerSubCommand(new MineAddBlockSubCommand("addblock"));
		$this->registerSubCommand(new MineRemoveBlockSubCommand("removeblock"));
		$this->registerSubCommand(new MineResetTimeSubCommand("setresettime"));
		$this->registerSubCommand(new MineDeleteSubCommand("delete"));
		$this->registerSubCommand(new MineResetAllSubCommand("resetall"));
		$this->registerSubCommand(new MineToggleDiffResetSubCommand("diffreset"));
	}

	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{ }
}
