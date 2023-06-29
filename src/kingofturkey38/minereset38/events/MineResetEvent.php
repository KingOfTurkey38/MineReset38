<?php

declare(strict_types=1);

namespace kingofturkey38\minereset38\events;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

use kingofturkey38\minereset38\mine\Mine;

class MineResetEvent extends Event implements Cancellable{
	use CancellableTrait;

	public function __construct(protected Mine $mine, protected bool $diff){ }

	/**
	 * @return Mine
	 */
	public function getMine(): Mine{
		return $this->mine;
	}

	/**
	 * @since 4.4.0
	 */
	public function hasDiff(): bool{
		return $this->diff;
	}
}