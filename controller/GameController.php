<?php

namespace controller;

require_once("model/LastStickGame.php");
require_once("view/GameView.php");

class GameController {
	private $game;
	private $view;


	public function __construct(\model\LastStickGame $game, \view\GameView $view, \model\computerPlayer $player2) {
		$this->game = $game;//
		$this->view = $view;
		$this->computerPlayer = $player2;
	}

	public function playGame()  {
		//Select Use-Case depending on state
		if ($this->game->isGameOver()) {
			$this->handleGameOverActions();
		} else {
			$this->handleGamePlayActions();
		}
	}

	private function handleGameOverActions() {
		if ($this->view->playerWantsToStartOver()) {
			$this->game->startNewGame();
		}		
	}

	/**
	* Called when game is still running
	*/
	private function handleGamePlayActions() {

		if ($this->view->playerSelectedSticks()) {

			//Get high level input from player
			$sticksDrawnByPlayer = $this->view->getSelection();

			//Make changes to state
			$this->game->doMove($sticksDrawnByPlayer, true);

			//AI-Turn always follows player selections if player has not won
			if ($this->game->isGameOver() == false) {
				$this->doComputerPlayerMove();
			} 
		}
	}


	private function doComputerPlayerMove() {
		$computerSelection = $this->computerPlayer->getSelection();
		$this->game->doMove($computerSelection, false);
	}
}	