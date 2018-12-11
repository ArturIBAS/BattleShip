<?php
require 'connect_db.php';
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$c=new Controller();

$c->action();
GeneratorHTML::createHTML();

?>
