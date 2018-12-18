<?php

class DB
{
	private static $instance = null;

	private static $DB_HOST = 'localhost';
	private static $DB_NAME = 'BattleShip';
	private static $DB_USER = 'artur';
	private static $DB_PASS = 'Oprznogr1';

	private  function __construct () {

	    self::$instance=new PDO(
			'pgsql:host=' . self::$DB_HOST . ';dbname=' . self::$DB_NAME,
	    	self::$DB_USER,
	    	self::$DB_PASS
	    );
		
	}

	private function __clone () {}
	private function __wakeup () {}

	public static function getInstance()
	{
		if (self::$instance != null) {
			return self::$instance;
		}else{
			self::$instance=new PDO(
			'pgsql:host=' . self::$DB_HOST . ';dbname=' . self::$DB_NAME,
	    	self::$DB_USER,
	    	self::$DB_PASS
	    );
		return self::$instance;
		}
	}
}

?>
