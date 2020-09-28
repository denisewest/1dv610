<?php

namespace model;

require_once("model/SticksPile.php");

class LastStickGame {
	private $isPlayerOne;
	private $pile;

	public function __construct(SticksPile $pile) {
		$this->pile = $pile; 
		$this->startNewGame();
	}

	public function isGameOver() : bool {
		return $this->pile->getNumberOfSticks() < 2;
	}

	public function getCurrentGame() {
		if (isset($_SESSION["savegame"]) == false) {
			$game = new \model\LastStickGame(new \model\SticksPile());
		} else {
			$game = $_SESSION["savegame"];
		}
	}

	public function isInPlay() {
		return isset($_SESSION["savegame"]);
	}

	public function humanPlayerWon() : bool {
		return $this->isGameOver() && $this->isPlayerOne == false;
	}

	//Facade pattern to simplify 
	public function getNumberOfSticks() : int {
		return $this->pile->getNumberOfSticks();
	}

	public function startNewGame() {
		$this->pile->newGame();
		$this->isPlayerOne = true;
	}

	/*
	* This method makes sure the correct player makes the move and that is important to know who won.
	*/
	public function doMove(Stick $selection, bool $isPlayerOne) {
		if ($this->isPlayerOne != $isPlayerOne)
			throw new \Exception("Wrong player move");

		$this->isPlayerOne = !$this->isPlayerOne; //swap players
		
		$this->pile->removeSticks($selection);

		$_SESSION["savegame"] = $this;
	}
}