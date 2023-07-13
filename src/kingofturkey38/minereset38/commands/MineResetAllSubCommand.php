<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;

use SOFe\AwaitGenerator\Await;

use CortexPE\Commando\BaseSubCommand;

use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;

class MineResetAllSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct("resetall", "", []);
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
		Await::f2c(function() use ($p){
			foreach(MineRegistry::getInstance()->getAllMines() as $mine){
				$this->msg($p, Main::getPrefix() . "Trying to reset mine §c{$mine->name}");


				$result = yield from $mine->tryReset();

				if($result === true){
					$this->msg($p, Main::getPrefix() . "Mine §c{$mine->name}§7 has been reset.");
				}else $this->msg($p, Main::getPrefix() . "Failed to reset mine §c{$mine->name}");
			}
		});
	}

	public function msg(CommandSender $sender, string $msg){
		if($sender instanceof Player && $sender->isOnline()){
			$sender->sendMessage($msg);
		}else $sender->sendMessage($msg);
	}
}
