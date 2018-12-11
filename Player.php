<?php
class Player{
	public $name;
	public $cells;
	public $isAttack;

	function __construct($namePlayer,$cellsPlayer,$statusAttack){
		$this->name=$namePlayer;
		$this->cells=$cellsPlayer;
		$this->isAttack=$statusAttack;
	}
}
?>
