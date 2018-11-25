<?php
require 'Cell.php';
require 'Player.php';

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

	private function determineQueue(){//определяет очередность хода
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

		if($affectedCells1==20) return 2;
		if($affectedCells2==20) return 1;
		if($affectedCells1<20 && $affectedCells2<20) return 0;
	 }

	 public function createHTML(){
	 	$p1=file_get_contents("/home/artur/BattleShip/JSON/player1.json");
		$player1=json_decode($p1);
		$p2=file_get_contents("/home/artur/BattleShip/JSON/player2.json");
		$player2=json_decode($p2);
		$step=Controller::determineQueue();

		$head='
		<html>
		<head>
		<meta http-equiv="refresh" content="10">
		</head>';

		$body='
		<body style="background-image:url(back.jpg);background-repeat:no-repeat;">
		';

		$footer='
		</body>
		</html>';


	
		$count1=0;
		$count2=0;

		 if($step==1){
			$cells=$player1->cells;
			$enemyCells=$player2->cells;
		}
		if($step==2){
			$cells=$player2->cells;
			$enemyCells=$player1->cells;
		}

		$body.='<p>Вражеское поле: </p>
			<table style="float:left;margin-top:30px;">
			<tr>';

			foreach ($enemyCells as $enemyCell) {
				 if($enemyCell->cellCondition==0 || $enemyCell->cellCondition==1){
					 $body.='<td><a target="_self" href="index.php?action=moveStep&chosenCell='.(string)$enemyCell->numCell.'">'.'<img src="freeCell.jpg" alt="Not found"/>'.'</a></td>   ';
				}
				if($enemyCell->cellCondition==2){
					$body.='<td>'.'<img src="slip.jpg" alt="Not found"/>'.'</td>';
				}
				if($enemyCell->cellCondition==3){
					$body.='<td>'.'<img src="damageCell.jpg" alt="Not found"/>'.'</td>';
				}
				
				$count2++;
				if($count2%10==0){
					if($count2==100){
						$body.='</tr>';
					}else{
					$body.='</tr> <tr>';
				}
				}
			}
			$body.='</table>';

			$body.='
			<br>
			<table style="margin:10px 500px 500px 300px;">
			<tr>';
			foreach ($cells as $cell) {
				if($cell->cellCondition==0){
					$body.='<td>'.'<img src="freeCell.jpg" alt="Not found"/>'.'</td>';
				}
				if($cell->cellCondition==1){
					$body.='<td>'.'<img src="busyCell.jpg" alt="Not found"/>'.'</td>';
				}
				if($cell->cellCondition==2){
					$body.='<td>'.'<img src="slip.jpg" alt="Not found"/>'.'</td>';
				}
				if($cell->cellCondition==3){
					$body.='<td>'.'<img src="damageCell.jpg" alt="Not found"/>'.'</td>';
				}
				$count1++;
				if($count1%10==0){
					if($count1==100){
						$body.='</tr>';
					}else{
					$body.='</tr> <tr>';
				}

				}
			}
			$body.='</table>';
			
		echo $head.$body.$footer;
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

}
?>
