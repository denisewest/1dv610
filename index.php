<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("controller/GameController.php");
require_once("view/LayoutView.php");
require_once("model/ComputerPlayer.php");
require_once("model/SticksPile.php");
require_once("model/LastStickGame.php");


//Setup
session_start();

$lastStickGame = new \model\LastStickGame(new \model\SticksPile());

//Load state 
if ($lastStickGame->isInPlay() == false) {
	$game = new \model\LastStickGame(new \model\SticksPile());
} else {
	$game = $_SESSION["savegame"];
}

$computerPlayer = new \model\ComputerPlayer($game);
$view = new \view\GameView($game, $computerPlayer);
$controller = new controller\GameController($game, $view, $computerPlayer);

$controller->playGame();


//Generate output
$page = new view\LayoutView();
$page->echoPage($view->getHTMLTitle(), $view->getHTMLBody());




