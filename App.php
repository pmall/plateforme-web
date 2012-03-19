<?php

class App{

	private $root;
	private $request;
	private $response;
	private $router;

	# =====================================================================
	# Constructeur
	# =====================================================================

	public function __construct($root = ''){

		session_start();

		$this->root = trim($root, '/');
		$this->request = new Request();
		$this->response = new Response($this->root);
		$this->router = new Router();

	}

	# =====================================================================
	# Restful routes
	# =====================================================================

	public function get($pattern, Closure $action){

		$this->router->get($pattern, $action);

	}

	public function post($pattern, Closure $action){

		$this->router->post($pattern, $action);

	}

	public function put($pattern, Closure $action){

		$this->router->put($pattern, $action);

	}

	public function delete($pattern, Closure $action){

		$this->router->delete($pattern, $action);

	}

	# =====================================================================
	# RUN !
	# =====================================================================

	public function run(){

		try{

			# On récupère la route correspondant a la requete
			$route = $this->router->dispatch(
				$this->request,
				$this->root
			);

			# Si il n'y a pas de route on lance une exception
			if(!$route){

				throw new Exception('Pas de route trouvée pour cette adresse.');

			}

			# On execute la route et on récupère la valeur retournée
			$retour = $route->execute(
				$this->request,
				$this->response
			);

			# Si la valeur de retour n'est pas vide
			if($retour){

				# Si c'est une vue qui a été retournée, on la
				# rend
				if($retour instanceOf View){

					$retour = $retour->render();

				}

				# On attribue la valeur de retour au corps de
				# la réponse
				$this->response->setBody($retour);

			}

			# Si la réponse n'a pas été envoyée, on l'envoit
			if(!$this->response->isSent()){

				$this->response->send();

			}

		}catch(Exception $e){

			echo $e->getMessage();

		}

	}

}

?>
