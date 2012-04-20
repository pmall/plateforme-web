<?php

class View{

	private $viewDir;
	private $layout;
	private $file;
	private $values;

	public function __construct($file, Array $values = array()){

		$this->viewDir = 'views';
		$this->layout = 'layout.php';
		$this->file = $file;
		$this->values = $values;

	}

	public function setViewDir($viewDir){

		$this->viewDir = trim($viewDir, '/');

	}

	public function setLayout($layout){

		$this->layout = trim($layout, '/');

	}

	public function assign(Array $values){

		foreach($values as $key => $value){

			$this->values[$key] = $value;

		}

		return $this;

	}

	private function getFile($fileName){

		return (empty($this->viewDir))
			? $fileName
			: $this->viewDir . '/' . $fileName;

	}

	protected function partial($partial, Array $values = array()){

		$partial = trim($partial, '/');

		# On récupère les fichiers
		$file = $this->getFile($partial);

		if(file_exists($file)){

			# On extract les valeurs de la vue
			extract($this->values);

			# On extract les valeurs passées au partial
			extract($values);

			# On inclu le partial
			include($file);

		}else{

			throw new Exception('Le partial ' . $file . ' n\'existe pas');

		}

	}

	public function render($useLayout = true){

		# On récupère les fichiers
		$file = $this->getFile($this->file);
		$layout = $this->getFile($this->layout);

		# Si le fichier existe
		if(file_exists($file)){

			# On extract les valeurs de la vue
			extract($this->values);

			# On ouvre le buffer
			ob_start();

			# On inclu le fichier
			include($file);

			# On récupère son contenu
			$out = ob_get_clean();

			# Si on doit utiliser un layout
			if($useLayout and !empty($layout)){

				# Si le fichier de layout existe
				if(file_exists($layout)){

					# On ouvre le buffer
					ob_start();

					# On inclu le layout
					include($layout);

					# On récupère son contenu
					$out = ob_get_clean();

				}

			}

			# On affiche le contenu
			return $out;

		}else{

			throw new Exception('Le template ' . $file . ' n\'existe pas');

		}

	}

}

?>
