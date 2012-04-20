<?php

class Flash{

	public static $_instance;
	private $values;

	public static function getInstance(){

		if(!isset(self::$_instance)){

			self::$_instance = new Flash();

		}

		return self::$_instance;

	}

	private function __construct(){

		$this->values = array();

		# Si il y a du flash dans la session
		if(array_key_exists('_flash', $_SESSION)){

			# On récupère les valeurs du flash
			$this->values = $_SESSION['_flash'];

			# On vide le flash de la session
			unset($_SESSION['_flash']);

		}

	}

	public static function set($key, $value){

		$flash = Flash::getInstance();

		$flash->values[$key] = $value;

		$_SESSION['_flash'][$key] = $value;

		return $value;

	}

	public static function get($key){

		$flash = Flash::getInstance();

		if(array_key_exists($key, $flash->values)){

			return $this->values[$key];

		}else{

			return null;

		}

	}

	public static function all(){

		$flash = Flash::getInstance();

		return $flash->values;
	}

}

?>
