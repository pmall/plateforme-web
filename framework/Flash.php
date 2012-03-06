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

	public function get($key){

		if(array_key_exists($key, $this->values)){

			return $this->values[$key];

		}

	}

	public function set($key, $value){

		$this->values[$key] = $value;

		$_SESSION['_flash'][$key] = $value;

		return $value;

	}

}

?>
