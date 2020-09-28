<?php

namespace view;

class GameView  {
	const STARTING_NUMBER_OF_STICKS = 22;
	private static $DRAW_GET_INDEX = "draw";
	private static $STARTOVER_GET_INDEX = "start";

	private $game;
	private $computerPlayer;
	private $errorMessage = "";

	public function __construct(\model\LastStickGame $game, \model\ComputerPlayer $computer) {
		$this->game = $game;
		$this->computerPlayer = $computer;
	}

	//Action
	public function playerSelectedSticks() : bool {
		if (isset($_GET[self::$DRAW_GET_INDEX])) {
			try {
				$this->getSelection(); //this one throws exception if the input is wrong
				return true;
			} catch (\model\InvalidSticksAmountException $e) {
				$this->errorMessage = "Not valid input, please select one of the three buttons below";
			}
		}
		return false;
	}

	public function playerWantsToStartOver() : bool {
		return isset($_GET[self::$STARTOVER_GET_INDEX]);
	}

	public function getSelection() : \model\StickAmount {
	 return new \model\StickAmount($_GET[self::$DRAW_GET_INDEX]);
	}

	public function getHTMLTitle() : string {
		return "Game of sticks";
	}

	public function getHTMLBody() : string {
		if ($this->game->isGameOver()) {
			return 	$this->showSticks() . 
					$this->showWinner() . 
					$this->getStartOverURL();
		} else {
			return 	$this->showSticks() . 
					$this->showSelection();
		}
	}


	private function showSticks() : string {
		$numSticks = $this->game->getNumberOfSticks();
		
		$aiDrew = 0;
		$opponentsMove = "";
		if ($this->computerPlayer->hasMoved()) { 
			$aiDrew = $this->computerPlayer->getLastMove()->getNumSticks();
			$opponentsMove = "Your opponent drew $aiDrew stick". ($aiDrew > 1 ? "s" : ""); //TODO: handle code duplication
		}


		//Make a visualisation of the sticks 
		$sticks = "";
		for ($i = 0; $i < $numSticks; $i++) {
			$sticks .= "I"; //Sticks remaining
		}
		for (; $i < $aiDrew + $numSticks; $i++) {
			$sticks .= "."; //Sticks taken by opponent
		}
		for (; $i < self::STARTING_NUMBER_OF_STICKS; $i++) {
			$sticks .= "_"; //old sticks
		}

		return "<p>$opponentsMove</p>
				<p>There " . ($numSticks > 1 ? "are" : "is") ." $numSticks stick" . ($numSticks > 1 ? "s" : "") ." left</p>
				<p class='stick'>$sticks</p>
				";
	}

	private function showSelection() : string {
		
		$numSticks = $this->game->getNumberOfSticks();

		$ret = "<h2>Select number of sticks</h2>
				<p>The player who draws the last stick looses. </p>
				<p>$this->errorMessage</p>";
		$ret .= "<ol>";

		//HTML for the selection buttons 
		for ($i = 1; 
			 $i <= 3 && $i <= $numSticks; 
			 $i++ ) {
			$ret .= "<li>
						<a href='?". self::$DRAW_GET_INDEX ."=$i'>Draw $i stick". ($i > 1 ? "s" : ""). "</a>
					</li>";
		}
		$ret .= "<ol>";

		return $ret;
	}

	private function showWinner() : string {
		if ($this->game->humanPlayerWon()) {
			return "<h2>Congratulations</h2>
					<p>You forced the opponent to draw the last stick!</p>";
		} else {
			return "<h2>Epic FAIL!</h2>
					<p>You were forced to draw the last stick</p>";
		}
	}

	private function getStartOverURL() : string {
		return "<a href='?". self::$STARTOVER_GET_INDEX ."'>Start new game</a>";
	}
}