<?php

# Affichage de la liste des utilisateurs
$app->get('/users', function($req, $res){

	$users = User::All();

	return new View('users/list.php', array(
		'title' => 'Liste des utilisateurs',
		'users' => $users
	));

});

# Affichage du formulaire pour ajouter un utilisateur
$app->get('/user', function($req, $res){

	$user = new User();

	return new View('users/new.php', array(
		'title' => 'Ajout d\'un nouvel utilisateur',
		'user' => $user
	));

});

# Ajout d'un utilisateur
$app->post('/user', function($req, $res){

	$user = new User($req->param('user'));

	if($user->save()){

		$res->redirect('/index.php');

	}else{

		return new View('users/new.php', array(
			'title' => 'Ajout d\'un nouvel utilisateur',
			'user' => $user
		));

	}

});

# Affichage des projets d'un utilisateur
$app->get('/user/:id', function($req, $res, $matches){

	$user = User::GetWithProjects($matches['id']);

	if(!$user){

		$res->redirect('index.php');

	}else{

		return new View('users/show.php', array(
			'title' => 'Liste des projets de l\'utilisateur ' . $user->login,
			'user' => $user 
		));

	}

});

# Affichage du formulaire pour modifier un utilisateur
$app->get('/user/:id/edit', function($req, $res, $matches){

	$user = User::Get($matches['id']);

	if(!$user){

		$res->redirect('index.php');

	}else{

		return new View('users/edit.php', array(
			'title' => 'Modification de l\'utilisateur ' . $user->login,
			'login' => $user->login,
			'user' => $user
		));

	}

});

# Modification de l'utilisateur
$app->put('/user/:id', function($req, $res, $matches){

	$user = User::Get($matches['id']);

	if(!$user){

		$res->redirect('index.php');

	}else{

		# On récupère le login initial (pour pas afficher le modifié
		# en cas d'erreur dans le forulaire)
		$login = $user->login;

		# On assigne les valeurs du formulaire a l'objet
		$user->assign($req->param('user'));

		if($user->save()){

			$res->redirect('index.php');

		}else{

			return new View('users/edit.php', array(
				'title' => 'Modification de l\'utilisateur ' . $user->login,
				'login' => $login,
				'user' => $user
			));

		}

	}

});

# Suppression de l'utilisateur
$app->delete('/user/:id/', function($req, $res, $matches){

	$user = User::Get($matches['id']);

	if($user){

		$user->delete();

	}

	$res->redirect('index.php');

});

?>
