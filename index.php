<?php
require 'Controller.php';
$c=new Controller();
if(filesize("/home/artur/BattleShip/JSON/player1.json")==0){
require '/home/artur/BattleShip/HTML/formPlayer.php';
$wr1=$c->action();//заполнение поля первого игрока
}else{
	if(filesize("/home/artur/BattleShip/JSON/player2.json")==0){
	require '/home/artur/BattleShip/HTML/formPlayer.php';
	$wr2=$c->action();//заполнение поля второго игрока
	}
}
if(filesize("/home/artur/BattleShip/JSON/player1.json")!==0 && filesize("/home/artur/BattleShip/JSON/player2.json")!==0){
	if($c->determineWinner()==0){//проверка на целостность флота у игроков
		
		$c->createHTML();//вывод своего и вражеского поля
		$wr=$c->action();	
		unset($_GET['chosenCell']);
	}else{
		if($c->determineWinner()==1) {
			$c->recordResultOfGame(1);
			echo "Победил первый игрок!";
		}
		
		if($c->determineWinner()==2) {
			$c->recordResultOfGame(2);
			echo "Победил второй игрок!";
		}
	}
}
?>
