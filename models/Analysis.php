<?php

class Analysis extends Model{

	public $id;
	public $id_project;
	public $name;
	public $type;
	public $groups;

	# Retourne un tableau contenant toutes les analyses
	public static function All(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare("SELECT * FROM _analyses");

		$stmt->execute();

		$analyses = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$analyses[] = new Analysis($row, true);

		}

		return $analyses;

	}

	# Retourne l'analyse qui correspond a l'id
	public static function Get($id){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare("SELECT * FROM _analyses WHERE id = ?");

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

			$dbh = Dbh::getInstance();

			$stmt = $dbh->prepare(
				"SELECT `condition`, letter
				FROM _groups
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

	public function validates(){

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

			foreach(array_keys($letters) as $letter){

				if($letter == 'A' or $letter == 'B'){

					if(count($letters[$letter]) > 1){

						$this->addError(new Error(
							'Pour une analyse de type paire,
							une seule condition doit être
							doit être utilisé pour la lettre ' . $letter 
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

		$dbh = Dbh::getInstance();

		$this->id = $this->makeUniqid($dbh->prepare(
			"SELECT id FROM _analyses WHERE id = ?"
		));

	}

	protected function rawInsert(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"INSERT INTO _analyses
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

		$dbh = Dbh::getInstance();

		$update_analysis_stmt = $dbh->prepare(
			"UPDATE _analyses SET name = ?, type = ? WHERE id = ?"
		);

		$update_analysis_stmt->execute(array(
			$this->name,
			$this->type,
			$this->id
		));

		$delete_groups_stmt = $dbh->prepare(
			"DELETE FROM _groups WHERE id_analysis = ?"
		);

		$delete_groups_stmt->execute(array($this->id));

		$this->insertGroups();

	}

	protected function insertGroups(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"INSERT INTO _groups
			(id_analysis, `condition`, letter)
			VALUES(?, ?, ?)"
		);

		foreach($this->groups as $group){

			$stmt->execute(array(
				$this->id,
				$group['condition'],
				$group['letter']
			));

		}

	}

	protected function rawDelete(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"DELETE a, g
			FROM _analyses AS a
			LEFT JOIN _groups AS g ON a.id = g.id_analysis
			WHERE a.id = ?"
		);

		$stmt->execute(array($this->id));

	}

}

?>
