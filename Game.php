<?php
class Game{
	public $gameStartTime;
	public $gameEndTime;
	public $attackedField;

	public function __construct($gameStart,$gameEnd,$attackField){//конструктор
        $this->gameStartTime=$gameStart;
        $this->gameEndTime=$gameEnd;
        $this->attackedField=$attackField;
    }
}
?>
