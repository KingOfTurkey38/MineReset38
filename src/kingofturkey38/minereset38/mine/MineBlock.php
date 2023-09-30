<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\mine;

use JsonSerializable;

use pocketmine\block\Block;

use pocketmine\item\StringToItemParser;
use pocketmine\item\LegacyStringToItemParser;

class MineBlock implements JsonSerializable{

	public function __construct(
		public Block $block,
		public int $chance,
	){
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function jsonDeserialize(array $data): self {
		$blockID = $data["blockID"];
		if(is_int($blockID)){
			$block = LegacyStringToItemParser::getInstance()->parse($blockID.":".$data["meta"] ?? "0");
		}else{
			$block = StringToItemParser::getInstance()->parse($blockID);
		}
		return new MineBlock($block->getBlock(), $data["chance"]);
	}
	

	/**
	 * @return array
	 */
	public function jsonSerialize(): array{
		return [
			"blockID" => StringToItemParser::getInstance()->lookupBlockAliases($this->block)[0],
			"chance" => $this->chance
		];
	}
}