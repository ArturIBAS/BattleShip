<?php
spl_autoload_register(function ($class_name) {
	include $class_name . '.php';
});
$pdo=DB::getInstance();
$c=new Controller();
$c->action($pdo);
GeneratorHTML::createHTML($pdo);


?>
