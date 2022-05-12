<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\mine;

use JsonSerializable;
use kingofturkey38\minereset38\events\MineResetEvent;
use kingofturkey38\minereset38\Main;
use pocketmine\block\BlockFactory;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\World;

class Mine implements JsonSerializable{


	public function tryReset(){
		$event = new MineResetEvent($this);
		$event->call();

		if($event->isCancelled()) return;

		$this->lastReset = time();

		if(($world = Server::getInstance()->getWorldManager()->getWorldByName($this->world)) !== null){
			Server::getInstance()->broadcastMessage(str_replace("{mine}", $this->name, Main::getInstance()->getConfig()->getNested("messages.mine-reset-announcement")));

			yield $this->reset($world);
		}
	}

	public function reset(World $world){
		$std = Main::getInstance()->getStd();

		$minX = min($this->pos1->getX(), $this->pos2->getX());
		$maxX = max($this->pos1->getX(), $this->pos2->getX());
		$minZ = min($this->pos1->getZ(), $this->pos2->getZ());
		$maxZ = max($this->pos1->getZ(), $this->pos2->getZ());
		$minY = min($this->pos1->getY(), $this->pos2->getY());
		$maxY = max($this->pos1->getY(), $this->pos2->getY());
		$blocks = yield $this->getBlocksAsRandomArray();

		$count = 0;
		$total = 0;
		$started = time();
		for($x = $minX; $x <= $maxX; $x++){
			for($z = $minZ; $z <= $maxZ; $z++){
				for($y = $minY; $y <= $maxY; $y++){
					if(!$world->isLoaded()){
						break 3;
					}

					$total++;
					$count++;

					if($count >= Main::$blockReplaceTick){
						$count = 0;
						yield $std->sleep(1);
					}

					$set = $blocks[array_rand($blocks)];
					$world->setBlockAt($x, $y, $z, $set, false);
				}
			}
		}

		$end = time();
		$time = $end - $started;

		var_dump("Took {$time}s to replace " . number_format($total) . " blocks");

		return true;
	}

	public function getBlocksAsRandomArray() {
		$arr = [];
		$std = Main::getInstance()->getStd();

		foreach($this->blocks as $block){
			for($i = 1; $i <= $block->chance; $i++){
				$arr[] = $block->block;
			}

			yield $std->sleep(1);
		}

		return $arr;
	}


	/**
	 * @param string      $name
	 * @param Vector3     $pos1
	 * @param Vector3     $pos2
	 * @param string      $world
	 * @param MineBlock[] $blocks
	 * @param int         $resetTime
	 * @param int         $lastReset
	 * @param bool 		  $isResetting
	 */
	public function __construct(
		public string $name,
		public Vector3 $pos1,
		public Vector3 $pos2,
		public string $world,
		public array $blocks,
		public int $resetTime,
		public int $lastReset,
		public bool $isResetting = false,
	){
	}

	public static function jsonDeserialize(array $data) : self{
		return new Mine(
			$data["name"],
			new Vector3(...$data["pos1"]),
			new Vector3(...$data["pos2"]),
			$data["world"],
			array_map(fn(array $v) => MineBlock::jsonDeserialize($v), $data["blocks"]),
			$data["resetTime"],
			$data["lastReset"]
		);
	}

	public function jsonSerialize(){
		return [
			"name" => $this->name,
			"pos1" => [$this->pos1->getX(), $this->pos1->getY(), $this->pos1->getZ()],
			"pos2" => [$this->pos2->getX(), $this->pos2->getY(), $this->pos2->getZ()],
			"world" => $this->world,
			"blocks" => $this->blocks,
			"resetTime" => $this->resetTime,
			"lastReset" => $this->lastReset,
		];
	}
}