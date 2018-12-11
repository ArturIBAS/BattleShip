<?php

class Controller{
	public function action(){
		require 'connect_db.php';

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
		$cells=Controller::arangeShips();
		$countPlayers=$pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn();//проверка на пустоту таблицы players
		
		if($countPlayers==0){
			$player_obj=new Player($_GET['namePlayer'],$cells,true);
			$time=date('l jS \of F Y h:i:s A');
			$count=$pdo->exec("INSERT INTO games VALUES(DEFAULT,'$time',' ',' ',' ')");//при создании первого игра создаётся запись игры
			$count=$pdo->exec("INSERT INTO players VALUES(DEFAULT,1,'$player_obj->name',true)");
		}else{
			$player_obj=new Player($_GET['namePlayer'],$cells,false);
			$count=$pdo->exec("INSERT INTO players VALUES(DEFAULT,2,'$player_obj->name',false)");
		}

			$p=$pdo->query("SELECT id FROM players WHERE id=(SELECT MAX(id) FROM players)");
			$arrPlayer=$p->fetch();
			$g=$pdo->query("SELECT id FROM games");
			$arrGame=$g->fetch();
			$idGame=$arrGame['id'];
			$idPlayer=$arrPlayer['id'];
			$count=$pdo->exec("INSERT INTO fields VALUES(DEFAULT,$idPlayer,$idGame);");
			$f=$pdo->query("SELECT id FROM fields WHERE id=(SELECT MAX(id) FROM fields)");
			$arrField=$f->fetch();
			$idField=$arrField['id'];

			foreach ($cells as $cell) {
				$count=$pdo->exec("INSERT INTO cells VALUES(DEFAULT,$cell->numCell,$cell->cellCondition,$idField);");
			}

	}

		public static function getPlayer($numPlayer){
		require 'connect_db.php';

		$p=$pdo->query("SELECT * FROM players WHERE num_player=$numPlayer");
        $arrPlayer=$p->fetch();
        $playerId=$arrPlayer['id'];
        
        $f=$pdo->query("SELECT * FROM fields WHERE player_id=$playerId");
        $arrField=$f->fetch();
        $fieldId=$arrField['id'];

        $c=$pdo->query("SELECT * FROM cells WHERE field_id=$fieldId");


        while ($cellOutTable=$c->fetch()) {
                $cells[]=new Cell($cellOutTable['num_cell'],$cellOutTable['cell_condition']);
            }

            $player=new Player($arrPlayer['name_player'],$cells,$arrPlayer['is_attack']);
            
             return $player;
            
        }


	public static function determineQueue(){
		require 'connect_db.php';
		$p1=$pdo->query("SELECT * FROM players WHERE num_player=1");
		$arrPlayer1=$p1->fetch();

		$p2=$pdo->query("SELECT * FROM players WHERE num_player=2");
		$arrPlayer2=$p2->fetch();
		
		if($arrPlayer1['is_attack']){
			return 1;
		}

		if($arrPlayer2['is_attack']){
			return 2;
		}

	}

	public static function fillPlayer($player,$numPlayer){
		require 'connect_db.php';
		$count=$pdo->exec("UPDATE players SET is_attack=$player->isAttack WHERE num_player=$numPlayer");

		$p=$pdo->query("SELECT id FROM players WHERE num_player=$numPlayer");
        $arrPlayer=$p->fetch();
        $playerId=$arrPlayer['id'];
        
        $f=$pdo->query("SELECT id FROM fields WHERE player_id=$playerId");
        $arrField=$f->fetch();
        $fieldId=$arrField['id'];

        $cells=$player->cells;
		foreach ($cells as $cell) {
				$count=$pdo->exec("UPDATE cells SET cell_condition=$cell->cellCondition WHERE num_cell=$cell->numCell and field_id=$fieldId");
			}

	}

	 public function moveStep($pdo){//выполнение хода
	 	$player1=Controller::getPlayer(1);
	 	$player2=Controller::getPlayer(2);

		$step=Controller::determineQueue();
		$cells=[];
		$enemyCells=[];
		
		if($step==1){
			$walkPlayer=$player1;
			$enemyPlayer=$player2;
		}
		if($step==2){
			$walkPlayer=$player2;
			$enemyPlayer=$player1;
		}

		echo "Ход игрока $walkPlayer->name";

		foreach ($enemyPlayer->cells as $cell) {
			if($cell->numCell==$_GET['chosenCell']){
				if($cell->cellCondition==0){
					$cell->cellCondition==2;
					$walkPlayer->isAttack=false;
					$enemyPlayer->isAttack=true;
					break;
				}
				if($cell->cellCondition==1) $cell->cellCondition=3;
			}
		}

		if($step==1){
			$player1=$walkPlayer;
			$player2=$enemyPlayer;
		}
		
		if($step==2){
			$player2=$walkPlayer;
			$player1=$enemyPlayer;
		}

		Controller::fillPlayer($player1,1);
		Controller::fillPlayer($player2,2);


	 }
	




	 public function determineWinner(){
	 	if($pdo->query("SELECT COUNT(*) as count FROM players")->fetchColumn()==2){
	 	$player1=Controller::getPlayer(1);
	 	$player2=Controller::getPlayer(2);
		$cells1=$player1->cells;
		$cells2=$player2->cells;
		$affectedCells1=0;
		$affectedCells2=0;
		foreach ($cells1 as  $cell) {
			if($cell->cellCondition==3) $affectedCells1++;
		}
		foreach ($cells2 as  $cell) {
			if($cell->cellCondition==3) $affectedCells2++;
		}

		if($affectedCells1==20) {
			return 2;
	}
		if($affectedCells2==20){
			return 1;
		}
		return 0;
	}
	return 0;
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
//Конец класса
}
?>
