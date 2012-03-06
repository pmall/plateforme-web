<?php

final class Route{

	private $method;
	private $pattern;
	private $matches;
	private $action;

	public function __construct($method, $pattern, Closure $action){

		$this->method = $method;
		$this->pattern = trim($pattern, '/');
		$this->matches = array();
		$this->action = $action;

	}

	# Match la requete au pattern de la route
	public function match(Request $request, $root = ''){

		# On check déjà si c'est la même méthode
		if($this->method == $request->method){

			# On récupère la bonne partie de l'url
			$path = preg_replace(
				'#^' . $root . '(/index\.php)?(/(.*?)/?(\?.*)?)?$#',
				'$3',
				$request->url
			);

			# On remplace le pattern
			$patterns = array(
				'#\.#',
				'#\*#',
				'#:([a-zA-Z][a-zA-Z0-9_\-]+)#'
			);

			$replacements = array(
				'\.',
				'(.*?)',
				'(?P<$1>[a-zA-Z0-9_\-]+?)'
			);

			# On transforme le pattern en regex
			$pattern = preg_replace(
				$patterns,
				$replacements,
				$this->pattern
			);

			# On tente de matcher le pattern à l'url
			preg_match(
				'#^' . $pattern . '$#',
				$path,
				$matches
			);

			# On associe les matches à la route
			$this->matches = $matches;

			# On retourne les matches
			return count($matches) > 0;

		}else{

			return false;

		}

	}

	# Execute l'action associé à la route
	public function execute(Request $request, Response $response){

		$action = $this->action;

		return $action(
			$request,
			$response,
			$this->matches
		);

	}

}

?>
