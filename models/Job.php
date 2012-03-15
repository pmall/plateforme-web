<?php

class Job extends Model{

	public $id_project;
	public $id_analysis;
	public $name_project;
	public $name_analysis;
	public $type;
	public $status;
	public $start;
	public $end;

	public static function All($limit = 0){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"SELECT j.*, p.name AS name_project, a.name AS name_analysis
			FROM _jobs AS j
			LEFT JOIN _analyses AS a ON a.id = j.id_analysis,
			_projects AS p
			WHERE p.id = j.id_project
			ORDER BY id DESC
			LIMIT 0, :limit"
		);

		$stmt->bindParam(':limit', intval($limit), PDO::PARAM_INT);

		$stmt->execute();

		$jobs = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$jobs[] = new Job($row);

		}

		return $jobs;

	}

	public function validates(){

		$dbh = Dbh::getInstance();

		# On crée la chaine where
		$filter = array_filter(array(
			'id_project' => $this->id_project,
			'id_analysis' => $this->id_analysis,
			'type' => $this->type
		));

		$fields = array_map(function($v){
			return $v . ' = ?';
		}, array_keys($filter));

		$fields[] = 'status != \'done\'';

		$where = implode(' AND ', $fields);

		# On vérifie qui il n'y ai pas le même job entrain d'être traité
		# dans la liste de jobs
		$stmt = $dbh->prepare(
			"SELECT id FROM _jobs
			WHERE $where"
		);

		$stmt->execute(array_values($filter));

		# Si il y a une ligne correspondante, on ajoute une erreur
		if($stmt->rowCount() == 1){

			$this->addError(new Error(
				'Cette tache est déjà en cours de traitement'
			));

		}

	}

	public function beforeInsert(){

		$this->status = 'waiting';

	}

	public function rawInsert(){

		$dbh = Dbh::getInstance();

		$stmt = $dbh->prepare(
			"INSERT INTO _jobs
			(id_project, id_analysis, type, status)
			VALUES(?, ?, ?, ?)"
		);

		$stmt->execute(array(
			$this->id_project,
			$this->id_analysis,
			$this->type,
			$this->status
		));

	}

}

?>
