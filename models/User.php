<?php

class User extends Model{

	public $id;
	public $login;
	public $password;
	public $password_confirm;
	public $encrypted_password;
	public $salt;
	public $admin;
	public $projects;

	# Retourne tous les utilisateurs
	public static function All(){

		$stmt = Dbh::prepare(
			"SELECT id, login, salt, admin
			FROM users
			ORDER BY login ASC"
		);

		$stmt->execute();

		$users = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$users[] = new User($row, true);

		}

		return $users;

	}

	# Retourne tous les utilisateurs et leur projets
	public static function AllWithProjects(){

		$users = User::All();
		$projects = Project::AllWithAnalyses();

		foreach($users as $user){

			$user->projects = array_filter($projects, function($project) use($user){
				return $user->id == $project->id_user;
			});

		}

		return $users;

	}

	# Retourne tous les utilisateurs
	public static function Get($id){

		$stmt = Dbh::prepare(
			"SELECT id, login, salt, admin FROM users WHERE id = ?"
		);

		$stmt->execute(array($id));

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			return new User($row, true);

		}else{

			return null;

		}

	}

	# Retourne l'utilisateur idUser avec ses projets
	public static function GetWithProjects($id, Array $filter = array()){

		$stmt = Dbh::prepare("SELECT * FROM users WHERE id = ?");

		$stmt->execute(array($id));

		$filter['id_user'] = $id;

		$projects = Project::AllWithAnalyses($filter);

		$user = null;

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$user = new User($row, true);

			$user->projects = array_filter($projects, function($project) use($user){
				return $user->id == $project->id_user;
			});

		}

		return $user;

	}

	# Retourne le nombre de projets de l'utilisateur
	public static function CountProjects($id){

		$stmt = Dbh::prepare(
			"SELECT COUNT(*) AS num
			FROM projects
			WHERE id_user = ?"
		);

		$stmt->execute(array($id));

		$numProjects = 0;

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$numProjects = $row['num'];

		}

		return $numProjects;

	}

	# Retourne les utilisateurs en tant que liste d'option
	public static function OptionArray(){

		$optionArray = array();

		$users = User::All();

		foreach($users as $user){

			$optionArray[$user->id] = $user->login;

		}

		return $optionArray;

	}

	# On modifie la fonction assign
	public function assign(Array $values = array()){

		$this->admin = 0;

		parent::assign($values);
	}

	# retourne un password crypté a partir du password et d'un grain de sel
	private function crypt_password($password, $salt){

		return md5($password . $salt);

	}

	# Valide le modele avant de le sauvegarder dans la base de données
	protected function validates($context = ''){

		# Si le login est vide
		if(empty($this->login)){

			$this->addError(new Error(
				'Le login ne doit pas être vide',
				'login'
			));

		}else{

			# Le nom ne doit contenir que des alphanum + _ + - + .
			if(!preg_match('/^[A-Za-z0-9_\-]+$/', $this->login)){

				$this->addError(new Error(
					'Le nom ne doit contenir que des chiffres, des
					lettres, des underscores, et des tirets',
					'login'
				));

			}

			# On récupère les utilisateur qui ont ce login
			$stmt = Dbh::prepare(
				"SELECT id FROM users WHERE login = ?"
			);

			$stmt->execute(array($this->login));

			# Si il y en a déjà un
			if($stmt->rowCount() == 1){

				$loginDejaPresent = true;

				# Si on est dans le contexte d'une update
				if($context == 'update'){

					# On récupère les infos de l'utilisateur
					# déjà présent
					$row = $stmt->fetch();

					# Si les ids sont différents
					$loginDejaPresent = ($this->id != $row['id']);
				}

				if($loginDejaPresent){

					$this->addError(new Error(
						'Ce login est déjà présent dans la base de données',
						'login'
					));

				}

			}

		}

		# Si le password est vide
		if(empty($this->password)){

			# Si on est dans le contexte d'une insertion c'est une
			# erreur
			if($context == "insert"){

				$this->addError(new Error(
					'Le mot de passe ne doit pas être vide',
					'password'
				));

			}

		}else{

			# Si le password est différent de sa confirmation
			if($this->password != $this->password_confirm){

				$this->addError(new Error(
					'Le mot de passe et sa confirmation sont différentes',
					array('password', 'password_confirm')
				));

			}

		}

	}

	# Callback avant la sauvegarde
	protected function beforeSave(){

		if(!empty($this->password)){

			# On fait un grain de sel
			$this->salt = uniqid('', true);

			# On encrypte le password
			$this->encrypted_password = $this->crypt_password(
				$this->password,
				$this->salt
			);

		}

		# On undef le password
		$this->password = null;

	}

	# Insertion brute
	protected function rawInsert(){

		$stmt = Dbh::prepare(
			"INSERT INTO users
			(login, password, salt, admin)
			VALUES(?, ?, ?, ?)"
		);

		$stmt->execute(array(
			$this->login,
			$this->encrypted_password,
			$this->salt,
			$this->admin
		));

	}

	# Update brute
	protected function rawUpdate(){

		$stmt = Dbh::prepare(
			"UPDATE users SET login = ?, admin = ?	WHERE id = ?"
		);

		$stmt->execute(array(
			$this->login,
			$this->admin,
			$this->id
		));

		if(!empty($this->encrypted_password)){

			$stmt = Dbh::prepare(
				"UPDATE users SET password = ?, salt = ? WHERE id = ?"
			);

			$stmt->execute(array(
				$this->encrypted_password,
				$this->salt,
				$this->id
			));

		}

	}

	# Delete brut
	protected function rawDelete(){

		# On selectionne les projets de l'utilisateur
		$projects = Project::All(array(
			'id_user' => $this->id
		));

		# On supprime ces projets les uns après les autres
		foreach($projects as $project){

			$project->delete();

		}

		# On supprime l'utilisateur
		$stmt = Dbh::prepare(
			"DELETE FROM users WHERE id = ?"
		);

		$stmt->execute(array($this->id));

	}

}

?>
