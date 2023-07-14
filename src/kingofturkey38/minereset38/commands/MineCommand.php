<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;

use kingofturkey38\minereset38\Main;

class MineCommand extends BaseCommand{

	public function __construct(){
		parent::__construct(Main::getInstance(), "mine", "", ["minereset38"]);
		$this->setPermission("minereset38.mine");
	}

	/**
	 * @return void
	 */
	protected function prepare(): void{
		$this->registerSubCommand(new MineCreateSubCommand);
		$this->registerSubCommand(new MineInfoSubCommand);
		$this->registerSubCommand(new MineListSubCommand);
		$this->registerSubCommand(new MineResetSubCommand);
		$this->registerSubCommand(new MineAddBlockSubCommand);
		$this->registerSubCommand(new MineRemoveBlockSubCommand);
		$this->registerSubCommand(new MineResetTimeSubCommand);
		$this->registerSubCommand(new MineDeleteSubCommand);
		$this->registerSubCommand(new MineResetAllSubCommand);
		$this->registerSubCommand(new MineToggleDiffResetSubCommand);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{ }
}
