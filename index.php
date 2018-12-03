<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$c=new Controller();

if($c->determineWinner()==0){
$c->action();
GeneratorHTML::createHTML();
}

		if($c->determineWinner()==1) {
			$c->recordResultOfGame(1);
			echo "Победил первый игрок!";
		}
		
		if($c->determineWinner()==2) {
			$c->recordResultOfGame(2);
			echo "Победил второй игрок!";
		}
	

?>
