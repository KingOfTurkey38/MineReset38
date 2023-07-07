<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\mine;

use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

use SOFe\AwaitGenerator\Await;

use kingofturkey38\minereset38\Main;

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

	/**
	 * @param string $name
	 * @return Mine|null
	 */
	public function getMine(string $name): ?Mine{
		return $this->mines[$name] ?? null;
	}

	/**
	 * @param Mine $mine
	 * @return void
	 */
	public function addMine(Mine $mine): void{
		$this->mines[$mine->name] = $mine;

		Await::f2c(function() use ($mine){
			$std = Main::getInstance()->getStd();

			while(true){
				if(($mine = $this->getMine($mine->name)) !== null){
					$nextReset = $mine->lastReset + $mine->resetTime;

					if(count($mine->blocks) > 0){
						if(time() >= $nextReset){
							yield from $mine->tryReset();
						}
					}

					yield from $std->sleep(12);
					continue;
				}

				break;
			}
		});
	}

	/**
	 * @return array
	 */
	public function getAllMines(): array {
		return $this->mines;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function removeMine(string $name): void{
		unset($this->mines[$name]);
	}

	/**
	 * @param Plugin $plugin
	 * @return void
	 */
	public function onClose(Plugin $plugin): void{
		$this->config->setAll($this->mines);
		$this->config->save();
	}
}