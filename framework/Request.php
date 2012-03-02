<?php

final class Request{

	public $get;
	public $post;
	public $session;
	public $cookies;
	public $url;
	public $method;

	# Constructeur
	public function __construct(){

		# Initialisation des parametres
		$this->get = $_GET;
		$this->post = $_POST;
		$this->session = $_SESSION;
		$this->cookies = $_COOKIE;

		# Définition de l'url de la requete
		$this->url = trim($_SERVER['REQUEST_URI'], '/');

		# Définition de la méthode http de la requete
		$method = strtolower($_SERVER['REQUEST_METHOD']);

		if(in_array('_method', array_keys($_POST))){

			$post_method = strtolower($_POST['_method']);

			if($post_method == 'put' or $post_method == 'delete'){

				$method = $post_method;

			}

		}

		$this->method = $method;

	}

	# Ajoute un parametre a la requete
	public function addParam($name, $value){

		$this->params[$name] = $value;

	}

	# Ajoute plusieurs parametres a la requete
	public function addParams(Array $params){

		foreach($params as $name => $value){

			$this->addParam($name, $value);

		}

	}

	# Retourne le param demandé (par ordre de préférence)
	public function param($param){

		# On merge tout les params dans l'ordre de préférence
		$params = array_merge(
			$this->get,
			$this->post,
			$this->session,
			$this->cookies
		);

		# Si il est présent on retourne va valeur du parametre
		if(in_array($param, array_keys($params))){

			return $params[$param];

		}else{

			return null;

		}

	}

	# On retourn tout les parametres (par ordre de préférence)
	public function getParams(){

		# On merge tout les params dans l'ordre de préférence
		$params = array_merge(
			$this->get,
			$this->post,
			$this->session,
			$this->cookies
		);

		# et on les retourne
		return $params;

	}

}

?>
