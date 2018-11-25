<?php
class Player{
	public $name;
	public $cells;
	public $historyOfMoves;
	public $isAttack;

	function __construct($namePlayer,$cellsPlayer,$historyOfMovesPlayer,$statusAttack){
		$this->name=$namePlayer;
		$this->cells=$cellsPlayer;
		$this->historyOfMoves=$historyOfMovesPlayer;
		$this->isAttack=$statusAttack;
	}
}
?>
