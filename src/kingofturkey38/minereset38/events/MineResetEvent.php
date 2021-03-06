<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\events;

use kingofturkey38\minereset38\mine\Mine;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

class MineResetEvent extends Event implements Cancellable{
	use CancellableTrait;

	public function __construct(protected Mine $mine){ }

	public function getMine() : Mine{
		return $this->mine;
	}
}