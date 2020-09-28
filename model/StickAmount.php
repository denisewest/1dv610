<?php

namespace model;

/**
 * Encapsulate how many sticks a player draws
 * can be 1,2 or 3
 */
class StickAmount {

	const MIN_SELECTION = 1;
	const MAX_SELECTION = 3;

	private $stickAmount;

		/**
	 * Private constructor makes sure we cannot create outside of 1,2,3
	 */
	public function __construct(int $stickAmount) {
		if (!is_numeric($stickAmount) ) {
			throw new InvalidSticksAmountException("Not a number");
		}
		if ($stickAmount < $this->MIN_SELECTION || $stickAmount > $this->MAX_SELECTION) {
		throw new InvalidSticksAmountException();
		}

		$this->stickAmount = $stickAmount;
	}

	public function getNumSticks() : int {
		return $this->stickAmount;
	}
}