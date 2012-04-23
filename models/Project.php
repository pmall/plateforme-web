<?php

class Project extends Model{

	public $id;
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
	public $analyses;

	# Retourne un tableau contenant tous les projets
	public static function All(Array $filter = array()){

		# On garde seulement les valeurs non vide
		$filter = array_filter($filter, function($v){
			return !empty($v);
		});

		# On récupère les valeurs du filtre
		$filter_values = array_values($filter);

		# On formatte les valeurs pour le where
		$filter = array_map(function($v){
			if($v == 'name' or $v == 'cell_line'){
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
		$stmt = Dbh::prepare(
			"SELECT * FROM projects " . $where
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

	# Retourne un tableau avec les projets et les analysis correspondantes
	public static function AllWithAnalyses(Array $filter = array()){

		$projects = Project::All($filter);
		$analyses = Analysis::All();

		foreach($projects as $project){

			# On récupère les analyses qui correspondent au projet
			$project->analyses = array_filter($analyses, function($analysis) use($project){
				return $project->id == $analysis->id_project;
			});

		}

		return $projects;

	}

	# Retourne un projet à partir de son id
	public static function Get($id){

		$stmt = Dbh::prepare(
			"SELECT * FROM projects WHERE id = ?"
		);

		$stmt->execute(array($id));

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			return new Project($row, true);

		}else{

			return null;

		}

	}

	# Retourne un projet avec ses puces
	public static function GetWithChips($id){

		$project = Project::Get($id);

		if($project){

			# On prépare la requete pour selectionner les puces
			$stmt = Dbh::prepare(
				"SELECT name, `condition`, num
				FROM chips
				WHERE id_project = ?"
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

	# Retourne un projet avec ses conditions
	public static function GetWithConditions($id){

		$project = Project::Get($id);

		if($project){

			# On prépare la requete pour selectionner les puces
			$stmt = Dbh::prepare(
				"SELECT DISTINCT `condition`
				FROM chips
				WHERE id_project = ?"
			);

			# On selectionne les conditions
			$stmt->execute(array($id));

			# On formatte les conditions
			$conditions = array();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				 $conditions[] = $row['condition'];

			}

			$project->conditions = $conditions;

		}

		asort($project->conditions, SORT_LOCALE_STRING);

		return $project;

	}

	# Retourne le nombre de projets
	public static function Count(){

		$stmt = Dbh::prepare("SELECT COUNT(*) AS num FROM projects");

		$stmt->execute();

		$numProjects = 0;

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$numProjects = $row['num'];

		}

		return $numProjects;

	}

	# On retourne la liste des différentes lignées cellulaires
	public static function CellLines(){

		$stmt = Dbh::prepare("SELECT DISTINCT(cell_line) FROM projects");

		$stmt->execute();

		$cell_lines = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$cell_lines[] = $row['cell_line']; 

		}

		return $cell_lines;

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

	# Retourne la condition de la puce demandé
	public function getChipCondition($chipname){

		if(!empty($this->chips)){

			if(array_key_exists($chipname, $this->chips)){

				return $this->chips[$chipname]['condition'];

			}else{

				return '';

			}

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

		}else{

			# Le nom doit faire moins de 255 caractères
			if(strlen($this->name) > 255){

				$this->addError(new Error(
					'Le nom doit faire 255 caractères au
					maximum',
					'name'
				));

			}

			# Le nom ne doit contenir que des alphanum + _ + - + .
			if(!preg_match('/^[A-Za-z0-9_\-.]+$/', $this->name)){

				$this->addError(new Error(
					'Le nom ne doit contenir que des chiffres, des
					lettres, des underscores, des tirets et des
					points',
					'name'
				));

			}

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

		}else{

			# Lignée doit faire moins de 20 caractères
			if(strlen($this->cell_line) > 20){

				$this->addError(new Error(
					'Lignée doit faire 20 caractères au
					maximum',
					'cell_line'
				));

			}

			# Lignée cellulaire ne doit contenir que des alphanum + _ + - + .
			if(!preg_match('/^[A-Za-z0-9_\-.]+$/', $this->cell_line)){

				$this->addError(new Error(
					'Lignée ne doit contenir que des chiffres, des
					lettres, des underscores, des tirets et des
					points',
					'cell_line'
				));

			}

		}

		# On valide qu'il y a au moins 2 puces de selectionnées
		$puces_remplies = array_filter($this->chips, function($v){
			return !empty($v['condition']) or !empty($v['num']);
		});

		if(count($puces_remplies) < 2){

			$this->addError(new Error(
				'Vous devez sélectionner au moins 2 puces.'
			));

		}

		# On valide les puces
		$conds_length_errors = array();
		$conds_format_errors = array();

		foreach($this->chips as $chip){

			if(!empty($chip['condition'])){

				if(strlen($chip['condition']) > 20){

					$conds_length_errors[] = $chip['name'];

				}

				if(!preg_match('/^[A-Za-z0-9_\-.]+$/', $chip['condition'])){

					$conds_format_errors[] = $chip['name'];

				}

				if(empty($chip['num'])){

					$this->addError(new Error(
						'Vous devez donner un numéro a la puce ' . $chip['name'],
						$chip['name']
					));

				}

			}

			# Erreur si il y a un num et pas de condition
			if(!empty($chip['num'])){

				if(empty($chip['condition'])){

					$this->addError(new Error(
						'Vous devez donner une condition a la puce ' . $chip['name'],
						$chip['name']
					));

				}

			}

		}

		# Si il y a des noms de condition trop longs on ajoute une
		# erreur sur toutes ces puces concernées
		if(count($conds_length_errors) > 0){

			$this->addError(new Error(
				'Le nom d\'une condition doit faire 20
				caractères au maximum',
				$conds_length_errors
			));

		}

		# Si il y a des noms de condition mal formattés on ajoute une
		# erreur sur toutes ces puces concernées
		if(count($conds_format_errors) > 0){

			$this->addError(new Error(
				'Le nom d\'une condition ne doit contenir que des chiffres, des
				lettres, des underscores, des tirets et des
				points',
				$conds_format_errors
			));

		}

		# On valide les numéros de puces

		# On fait une liste condition => chips
		$conditions = array();

		foreach($this->chips as $chip){

			if(!empty($chip['condition']) and !empty($chip['num'])){

				$conditions[$chip['condition']][] = array(
					'name' => $chip['name'],
					'num' => $chip['num']
				);

			}

		}

		# On valide les numéros
		foreach($conditions as $condition){

			# On classe les puces de la condition
			usort($condition, function($a, $b){
				if($a['num'] == $b['num']){
					return strnatcmp($a['name'], $b['name']);
				}else{
					return ($a['num'] < $b['num']) ? -1 : 1;
				}
			});

			# On vérifie que leur num commencent bien a 1 et se suivent
			$i = 1;

			foreach($condition as $chip){

				if($chip['num'] != $i){

					$this->addError(new Error(
						'Le numéro de la puce ' . $chip['name'] . ' est incorect',
						$chip['name']
					));

				}

				$i++;

			}

		}

	}

	# Avant l'insertion
	protected function beforeInsert(){

		$this->id = $this->makeUniqid(Dbh::prepare(
			"SELECT id FROM projects WHERE id = ?"
		));

		$this->date = date("Y-m-d H:i:s");

	}

	# Insertion dans la base de données
	protected function rawInsert(){

		$stmt = Dbh::prepare(
			"INSERT INTO projects
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

		# On prépare la requete pour mettre a jout le projet
		$update_project_stmt = Dbh::prepare(
			"UPDATE projects SET
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
		$delete_chips_stmt = Dbh::prepare(
			"DELETE FROM chips WHERE id_project = ?"
		);

		# On supprime les puces
		$delete_chips_stmt->execute(array($this->id));

		# On ajoute a nouveau les puces (qui ont de nouvelles valeurs)
		$this->insertChips();

	}

	# Supprime le projet de la base de données
	protected function rawDelete(){

		$stmt = Dbh::prepare(
			"DELETE p, c, a, g
			FROM projects AS p
			LEFT JOIN chips AS c ON p.id = c.id_project
			LEFT JOIN analyses AS a ON p.id = a.id_project
			LEFT JOIN groups AS g ON a.id = g.id_analysis
			WHERE p.id = ?"
		);

		$stmt->execute(array($this->id));

	}

	# Insert les puces dans la base de données
	private function insertChips(){

		$insert_chip_stmt = Dbh::prepare(
			"INSERT INTO chips
			(id_project, name, `condition`, num)
			VALUES (?, ?, ?, ?)"
		);

		# Pour chaque puce
		foreach($this->chips as $chip){

			if(!empty($chip['condition']) and !empty($chip['num'])){

				# On ajoute la puce
				$insert_chip_stmt->execute(array(
					$this->id,
					$chip['name'],
					$chip['condition'],
					$chip['num']
				));

			}

		}

	}

}

?>
