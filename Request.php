<?php

final class Request{

	private $params;
	public $url;
	public $method;

	# Constructeur
	public function __construct(){

		# Initialisation des parametres
		$get = $_GET;
		$post = $_POST;
		$flash = Flash::all();
		$session = $_SESSION;
		$cookies = $_COOKIE;

		# On merge tout les params dans l'ordre de préférence
		$this->params = array_merge(
			$get,
			$post,
			$flash,
			$session,
			$cookies
		);

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

	# Retourne le param demandé
	public function param($param){

		# Si il est présent on retourne va valeur du parametre
		if(in_array($param, array_keys($this->params))){

			return $this->params[$param];

		}else{

			return null;

		}

	}

	# On retourne tous les parametres
	public function params(){

		# et on les retourne
		return $this->params;

	}

	# Fonction qui retourne vrai ou faux selon que la requete est ajax ou pas
	public function isAjax(){

		return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])
			== 'xmlhttprequest';

	}

}

?>
