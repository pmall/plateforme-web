<?php

class Model{

	private $_new;
	private $_initialValues;
	private $_errors;

	# Constructeur par défaut
	public function __construct(Array $values = array(), $notNew = false){

		$this->_new = !$notNew;
		$this->_initialValues = $values;
		$this->_errors = array();

		$this->initialize();

		$this->assign($values);

	}

	# Assigne un tableau de valeur aux propriétés
	# de l'objet
	public function assign(Array $values){

		foreach($values as $key => $value){

			$this->$key = $value;

		}

	}

	# Si le modele est nouveau
	public function isNew(){

		return $this->_new;

	}

	# Si le projet a été modifié par rapport à son état initial
	public function isDirty(){

		# Toujours true en attendant de faire mieux
		return true;

	}

	# Ajouter une erreur
	protected function addError(Error $error){

		$this->_errors[] = $error;

	}

	# Ajouter plusieurs erreurs
	protected function addErrors(Array $errors){

		foreach($errors as $error){

			$this->addError($error);

		}

	}

	# Retourne vrai si le field passé en paramètre est dans les erreurs
	public function hasError($field){

		foreach($this->_errors as $error){

			if($error->hasField($field)){ return true; }

		}

	}

	# Retourne toutes les erreurs
	public function getErrors(){

		return $this->_errors;

	}

	# Retourne true si le modele n'a pas d'erreurs
	public function isValid(){

		return count($this->_errors) == 0;

	}

	# Retourne un chiffre ou une lettre aléatoirement
	private function rand(){

		$char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return $char[rand(0, 35)];

	}

	# Retourne un identifiant unique
	protected function makeUniqid($stmt){

		while(1){

			$uniqid = implode('', array(
				$this->rand(),
				$this->rand(),
				$this->rand(),
				$this->rand(),
				$this->rand(),
				$this->rand()
			));

			$stmt->execute(array($uniqid));

			if($stmt->rowCount() == 0){ return $uniqid; }

		}

	}

	# Sauvegarde du modele dans la bdd
	public function save(){

		# On défini le contexte de la sauvegarde
		$context = ($this->isNew())
			? 'insert'
			: 'update';

		# On valide le modèle dans le contexte approprié
		$this->validates($context);

		# Si l'objet est valide
		if($this->isValid()){

			$this->beforeSave();

			# Si l'objet est nouveau
			if($this->isNew()){

				# On l'insert
				return $this->insert();

			}else{

				# On l'update
				return $this->update();

			}

			$this->afterSave();

		}else{

			return false;

		}

	}

	# Insertion du modele dans la bdd
	public function insert(){

		if($this->isValid()){

			$this->beforeInsert();
			$this->rawInsert();
			$this->afterInsert();

			return true;

		}else{

			return false;

		}

	}

	# Mise a jour du modele dans la bdd
	public function update(){

		if($this->isValid()){

			# Si le modèle a bien été modifié par rapport à son
			# état initial
			if($this->isDirty()){

				$this->beforeUpdate();
				$this->rawUpdate();
				$this->afterUpdate();

			}

			return true;

		}else{

			return false;

		}

	}

	# Suppression du modele dans la bdd
	public function delete(){

		$this->beforeDelete();
		$this->rawDelete();
		$this->afterDelete();

		return true;

	}

	# Validation
	protected function validates(){ return true; }

	# Callbacks
	protected function initialize(){}
	protected function beforeSave(){}
	protected function afterSave(){}
	protected function beforeInsert(){}
	protected function afterInsert(){}
	protected function beforeUpdate(){}
	protected function afterUpdate(){}
	protected function beforeDelete(){}
	protected function afterDelete(){}

	# Fonction de manipulation brutes de la bdd
	protected function rawInsert(){}
	protected function rawUpdate(){}
	protected function rawDelete(){}

}

?>
