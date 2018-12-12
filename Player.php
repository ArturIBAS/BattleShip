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

	public function copyPlayer(&$player){//КОПИРУЕТ ОБЪЕКТ
		$this->name=$player->name;
		$this->cells=$player->cells;
		$this->isAttack=$player->isAttack;
		return $this;
	}
}
?>
