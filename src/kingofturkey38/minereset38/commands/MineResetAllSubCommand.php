<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\commands;

use CortexPE\Commando\BaseSubCommand;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

class MineResetAllSubCommand extends BaseSubCommand{
	protected function prepare() : void{
	}

	public function onRun(CommandSender $p, string $aliasUsed, array $args) : void{
		Await::f2c(function() use ($p){
			foreach(MineRegistry::getInstance()->getAllMines() as $mine){
				$this->msg($p, Main::PREFIX . "Trying to reset mine §c{$mine->name}");


				$result = yield $mine->tryReset();

				if($result === true){
					$this->msg($p, Main::PREFIX . "Mine §c{$mine->name}§7 has been reset.");
				}else $this->msg($p, Main::PREFIX . "Failed to reset mine §c{$mine->name}");
			}
		});
	}

	public function msg(CommandSender $sender, string $msg){
		if($sender instanceof Player && $sender->isOnline()){
			$sender->sendMessage($msg);
		}else $sender->sendMessage($msg);
	}
}
