<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38;

use CortexPE\Commando\PacketHooker;
use kingofturkey38\minereset38\commands\MineCommand;
use kingofturkey38\minereset38\mine\MineRegistry;
use pocketmine\plugin\PluginBase;
use SOFe\AwaitStd\AwaitStd;

class Main extends PluginBase{

	const PREFIX = "§r§7[§b§lMine§dReset§a38§r§7] ";

	private static self $instance;

	private AwaitStd $std;

	public static $blockReplaceTick;

	protected function onEnable() : void{
		self::$instance = $this;
		$this->std = AwaitStd::init($this);

		if(!PacketHooker::isRegistered()){
			PacketHooker::register($this);
		}


		$this->saveResource("config.yml");
		self::$blockReplaceTick = $this->getConfig()->get("blockReplaceTick", 1000);


		$this->getServer()->getCommandMap()->register("minereset38", new MineCommand($this, "mine"));
	}

	protected function onDisable() : void{
		MineRegistry::getInstance()->onClose($this);
	}

	public static function getInstance() : Main{
		return self::$instance;
	}

	public function getStd() : AwaitStd{
		return $this->std;
	}
}
