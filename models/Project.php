<?php

class Project extends Model{

	public $id_user;
	public $dir;
	public $name;
	public $type;
	public $organism;
	public $cell_line;
	public $comment;
	public $public;
	public $date;
	public $conditions;
	public $chips;

	# Retourne un tableau contenant tout les projets
	public static function All(Array $filter = array()){

		$dbh = Dbh::getInstance();

		# On garde seulement les valeurs non vide
		$filter = array_filter($filter, function($v){
			return !empty($v);
		});

		# On récupère les valeurs du filtre
		$filter_values = array_values($filter);

		# On formatte les valeurs pour le where
		$filter = array_map(function($v){
			if($v == 'name'){
				return $v . ' LIKE ?';
			}else{
				return $v . ' = ?';
			}
		}, array_keys($filter));

		# On ajoute 1 dans la chaine where
		array_unshift($filter, 1);

		# On crée la chaine where
		$where = 'WHERE ' . implode(' AND ', $filter);

		# On prépare la requete
		$select_projects_stmt = $dbh->prepare(
			"SELECT * FROM _projects " . $where
		);

		# On execute la requete
		$stmt->execute($filter_values);

		# On récupère la liste des projets
		$projects = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$projects[] = new Project($row, true);

		}

		return $projects;

	}

	# Retourne un projet à partir de son id
	public static function Get($id){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"SELECT * FROM _projects WHERE id = ?"
		);

		$stmt->execute(array($id));

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			return new Project($row, true);

		}else{

			return null;

		}

	}

	# Retourne un projet avec ses conditions
	public static function GetWithConditions($id){

		$project = Project::Get($id);

		if($project){

			$dbh = Dbh::getInstance();

			# On prépare la requete pour selectionner les puces
			$stmt = $dbh->prepare(
				"SELECT id, name
				FROM _conditions
				WHERE id_project = ?"
			);

			# On selectionne les conditions
			$stmt->execute(array($id));

			# On formatte les conditions
			$conditions = array();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				 $conditions[$row['name']]['id'] = $row['id'];
				 $conditions[$row['name']]['name'] = $row['name'];

			}

			$project->conditions = $conditions;

		}

		return $project;

	}

	# Retourne un projet avec ses puces
	public static function GetWithChips($id){

		$project = Project::Get($id);

		if($project){

			$dbh = Dbh::getInstance();

			# On prépare la requete pour selectionner les puces
			$stmt = $dbh->prepare(
				"SELECT ch.name, ch.num, co.name AS `condition`
				FROM _conditions AS co, _chips AS ch
				WHERE co.id = ch.id_condition
				AND co.id_project = ?"
			);

			# On selectionne les puces
			$stmt->execute(array($id));

			# On formatte les puces
			$chips = array();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				$chips[$row['name']]['condition'] = $row['condition'];
				$chips[$row['name']]['name'] = $row['name'];
				$chips[$row['name']]['num'] = $row['num'];

			}

			$project->chips = $chips;

		}

		return $project;

	}

	# Retourne un tableau contenant les projets correspondants a un id_user
	public static function GetByUser($idUser){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"SELECT p.*, u.login AS username
			FROM _projects AS p, _users AS u
			WHERE u.id = p.id_user
			AND id_user = ?"
		);

		$stmt->execute(array($idUser));

		$projects = array();

		while($project = $stmt->fetch(PDO::FETCH_ASSOC)){

			$projects[] = new Project($project, true);

		}

		return $projects;

	}

	# On modifie la fonction assign
	public function assign(Array $values = array()){

		$this->public = 0;

		parent::assign($values);

	}

	# Retourne les celfiles associées au projet
	public function getCelfiles(){

		$celfiles = array();

		if(!empty($this->dir)){

			$dir = new Dir($this->dir);

			$celfiles = $dir->getCelfiles();

		}

		return $celfiles;

	}

	# Retourne le numéro de la puce demandé
	public function getChipNum($chipname){

		if(!empty($this->chips)){

			if(array_key_exists($chipname, $this->chips)){

				return $this->chips[$chipname]['num'];

			}else{

				return '';

			}

		}

	}

	# Retourne le numéro de la puce demandé
	public function getChipCondition($chipname){

		if(!empty($this->chips)){

			if(array_key_exists($chipname, $this->chips)){

				return $this->chips[$chipname]['condition'];

			}else{

				return '';

			}

		}

	}

	# Retourne un identifiant unique
	private function makeUniqid(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare("SELECT id FROM _porjects WHERE id = ?");

		while(1){

			$uniqid = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

			$stmt->execute(array($uniqid));

			if($stmt->rowCount() == 0){ return $uniqid; }

		}

	}

	# Valide le projet avant la sauvegarde
	public function validates(){

		# L'utilisateur ne doit pas être vide
		if(empty($this->id_user)){

			$this->addError(new Error(
				'L\'utilisateur ne doit pas être vide',
				'id_user'
			));

		}

		# Le nom ne doit pas être vide
		if(empty($this->name)){

			$this->addError(new Error(
				'Le nom ne doit pas être vide',
				'name'
			));

		}

		$typeEtOrgaRempli = true;

		# Le type ne doit pas être vide !
		if(empty($this->type)){

			$this->addError(new Error(
				'Le type ne doit pas être vide',
				'type'
			));

			$typeEtOrgaRempli = false;

		}

		# L'organisme ne doit pas être vide
		if(empty($this->organism)){

			$this->addError(new Error(
				'L\'organisme ne doit pas être vide',
				'organism'
			));

			$typeEtOrgaRempli = false;

		}

		# Si on prend les ggh l'organisme est forcément humain
		if($typeEtOrgaRempli and $this->type == 'ggh' and $this->organism != "human"){

			$this->addError(new Error(
				'On ne peut utiliser les puces ggh que sur l\'humain !',
				array('type', 'organism')
			));

		}

		# Lignée ne doit pas être vide
		if(empty($this->cell_line)){

			$this->addError(new Error(
				'Lignée ne doit pas être vide',
				'cell_line'
			));

		}

		# Si il n'y a pas au moins 4 puces de choisi
		$puces_remplies = array_filter($this->chips, function($v){
			return !empty($v['condition']) or !empty($v['num']);
		});

		if(count($puces_remplies) < 4){

			$this->addError(new Error(
				'Vous devez sélectionner au moins 4 puces.'
			));

		}

		# On valide les puces
		foreach($this->chips as $chip){

			# Erreur si il y a une condition et pas de num
			if(!empty($chip['condition']) and empty($chip['num'])){

				$this->addError(new Error(
					'Vous devez donner un numéro a la puce ' . $chip['name'],
					$chip['name']
				));

			}

			# Erreur si il y a un num et pas de condition
			if(!empty($chip['num']) and empty($chip['condition'])){

				$this->addError(new Error(
					'Vous devez donner une condition a la puce ' . $chip['name'],
					$chip['name']
				));

			}

		}

	}

	# Avant l'insertion
	protected function beforeInsert(){

		$this->id = $this->makeUniqid();
		$this->date = date("Y-m-d H:i:s");

	}

	# Insertion dans la base de données
	protected function rawInsert(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"INSERT INTO _projects
			(id, id_user, dir, name, type, organism, cell_line, comment, public, date)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
		);

		$stmt->execute(array(
			$this->id,
			$this->id_user,
			$this->dir,
			$this->name,
			$this->type,
			$this->organism,
			$this->cell_line,
			$this->comment,
			$this->public,
			$this->date
		));

		$this->insertChips();

	}

	# Update dans la base de données
	protected function rawUpdate(){

		$dbh = Dbh::getInstance();

		# On prépare la requete pour mettre a jout le projet
		$update_project_stmt = $dbh->prepare(
			"UPDATE _projects SET
			id_user = ?, name = ?, type = ?, organism = ?,
			cell_line = ?, comment = ?, public = ?
			WHERE id = ?"
		);

		# On met a jour le projet
		$update_project_stmt->execute(array(
			$this->id_user,
			$this->name,
			$this->type,
			$this->organism,
			$this->cell_line,
			$this->comment,
			$this->public,
			$this->id,
		));

		# On prépare la requete pour supprimer les puces du projet
		$delete_chips_stmt = $dbh->prepare(
			"DELETE co, ch FROM _conditions AS co, _chips AS ch
			WHERE co.id = ch.id_condition
			AND co.id_project = ?"
		);

		# On supprime les puces
		$delete_chips_stmt->execute(array($this->id));

		# On ajoute a nouveau les puces (qui ont de nouvelles valeurs)
		$this->insertChips();

	}

	# Supprime le projet de la base de données
	protected function rawDelete(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"DELETE p, co, ch
			FROM _projects AS p, _conditions AS co, _chips AS ch
			WHERE p.id = co.id_project
			AND co.id = ch.id_condition
			AND p.id = ?"
		);

		$stmt->execute(array($this->id));

	}

	# Insert les puces dans la base de données
	private function insertChips(){

		$dbh = Dbh::getInstance();

		$insert_condition_stmt = $dbh->prepare(
			"INSERT INTO _conditions
			(id_project, name)
			VALUES (?, ?)"
		);

		$insert_chip_stmt = $dbh->prepare(
			"INSERT INTO _chips
			(id_condition, name, num)
			VALUES (?, ?, ?)"
		);

		# On récupère les conditions
		$conditions = array();

		# Pour chaque puce
		foreach($this->chips as $chip){

			if(!empty($chip['condition']) and !empty($chip['num'])){

				# Si on a pas déjà ajouté la condition
				if(!array_key_exists($chip['condition'], $conditions)){

					# On ajoute la condition
					$insert_condition_stmt->execute(array(
						$this->id,
						$chip['condition']
					));

					# On garde l'id de la condition
					$conditions[$chip['condition']] = $dbh->lastInsertId();

				}

				# On ajoute la puce
				$insert_chip_stmt->execute(array(
					$conditions[$chip['condition']],
					$chip['name'],
					$chip['num']
				));

			}

		}

	}

}

?>
