<?php

class Analysis extends Model{

	public $id;
	public $id_project;
	public $name;
	public $type;
	public $groups;

	# Retourne l'analyse qui correspond a l'id
	public static function Get($id){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"SELECT * FROM _analyses WHERE id = ?"
		);

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
				"SELECT c.name, g.*
				FROM _conditions AS c, _groups AS g
				WHERE c.id = g.id_condition
				AND id_analysis = ?"
			);

			$stmt->execute(array($id));

			$groups = array();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				$groups[$row['name']]['id_condition'] = $row['id_condition'];
				$groups[$row['name']]['letter'] = $row['letter'];

			}

			$analysis->groups = $groups;

		}

		return $analysis;

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

	public function validates(){

		if(empty($this->name)){

			$this->addError(new Error(
				'Le nom ne doit pas être vide',
				'name'
			));

		}

		if(empty($this->type)){

			$this->addError(new Error(
				'Le type ne doit pas être vide',
				'type'
			));

		}

	}

	protected function beforeInsert(){

		$this->id = $this->makeUniqid();

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
			(id_analysis, id_condition, letter)
			VALUES(?, ?, ?)"
		);

		foreach($this->groups as $group){

			$stmt->execute(array(
				$this->id,
				$group['id_condition'],
				$group['letter']
			));

		}

	}

}

?>
