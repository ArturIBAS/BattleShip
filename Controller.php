<?php

class Controller{
	public function action(){
		if(!empty($_GET['action'])){
		switch ($_GET['action']) {
			case 'setPlayer':
				return Controller::setPlayer();
				break;
			case 'moveStep':
			return Controller::moveStep();
			break;
		
		}
	}
	}
	private function setPlayer(){//записывает объект "игрок" в файл
		$cells=Controller::arangeShips();
		$historyMoves=[];
		$wr=false;
		if(filesize("/home/artur/BattleShip/JSON/player1.json")==0){
			$player=new Player($_GET['namePlayer'],$cells,$historyMoves,TRUE);
			$fd=fopen("/home/artur/BattleShip/JSON/player1.json", "r+");
			$player=json_encode($player);
			$wr=fputs($fd,$player);
			$wr=(boolean)$wr;
		}else{
			if(filesize("/home/artur/BattleShip/JSON/player2.json")==0){
			$player=new Player($_GET['namePlayer'],$cells,$historyMoves,FALSE);
			$fd=fopen("/home/artur/BattleShip/JSON/player2.json", "r+");
			$player=json_encode($player);
			$wr=fputs($fd,$player);
			$wr=(boolean)$wr;
			}
		}
		return $wr;
	}
	// private function setPlayer(){//записывает объект "игрок" в файл
	// 	$cells=Controller::arangeShips();
		
	// }


	public static function determineQueue(){//определяет очередность хода
		$player1=file_get_contents("/home/artur/BattleShip/JSON/player1.json");
		$player1=json_decode($player1);
		$player2=file_get_contents("/home/artur/BattleShip/JSON/player2.json");
		$player2=json_decode($player2);
		if($player1->isAttack){
			return 1;
		}
		if($player2->isAttack){
			return 2;
		}
	}
	 public function moveStep(){//выполнение хода
	 	$p1=file_get_contents("/home/artur/BattleShip/JSON/player1.json");
		$player1=json_decode($p1);
		$p2=file_get_contents("/home/artur/BattleShip/JSON/player2.json");
		$player2=json_decode($p2);
		$step=Controller::determineQueue();

	 	if($step==1){
	 		echo "Ход игрока $player1->name";
	 		$enemyCells=$player2->cells;
	 		foreach ($enemyCells as $cell) {
		 			if($cell->numCell==$_GET['chosenCell']){
		 				if($cell->cellCondition==0){
		 					$cell->cellCondition=2;
		 					$player1->isAttack=false;
		 					$player2->isAttack=true;
		 					break;
		 				}
		 				if($cell->cellCondition==1) $cell->cellCondition=3;
		 				}
		 			}
		 		
	 		
	 		$player2->cells=$enemyCells;
			$p2=json_encode($player2);
			$p1=json_encode($player1);
			$wr1=file_put_contents("/home/artur/BattleShip/JSON/player1.json",$p1);
			$wr1=(boolean)$wr1;
			$wr2=file_put_contents("/home/artur/BattleShip/JSON/player2.json",$p2);
			$wr2=(boolean)$wr2;
			if($wr1 && $wr2) return true;
			unset($_GET['chosenCell']);
	}
	 	if($step==2){
	 		echo "Ход игрока $player2->name";
	 		$enemyCells=$player1->cells;
	 		foreach ($enemyCells as $cell) {
		 			if($cell->numCell==$_GET['chosenCell']){
		 				if($cell->cellCondition==0){
		 					$cell->cellCondition=2;
		 					$player2->isAttack=false;
		 					$player1->isAttack=true;
		 					break;
		 				}
		 				if($cell->cellCondition==1) $cell->cellCondition=3;
		 				}
		 			}
		 		
	 		
	 		$player1->cells=$enemyCells;
			$p1=json_encode($player1);
			$p2=json_encode($player2);
			$wr1=file_put_contents("/home/artur/BattleShip/JSON/player1.json",$p1);
			$wr1=(boolean)$wr1;
			$wr2=file_put_contents("/home/artur/BattleShip/JSON/player2.json",$p2);
			$wr2=(boolean)$wr2;
			if($wr1 && $wr2) return true;
			unset($_GET['chosenCell']);
	 	}
	 }
	 public function determineWinner(){
	 	if(filesize("/home/artur/BattleShip/JSON/player1.json")!==0 && filesize("/home/artur/BattleShip/JSON/player2.json")!==0){
	 	$p1=file_get_contents("/home/artur/BattleShip/JSON/player1.json");
		$player1=json_decode($p1);
		$p2=file_get_contents("/home/artur/BattleShip/JSON/player2.json");
		$player2=json_decode($p2);
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

		if($affectedCells1==4) {
			return 2;
	}
		if($affectedCells2==4){
			return 1;
		}
		return 0;
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
// Record the result of the game

	public function recordResultOfGame($numWinner){
		require 'connect_db.php';
		
		date_default_timezone_set('UTC');
		$p1=file_get_contents("/home/artur/BattleShip/JSON/player1.json");
		$player1=json_decode($p1);
		$p2=file_get_contents("/home/artur/BattleShip/JSON/player2.json");
		$player2=json_decode($p2);
		$winnerName='';
		$loserName='';
		$time='';
		if($numWinner==1){
		$winnerName=$player1->name;
		$loserName=$player2->name;
		}

		if($numWinner==2){
		$winnerName=$player2->name;
		$loserName=$player1->name;
		}

		$time=date('l jS \of F Y h:i:s A');
		$query="INSERT INTO playersBattleShip VALUES(DEFAULT,'$winnerName','$loserName','$time');";
		$count=$pdo->exec($query);
	 	
	 	// $query="INSERT INTO playersBattleShip VALUES(DEFAULT,'Artur1111','Artur2222');";
	 	// $count=$pdo->exec($query);	
	}
}
?>
