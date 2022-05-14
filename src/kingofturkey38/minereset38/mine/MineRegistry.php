<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\mine;

use kingofturkey38\minereset38\Main;
use SOFe\AwaitGenerator\Await;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class MineRegistry{
	use SingletonTrait;

	private Config $config;

	/** @var Mine[] */
	private array $mines = [];

	public function __construct(){
		$this->config = new Config(Main::getInstance()->getDataFolder() . "config.json", Config::JSON);

		foreach($this->config->getAll() as $k => $v){
			$this->addMine(Mine::jsonDeserialize($v));
		}
	}

	public function getMine(string $name) : ?Mine{
		return $this->mines[$name] ?? null;
	}

	public function addMine(Mine $mine) : void{
		$this->mines[$mine->name] = $mine;

		Await::f2c(function() use ($mine){
			$std = Main::getInstance()->getStd();

			while(true){
				if(($mine = $this->getMine($mine->name)) !== null){
					$nextReset = $mine->lastReset + $mine->resetTime;

					if(count($mine->blocks) > 0){
						if(time() >= $nextReset){
							yield $mine->tryReset();
						}
					}

					yield $std->sleep(12);
					continue;
				}

				break;
			}
		});
	}

	public function getAllMines(): array {
		return $this->mines;
	}

	public function removeMine(string $name) : void{
		unset($this->mines[$name]);
	}

	public function onClose(Plugin $plugin) : void{
		$this->config->setAll($this->mines);
		$this->config->save();
	}
}