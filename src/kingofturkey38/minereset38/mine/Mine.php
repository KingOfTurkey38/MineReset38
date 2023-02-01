<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\mine;

use Generator;
use JsonSerializable;
use SOFe\AwaitGenerator\Await;
use kingofturkey38\minereset38\Main;
use kingofturkey38\minereset38\events\MineResetEvent;
use pocketmine\Server;
use pocketmine\event\EventPriority;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;

class Mine implements JsonSerializable{

	protected bool $diff = true; // Initial reset for the mine.

	public function tryReset(): Generator{
		$event = new MineResetEvent($this, $this->diff);
		$event->call();

		if($event->isCancelled()) return false;
		if ($this->diffReset && !$this->diff) return false;

		$this->lastReset = time();

		if(($world = Server::getInstance()->getWorldManager()->getWorldByName($this->world)) !== null){
			Await::g2c($this->watchDiff());
			$broadcast = trim(str_replace("{mine}", $this->name, Main::getInstance()->getConfig()->getNested("messages.mine-reset-announcement")));
			if ($broadcast !== "") {
				Server::getInstance()->broadcastMessage($broadcast);
			}

			return yield from $this->reset($world);
		}

		return false;
	}

	private function bb() : AxisAlignedBB {
		$minX = min($this->pos1->getX(), $this->pos2->getX());
		$maxX = max($this->pos1->getX(), $this->pos2->getX());
		$minZ = min($this->pos1->getZ(), $this->pos2->getZ());
		$maxZ = max($this->pos1->getZ(), $this->pos2->getZ());
		$minY = min($this->pos1->getY(), $this->pos2->getY());
		$maxY = max($this->pos1->getY(), $this->pos2->getY());
		return new AxisAlignedBB($minX, $minY, $minZ, $maxX, $maxY, $maxZ);
	}

	public function reset(World $world): Generator{
		$std = Main::getInstance()->getStd();
		$bb = $this->bb();

		foreach($world->getCollidingEntities($bb) as $e){
			if($e instanceof Player){
				$e->teleport($world->getSafeSpawn());
			}
		}


		$blocks = yield from $this->getBlocksAsRandomArray();

		$count = 0;
		$total = 0;
		$started = time();
		for($x = (int)$bb->minX; $x <= (int)$bb->maxX; $x++){
			for($z = (int)$bb->minZ; $z <= (int)$bb->maxZ; $z++){
				for($y = (int)$bb->minY; $y <= (int)$bb->maxY; $y++){
					if(!$world->isLoaded()){
						break 3;
					}

					$total++;
					$count++;

					if($count >= Main::$blockReplaceTick){
						$count = 0;
						yield from $std->sleep(1);
					}

					$set = $blocks[array_rand($blocks)];
					$world->setBlockAt($x, $y, $z, $set, false);
				}
			}
		}

		$end = time();
		$time = $end - $started;

		//var_dump("Took {$time}s to replace " . number_format($total) . " blocks");

		return true;
	}

	public function getBlocksAsRandomArray() {
		$arr = [];
		$std = Main::getInstance()->getStd();

		foreach($this->blocks as $block){
			for($i = 1; $i <= $block->chance; $i++){
				$arr[] = $block->block;
			}

			yield from $std->sleep(1);
		}

		return $arr;
	}

	/**
	 * @since 4.4.0
	 */	
	public bool $diffReset = true;


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
		$mine = new Mine(
			$data["name"],
			new Vector3(...$data["pos1"]),
			new Vector3(...$data["pos2"]),
			$data["world"],
			array_map(fn(array $v) => MineBlock::jsonDeserialize($v), $data["blocks"]),
			$data["resetTime"],
			$data["lastReset"],
		);
		$mine->diffReset = $data["diffReset"] ?? true;
		// if ($mine->diffReset) Await::g2c($mine->watchDiff());
		// Commented above line so the mine can have an initial reset.

		return $mine;
	}

	/**
	 * @since 4.4.0
	 * @see $this->diff
	 * @see $this->diffReset
	 * 
	 * @return \Generator<mixed, mixed, mixed, void>
	 */
	public function watchDiff() : \Generator {
		$this->diff = false;
		$std = Main::getInstance()->getStd();

		$awaitBreak = $awaitPlace = $awaitExplode = [
			BlockBreakEvent::class, fn(BlockEvent $e) : bool => $this->bb()->expand(.1, .1, .1)->isVectorInside($e->getBlock()->getPosition()), false, EventPriority::MONITOR, false
		];
		$awaitPlace[0] = BlockPlaceEvent::class;
		$awaitExplode[0] = EntityExplodeEvent::class;
		$awaitExplode[1] = function (EntityExplodeEvent $e) : bool {
			$bb = $this->bb()->expand(.1, .1, .1);
			foreach ($e->getBlockList() as $exploded) {
				if ($bb->isVectorInside($exploded->getPosition())) return true;
			}

			return false;
		};

		yield from Await::race([
			$std->awaitEvent(...$awaitBreak),
			$std->awaitEvent(...$awaitPlace),
			$std->awaitEvent(...$awaitExplode),
		]);

		$this->diff = true;
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
			"diffReset" => $this->diffReset,
		];
	}
}
