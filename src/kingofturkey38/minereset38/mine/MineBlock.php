<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\mine;

use JsonSerializable;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class MineBlock implements JsonSerializable{

	public function __construct(
		public Block $block,
		public int $chance
	){
	}

	public static function jsonDeserialize(array $data) : self{
		return new MineBlock(BlockFactory::getInstance()->get($data["blockID"], $data["meta"] ?? 0), $data["chance"]);
	}

	public function jsonSerialize(){
		return [
			"blockID" => $this->block->getId(),
			"meta" => $this->block->getMeta(),
			"chance" => $this->chance
		];
	}
}