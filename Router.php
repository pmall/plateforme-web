<?php

final class Router{

	private $routes;

	# =====================================================================
	# Constructeur
	# =====================================================================

	public function __construct(){

		$this->routes = array();

	}

	# =====================================================================
	# Restful routes
	# =====================================================================

	public function get($pattern, Closure $action){

		$this->routes[] = new Route('get', $pattern, $action);

	}

	public function post($pattern, Closure $action){

		$this->routes[] = new Route('post', $pattern, $action);

	}

	public function put($pattern, Closure $action){

		$this->routes[] = new Route('put', $pattern, $action);

	}

	public function delete($pattern, Closure $action){

		$this->routes[] = new Route('delete', $pattern, $action);

	}

	# =====================================================================
	# Dispatch l'url
	# =====================================================================

	public function dispatch(Request $request, $root = ''){

		# La premiere route qui matche est retournée
		foreach($this->routes as $route){

			if($route->match($request, $root)){

				return $route;

			}

		}

	}

}

?>
