<?php

class Dbh{

	private static $_instance;

	public static function getInstance(){
		global $config;

		if(!isset(self::$_instance)){

			self::$_instance = new PDO(
				'mysql:dbname=' . $config['db_name'] . ';host=' . $config['db_host'],
				$config['db_user'],
				$config['db_pass']
			);

		}

		return self::$_instance;

	}

	public static function prepare($query){

		$dbh = Dbh::getInstance();

		return $dbh->prepare($query);

	}

	private function __construct(){}

}

?>
