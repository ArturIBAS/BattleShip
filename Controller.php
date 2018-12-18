<?php
class Controller{
	public function action($pdo){
		if(!empty($_GET['action'])){
			switch ($_GET['action']) {
				case 'setPlayer':
				return Controller::setPlayer($pdo);
				break;
				case 'moveStep':
				return Controller::moveStep($pdo);
				break;

			}
		}
	}


	public function setPlayer($pdo){
		$placementCheck=Controller::CheckPlacement();
		if($placementCheck===true){
			$cells=Controller::arangeShips();
		$countPlayers=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();//проверка на пустоту таблицы players
		
		if($countPlayers==0){
			$time=date('l jS \of F Y h:i:s A');
			$player=new Player($_GET['namePlayer']);
			$field=new Field($cells,'left');
			$game=new Game($time,' ','right');
			$pdo->exec("INSERT INTO games VALUES(DEFAULT,'$game->gameStartTime',' ','$game->attackedField')");
			$pdo->exec("INSERT INTO players VALUES(DEFAULT,'$player->name')");
		}else{
			$player=new Player($_GET['namePlayer']);
			$pdo->exec("INSERT INTO players VALUES(DEFAULT,'$player->name')");
		}

		$idPlayer=$pdo->query("SELECT id FROM players WHERE id=(SELECT MAX(id) FROM players)")->fetch()['id'];
		$idGame=$pdo->query("SELECT id FROM games WHERE id=(SELECT MAX(id) FROM games)")->fetch()['id'];

		$countPlayers=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();
		if($countPlayers==1){
			$pdo->exec("INSERT INTO fields VALUES(DEFAULT,$idPlayer,$idGame,'left');");
		}
		if($countPlayers==2){
			$pdo->exec("INSERT INTO fields VALUES(DEFAULT,$idPlayer,$idGame,'right');");
		}
		
		$idField=$pdo->query("SELECT id FROM fields WHERE id=(SELECT MAX(id) FROM fields)")->fetch()['id'];

		foreach ($cells as $cell) {
			$count=$pdo->exec("INSERT INTO cells VALUES(DEFAULT,$cell->numCell,$cell->cellCondition,$idField);");
		}
	}else{
		echo "Неправильно размещены корабли, повторите попытку. ";
	}
}

public function moveStep($pdo){

	if(Controller::determineWinner($pdo)=='no'){
		$attackedField=Controller::determineAttackedField($pdo);
		$idGame=$pdo->query("SELECT id FROM games WHERE id=(SELECT MAX(id) FROM games)")->fetch()['id'];
		$arrOutGameTable=$pdo->query("SELECT * FROM games WHERE id=(SELECT MAX(id) FROM games)")->fetch();
		$game=new Game($arrOutGameTable['game_start_time'],$arrOutGameTable['game_end_time'],$arrOutGameTable['attacked_field']);

		if($attackedField=='right'){
			$enemyField=Controller::getCells($pdo,'right');
			$fieldId=$pdo->query("SELECT id FROM fields WHERE game_id=$idGame and side='right'")->fetch()['id'];
		}
		if($attackedField=='left'){
			$enemyField=Controller::getCells($pdo,'left');
			$fieldId=$pdo->query("SELECT id FROM fields WHERE game_id=$idGame and side='left'")->fetch()['id'];
		}

		foreach ($enemyField as $cell) {
			if($cell->numCell==$_GET['chosenCell']){
				if($cell->cellCondition==0){
					$cell->cellCondition=2;
					if($attackedField=='right'){
						$game->attackedField='left';
					}
					if($attackedField=='left'){
						$game->attackedField='right';
					}
					$pdo->exec("UPDATE games SET attacked_field='$game->attackedField' WHERE id=(SELECT MAX(id) FROM games)");
					break;
				}
				if($cell->cellCondition==1) $cell->cellCondition=3;
			}
		}

		Controller::updateCells($pdo,$enemyField,$fieldId);
	}else{
		$time=date('l jS \of F Y h:i:s A');
		$pdo->exec("UPDATE games SET game_end_time='$time', attacked_field='no' WHERE id=(SELECT MAX(id) FROM games)");
	}
}

public static function updateCells($pdo,$field,$fieldId){

	foreach ($field as $cell) {
		$count=$pdo->exec("UPDATE cells SET cell_condition=$cell->cellCondition WHERE num_cell=$cell->numCell and field_id=$fieldId");
	}
}



	public static function arangeShips(){//размещает корабли на поле
		for ($i=1; $i <=100 ; $i++) {
			$cells[]=new Cell($i,0);
		}
		foreach ($cells as $cell) {
			foreach ($_GET['cell'] as $occupiedCell) {
				if($cell->numCell==(int)$occupiedCell){
					$cell->cellCondition=1;
				}
			}
		}
		return $cells;
	}

	// public static function checkTheCorrectPlacementOfShips(){
	// 	$cells=Controller::arangeShips();
	// 	$flag=true;

	// }



	public static function getCells($pdo,$side){//работает
		$fieldId=$pdo->query("SELECT id FROM fields WHERE side='$side'")->fetch()['id'];
		$cellsDetails=$pdo->query("SELECT * FROM cells WHERE field_id=$fieldId");

		while ($arrCells=$cellsDetails->fetch()) {
			$cells[]=new Cell($arrCells['num_cell'],$arrCells['cell_condition']);
		}
		uasort($cells,"Controller::mySort");
		return $cells;
	}

	public static function mySort($cell1,$cell2)//сортирует массив клеток при считывании с бд
	{
		if($cell1->numCell < $cell2->numCell) return -1;
		elseif($cell1->numCell > $cell2->numCell) return 1;
		else return 0;
	}

	public static function determineAttackedField($pdo){//работает
		$attackedField=$pdo->query("SELECT attacked_field FROM games WHERE id=(SELECT MAX(id) FROM games)")->fetch()['attacked_field'];
		return $attackedField;
	}


	public static function determineWinner($pdo){
		$leftField=Controller::getCells($pdo,'left');
		$rightField=Controller::getCells($pdo,'right');
		
		$affectedCellsOfLeftField=0;
		$affectedCellsOfRightField=0;

		foreach ($leftField as $cell) {
			if($cell->cellCondition==3) $affectedCellsOfLeftField++;
		}

		foreach ($rightField as $cell) {
			if($cell->cellCondition==3) $affectedCellsOfRightField++;
		}

		if($affectedCellsOfLeftField==20) return 'right';
		if($affectedCellsOfRightField==20) return 'left';
		return 'no';

	}

	public static function CheckPlacement() { 

		$aField=[];
		$have=false;
		for($i=0;$i<10;++$i){
			for($j=0;$j<10;++$j){

				foreach ($_GET['cell'] as $occupiedCell) {
					if ((($i*10)+$j+1)==(int)$occupiedCell) $have=true;
				}
				if($have==true) $aField[$i][$j]=1;
				else $aField[$i][$j]=0;
				$have=false;

			}
		}

        # Проверка количества строк : 
		if( count( $aField ) != 10 ) 
			return -1; 

        # Счетчик кораблей на поле : 
		$iShipCounter = 0; 
		$iSingleDeckCounter = 0; 
		$iDoubleDeckCounter = 0; 
		$iThreeDeckCounter = 0; 
		$iFourDeckCounter = 0; 

        # Перебор по строкам : 
		for( $iY = 0; $iY < 10; $iY++ ) { 

            # Проверка количества столбцов : 
			if( count( $aField[$iY] ) != 10 ) 
				return -2; 

            # Подсчет количества кораблей по палубно : 
			$sTmp = str_replace( array( '[', ']' ), array( '0,', ',0' ), json_encode( $aField[$iY] ) ); 
			$iDoubleDeckCounter += substr_count( $sTmp, '0,1,1,0' ); 
			$iThreeDeckCounter += substr_count( $sTmp, '0,1,1,1,0' ); 
			$iFourDeckCounter += substr_count( $sTmp, '0,1,1,1,1,0' ); 

            # Перебор по столбцам : 
			for( $iX = 0; $iX < 10; $iX++ ) { 

                # Фильтрация значения ячейки : 
				$aField[$iY][$iX] = intval( $aField[$iY][$iX] ); 

                # Проверка значения ячейки : 
				if( $aField[$iY][$iX] != 0 && $aField[$iY][$iX] != 1 ) 
					return -3; 

                # Если это корабль : 
				if( $aField[$iY][$iX] == 1 ) { 

                    # Проверяем что бы угловые ячейки содержали 0 : 
					if( (isset( $aField[$iY-1] ) && isset( $aField[$iY-1][$iX-1] ) && $aField[$iY-1][$iX-1] != 0) || (isset( $aField[$iY-1] ) && isset( $aField[$iY-1][$iX+1] ) && $aField[$iY-1][$iX+1] != 0) || (isset( $aField[$iY+1] ) && isset( $aField[$iY+1][$iX-1] ) && $aField[$iY+1][$iX-1] != 0) || (isset( $aField[$iY+1] ) && isset( $aField[$iY+1][$iX+1] ) && $aField[$iY+1][$iX+1] != 0) ) 
						return -4; 

                    # Проверяем, однопалубный ли это корабль : 
					if( (!isset( $aField[$iY-1] ) || $aField[$iY-1][$iX] == 0) && (!isset( $aField[$iY+1] ) || $aField[$iY+1][$iX] == 0) && (!isset( $aField[$iY][$iX-1] ) || $aField[$iY][$iX-1] == 0) && (!isset( $aField[$iY][$iX+1] ) || $aField[$iY][$iX+1] == 0) ) 
						$iSingleDeckCounter++; 

                    # Увеличиваем счетчик палуб кораблей : 
					$iShipCounter++; 
				} 
			} 
		} 

        # Проверка количества палуб на поле : 
		if( $iShipCounter != 20 ) 
			return -5; 
		if( $iSingleDeckCounter != 4 ) 
			return -6;

        # Подсчет количества кораблей по вертикали : 
		for( $iX = 0; $iX < 10; $iX++ ) { 
			$aTmp = array(); 
			for( $iY = 0; $iY < 10; $iY++ ) 
				$aTmp[] = $aField[$iY][$iX]; 
			$sTmp = str_replace( array( '[', ']' ), array( '0,', ',0' ), json_encode( $aTmp ) ); 
			$iDoubleDeckCounter += substr_count( $sTmp, '0,1,1,0' ); 
			$iThreeDeckCounter += substr_count( $sTmp, '0,1,1,1,0' ); 
			$iFourDeckCounter += substr_count( $sTmp, '0,1,1,1,1,0' ); 
		} 

        # Проверка количества кораблей на поле : 
		if( $iDoubleDeckCounter != 3 || $iThreeDeckCounter != 2 || $iFourDeckCounter != 1 ) 
			return -6; 

		return true; 
	} 


}
?>
