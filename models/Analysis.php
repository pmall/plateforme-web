<?php

class Analysis extends Model{

	public $id;
	public $id_project;
	public $name;
	public $version;
	public $type;
	public $paired;
	public $groups;

	# Retourne un tableau contenant toutes les analyses
	public static function All(){

		$stmt = Dbh::prepare("SELECT * FROM analyses");

		$stmt->execute();

		$analyses = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$analyses[] = new Analysis($row, true);

		}

		return $analyses;

	}

	# Retourne l'analyse qui correspond a l'id
	public static function Get($id){

		$stmt = Dbh::prepare("SELECT * FROM analyses WHERE id = ?");

		$stmt->execute(array($id));

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			return new Analysis($row, true);

		}else{

			return null;

		}

	}

	# Retourne l'analyse avec les groupes
	public static function GetWithGroups($id){

		$analysis = Analysis::Get($id);

		if($analysis){

			$stmt = Dbh::prepare(
				"SELECT `condition`, letter
				FROM groups
				WHERE id_analysis = ?"
			);

			$stmt->execute(array($id));

			$groups = array();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				$groups[$row['condition']]['condition'] = $row['condition'];
				$groups[$row['condition']]['letter'] = $row['letter'];

			}

			$analysis->groups = $groups;

		}

		return $analysis;

	}

	public static function CountChips($id_project, $condition){

		$stmt = Dbh::prepare(
			"SELECT COUNT(*) FROM chips
			WHERE id_project = ? AND `condition` = ?"
		);

		$stmt->execute(array($id_project, $condition));

		$row = $stmt->fetch();

		return $row[0];

	}

	# Retourne vrai si le projet est préprocessé
	public static function isPreprocessed($id_analysis){

		$stmt = Dbh::prepare("SELECT type FROM analyses WHERE id = ?");
		$stmt->execute(array($id_analysis));
		$row = $stmt->fetch();

		$tables = array();

		if($row['type'] == 'simple' or $row['type'] == 'compose'){

			$tables[] = '%__' . $id_analysis . '_transcription';
			$tables[] = '%__' . $id_analysis . '_splicing';

		}

		if($row['type'] == 'apriori'){

			$tables[] = '%__' . $id_analysis . '_ase_apriori';

		}

		if($row['type'] == 'jonction'){

			$tables[] = '%__' . $id_analysis . '_jonction';

		}

		# On vérifie que les tables sont là
		$ok = true;

		$stmt = Dbh::prepare('SHOW TABLES LIKE ?');

		foreach($tables as $table){

			$stmt->execute(array($table));
			$nb_table = $stmt->rowCount();

			if($nb_table != 1){

				$ok = false;
				break;

			}

		}

		return $ok;

	}

	# On modifie la fonction assign
	public function assign(Array $values = array()){

		$this->paired = 0;

		parent::assign($values);
	}

	public function validates($context){

		# On valide si l'analyse est déjà en train d'être traitée ou non
		$is_processing = ($context == 'update')
			? Job::isProcessing($this->id_project, $this->id, $this->type)
			: false;

		if($is_processing){

			$this->addError(new Error(
				'Cette analyse est en train d\'être traitée,
				impossible de la modifier'
			));

		}

		# On valide que le nom n'est pas vide
		if(empty($this->name)){

			$this->addError(new Error(
				'Le nom ne doit pas être vide',
				'name'
			));

		}else{

			# Le nom doit faire 255 caractères au maximum
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

		# On valide que la version de fasterdb n'est pas vide
		if(empty($this->version)){

			$this->addError(new Error(
				'La version de fasterdb ne doit pas être vide',
				'version'
			));

		}

		# On valide que le type n'est pas vide
		if(empty($this->type)){

			$this->addError(new Error(
				'Le type ne doit pas être vide',
				'type'
			));

		}else{

			# On valide les analyses a priori
			if($this->type == 'apriori'){

				if($this->version == 'fdb2'){

					$this->addError(new Error(
						'Une analyse de type a priori
						ne peut être faite que sur
						fasterdb1',
						'type'
					));

				}

				# On va chercher le type de puces de l'exp
				$project = Project::get($this->id_project);

				if($project->organism == 'mouse'){

					$this->addError(new Error(
						'Une analyse de type a priori
						ne peut être faite que sur
						l\'humain',
						'type'
					));

				}

			}

			# On valide les analyses jonction
			if($this->type == 'jonction'){

				# On va chercher le type de puces de l'exp
				$project = Project::get($this->id_project);

				if($project->type == 'exon'){

					$this->addError(new Error(
						'Une analyse de type jonction ne
						peut etre faite que pour des
						puces GGH',
						'type'
					));

				}

			}

		}

		# On fait un tableau letter => condition
		$letters = array();

		foreach($this->groups as $group){

			if(!empty($group['letter'])){

				$letters[$group['letter']][] = $group['condition'];

			}

		}

		# On valide les lettres
		$a_ok = true;
		$b_ok = true;
		$c_ok = true;
		$d_ok = true;

		# On valide simple
		$types_simples = array('simple', 'apriori', 'jonction');

		if(in_array($this->type, $types_simples)){

			if(!array_key_exists('A', $letters)){

				$a_ok = false;

				$this->addError(new Error(
					'Vous devez attribuer la lettre A à une
					condition'
				));

			}

			if(!array_key_exists('B', $letters)){

				$b_ok = false;

				$this->addError(new Error(
					'Vous devez attribuer la lettre B à une
					condition'
				));

			}

			if(array_key_exists('C', $letters)){

				$this->addError(new Error(
					'Pour une analyse simple, vous ne pouvez
					pas utiliser la lettre C' 
				));

			}

			if(array_key_exists('D', $letters)){

				$this->addError(new Error(
					'Pour une analyse simple, vous ne pouvez
					pas utiliser la lettre D' 
				));

			}

		}

		# On valide composé
		if($this->type == 'compose'){

			if(!array_key_exists('A', $letters)){

				$a_ok = false;

				$this->addError(new Error(
					'Vous devez attribuer la lettre A à une
					condition'
				));

			}

			if(!array_key_exists('B', $letters)){

				$b_ok = false;

				$this->addError(new Error(
					'Vous devez attribuer la lettre B à une
					condition'
				));

			}


			if(!array_key_exists('C', $letters)){

				$c_ok = false;

				$this->addError(new Error(
					'Vous devez attribuer la lettre C à une
					condition'
				));

			}

			if(!array_key_exists('D', $letters)){

				$d_ok = false;

				$this->addError(new Error(
					'Vous devez attribuer la lettre D à une
					condition'
				));

			}

		}

		# On valide paired
		if($this->paired){

			#$a_tester = ($this->type == 'simple' and $a_ok and $b_ok);
			#$a_tester = ($a_tester or ($this->type == 'compose' and $a_ok and $b_ok and $c_ok and $d_ok));
			$a_tester = true;

			# On teste que chaque lettre est associée a une seule
			# condition
			foreach($letters as $letter => $conditions){

				if(count($conditions) > 1){

					$a_tester = false;

					$this->addError(new Error(
						'Pour une analyse paire la
						lettre ' . $letter . ' doit être
						associée à une seule condition'
					));

				}

			}

			if($a_tester){

				# On teste que chaque lettre contient le même
				# nombre de puces
				$num_chips = array();

				foreach($letters as $letter => $conditions){

					$num_chips[] = Analysis::CountChips(
						$this->id_project,
						$conditions[0]
					);

				}

				if(count(array_unique($num_chips)) > 1){

					$this->addError(new Error(
						'Pour une analyse paire toutes
						les lettres doivent correspondre
						au même nombre de puces'
					));

				}

			}

		}

	}

	protected function beforeInsert(){

		$this->id = $this->makeUniqid(Dbh::prepare(
			"SELECT id FROM analyses WHERE id = ?"
		));

	}

	protected function rawInsert(){

		$stmt = Dbh::prepare(
			"INSERT INTO analyses
			(id, id_project, name, version, type, paired)
			VALUES(?, ?, ?, ?, ?, ?)"
		);

		$stmt->execute(array(
			$this->id,
			$this->id_project,
			$this->name,
			$this->version,
			$this->type,
			$this->paired
		));

		$this->insertGroups();

	}

	protected function rawUpdate(){

		$update_analysis_stmt = Dbh::prepare(
			"UPDATE analyses SET name = ?, version = ?, type = ?, paired = ? WHERE id = ?"
		);

		$update_analysis_stmt->execute(array(
			$this->name,
			$this->version,
			$this->type,
			$this->paired,
			$this->id
		));

		$delete_groups_stmt = Dbh::prepare(
			"DELETE FROM groups WHERE id_analysis = ?"
		);

		$delete_groups_stmt->execute(array($this->id));

		$this->insertGroups();

	}

	protected function insertGroups(){

		$stmt = Dbh::prepare(
			"INSERT INTO groups
			(id_analysis, `condition`, letter)
			VALUES(?, ?, ?)"
		);

		foreach($this->groups as $group){

			if(!empty($group['letter'])){

				$stmt->execute(array(
					$this->id,
					$group['condition'],
					$group['letter']
				));

			}

		}

	}

	protected function rawDelete(){

		# On supprime les tables

		# On vérifie que l'id est bien formatté on sais jamais (sinon
		# tout va foutre le camp)
		if(preg_match('/[a-zA-Z0-9]{6}/', $this->id)){

			$tables = array();

			$stmt = Dbh::prepare("SHOW TABLES LIKE ?");

			$stmt->execute(array(
				'%__' . $this->id . '%'
			));

			while($row = $stmt->fetch()){

				$tables[] = $row[0];

			}

			if(count($tables) > 0){

				Dbh::exec("DROP TABLE IF EXISTS " . implode(',', $tables));

			}

		}

		# On supprime les lignes correspondantes dans les tables
		$stmt = Dbh::prepare(
			"DELETE a, j, g
			FROM analyses AS a
			LEFT JOIN jobs AS j ON a.id = j.id_analysis
			LEFT JOIN groups AS g ON a.id = g.id_analysis
			WHERE a.id = ?"
		);

		$stmt->execute(array($this->id));

	}

}

?>
