<?php
class Cell{
    public $numCell;//номер клетки
    public $cellCondition;//сосотояние клетки (свободна-0, занята-1, мимо-2, поражена-3)
    
    public function __construct($numberCell,$cellCond){//конструктор
        $this->numCell=$numberCell;
        $this->cellCondition=$cellCond;
    }
}
?>
