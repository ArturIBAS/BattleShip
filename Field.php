<?php
class Field{
	public $cells;
	public $side;

	function __construct($cellsOfField,$sideOfField){
		$this->cells=$cellsOfField;
		$this->side=$sideOfField;
	}
}

?>
