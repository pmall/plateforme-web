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

		$stmt = Dbh::prepare(
			"SELECT j.*, p.name AS name_project, a.name AS name_analysis
			FROM jobs AS j
			LEFT JOIN analyses AS a ON a.id = j.id_analysis,
			projects AS p
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

	public function isProcessing($id_project = '', $id_analysis = '', $type = ''){

		# On crée la chaine where
		$filter = array_filter(array(
			'id_project' => $id_project,
			'id_analysis' => $id_analysis,
			'type' => $type
		));

		$fields = array_map(function($v){
			return $v . ' = ?';
		}, array_keys($filter));

		$fields[] = 'status != \'done\'';

		$where = implode(' AND ', $fields);

		# On vérifie qui il n'y ai pas le même job entrain d'être traité
		# dans la liste de jobs
		$stmt = Dbh::prepare(
			"SELECT id FROM jobs WHERE $where"
		);

		$stmt->execute(array_values($filter));

		return $stmt->rowCount() == 1;

	}

	public function areAnalysesProcessing($id_project){

		$stmt = Dbh::prepare(
			"SELECT COUNT(*) AS num
			FROM jobs
			WHERE id_project = ?
			AND id_analysis IS NOT NULL
			AND status != 'done'"
		);

		$stmt->execute(array($id_project));

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['num'] > 0;

	}

	public function validates(){

		# On détermine si le job est un qc, un préprocessing ou une analyse
		if($this->type == 'qc'){

			# Le job est un qc !

			# Si un qc en cours, erreur
			if(Job::isProcessing($this->id_project, '', 'qc')){

				$this->addError(new Error(
					'Un qc de ce projet est déjà en cours'
				));

			}

		}elseif($this->type == 'preprocessing'){

			# Le job est un préprocessing !

			# Si le préprocessing est en cours erreur
			if(Job::isProcessing($this->id_project, '', 'preprocessing')){

				$this->addError(new Error(
					'Un préprocessing de ce projet est déjà
					en cours'
				));

			# Sinon si des analyses sont en cours, erreur
			}elseif(Job::areAnalysesProcessing($this->id_project)){

				$this->addError(new Error(
					'Une analyse de ce projet est en cours
					de traitement, impossible de lancer le
					preprocessing'
				));

			}

		}elseif($this->type == 'excels'){

				# Le job est une creation d'excels

				# Si l'analyse est en cours
				if(Job::isProcessing($this->id_project, $this->id_analysis, '')){

					$this->addError(new Error(
						'Ce traitement est déjà en cours'
					));

				# Si l'analyse n'est pas faite, erreur 
				}elseif(!Analysis::isPreprocessed($this->id_analysis)){

					$this->addError(new Error(
						'Il faut faire cette analyse pour pouvoir
						recréer les excels'
					));

				}

		}else{

			# Le job est une analyse !

			# Si un préprocess est en cours, erreur
			if(Job::isProcessing($this->id_project, '', 'preprocessing')){

				$this->addError(new Error(
					'Le préprocessing de ce projet est en
					cours, impossible de lancer une analyse'
				));

			# Sinon si le préprocess n'est pas fait, erreur
			}elseif(!Project::isPreprocessed($this->id_project)){

				$this->addError(new Error(
					'Il faut faire le préprocessing de ce
					projet avant de pouvoir lancer une analyse'
				));

			# Sinon si l'analyse est déjà en cours
			}elseif(Job::isProcessing($this->id_project, $this->id_analysis, '')){

				$this->addError(new Error(
					'Cette analyse est déjà en cours de traitement'
				));

			}

		}

	}

	public function beforeInsert(){

		$this->status = 'waiting';

		# Si le job est un preprocessing, il n'est plus dirty
		if($this->type == 'preprocessing'){

			$stmt = Dbh::prepare(
				"UPDATE projects SET dirty = 0 WHERE id = ?"
			);

			$stmt->execute(array($this->id_project));

		}

	}

	public function rawInsert(){

		$stmt = Dbh::prepare(
			"INSERT INTO jobs
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
