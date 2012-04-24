<?php

class Analysis extends Model{

	public $id;
	public $id_project;
	public $name;
	public $type;
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

		# On valide que le type n'est pas vide
		if(empty($this->type)){

			$this->addError(new Error(
				'Le type ne doit pas être vide',
				'type'
			));

		}

		# On fait un tableau letter => condition
		$letters = array();

		foreach($this->groups as $group){

			if(!empty($group['letter'])){

				$letters[$group['letter']][] = $group['condition'];

			}

		}

		# On valide que la lettre A est présente
		if(!array_key_exists('A', $letters)){

			$this->addError(new Error(
				'Vous devez attribuer la lettre A à une
				condition'
			));

		}

		# On valide que la lettre B est présente
		if(!array_key_exists('B', $letters)){

			$this->addError(new Error(
				'Vous devez attribuer la lettre B à une
				condition'
			));

		}

		# On valide paire
		if($this->type == 'paire'){

			# On vérifie la répartition des conditions
			foreach(array_keys($letters) as $letter){

				if($letter == 'A' or $letter == 'B'){

					if(count($letters[$letter]) > 1){

						$this->addError(new Error(
							'Pour une analyse de type paire,
							une seule condition doit être
							utilisé pour la lettre ' . $letter 
						));

					}

				}else{

					$this->addError(new Error(
						'Pour une analyse de type paire,
						vous ne pouvez pas utiliser la
						lettre ' . $letter 
					));

				}

			}

			# On vérifie que les conditions A et B ont bien le
			# même nombre de puces
			$condition_a = $letters['A'][0];
			$condition_b = $letters['B'][0];

			$nb_a = Analysis::CountChips($this->id_project, $condition_a);
			$nb_b = Analysis::CountChips($this->id_project, $condition_b);

			if($nb_a != $nb_b){

				$this->addError(new Error(
					'A et B doivent avoir le même nombre
					de puces pour une analyse paire. Ici 
					' . $condition_a . ' : ' . $nb_a . '
					et ' . $condition_b . ' : ' . $nb_b
				));

			}

		}

		# On valide impaire
		if($this->type == 'impaire'){

			foreach(array_keys($letters) as $letter){

				if($letter != 'A' and $letter != 'B'){

					$this->addError(new Error(
						'Pour une analyse de type
						impaire, vous ne pouvez pas
						utiliser la lettre ' . $letter 
					));

				}

			}

		}

		# On valide J/O
		if($this->type == 'J/O'){

			# On valide que la lettre C est présente
			if(!array_key_exists('C', $letters)){

				$this->addError(new Error(
					'Vous devez attribuer la lettre C à une
					condition'
				));

			}

			# On valide que la lettre D est présente
			if(!array_key_exists('D', $letters)){

				$this->addError(new Error(
					'Vous devez attribuer la lettre D à une
					condition'
				));

			}

			foreach(array_keys($letters) as $letter){

				if(count($letters[$letter]) > 1){

					$this->addError(new Error(
						'Pour une analyse de type J/O,
						une seule condition doit être
						doit être utilisé pour la lettre ' . $letter 
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
			(id, id_project, name, type)
			VALUES(?, ?, ?, ?)"
		);

		$stmt->execute(array(
			$this->id,
			$this->id_project,
			$this->name,
			$this->type
		));

		$this->insertGroups();

	}

	protected function rawUpdate(){

		$update_analysis_stmt = Dbh::prepare(
			"UPDATE analyses SET name = ?, type = ? WHERE id = ?"
		);

		$update_analysis_stmt->execute(array(
			$this->name,
			$this->type,
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

		$stmt = Dbh::prepare(
			"DELETE a, g
			FROM analyses AS a
			LEFT JOIN groups AS g ON a.id = g.id_analysis
			WHERE a.id = ?"
		);

		$stmt->execute(array($this->id));

	}

}

?>
