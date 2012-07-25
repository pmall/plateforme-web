<?php

# Affichage de la liste des utilisateurs
$app->get('/users', function($req, $res) use($app){

	$users = User::All();

	return $app->getView('users/list.php', array(
		'title' => 'Liste des utilisateurs',
		'users' => $users
	));

});

# Affichage d'un utilisateur en particulier
$app->get('/user/:id', function($req, $res, $matches) use($app){

	$filter = array(
		'id_user' => $matches['id'],
		'name' => $req->param('name'),
		'type' => $req->param('type'),
		'organism' => $req->param('organism'),
		'cell_line' => $req->param('cell_line')
	);

	$user = User::GetWithProjects($matches['id'], $filter);

	if(!$user){

		$res->redirect('index.php');

	}else{

		return $app->getView('users/show.php', array(
			'title' => 'Liste des projets de l\'utilisateur ' . $user->login,
			'numProjects' => User::CountProjects($user->id),
			'user' => $user,
			'users' => User::OptionArray(),
			'filter' => $filter
		));

	}

});

# Affichage du formulaire pour ajouter un utilisateur
$app->get('/user', function($req, $res) use($app){

	$user = new User();

	return $app->getView('users/new.php', array(
		'title' => 'Ajout d\'un nouvel utilisateur',
		'user' => $user
	));

});

# Ajout d'un utilisateur
$app->post('/user', function($req, $res) use($app){

	$user = new User($req->param('user'));

	if($user->save()){

		Flash::set(
			'notice',
			'L\'utilisateur ' . $user->login . ' a bien été ajouté.'
		);

		$res->redirect('/index.php');

	}else{

		return $app->getView('users/new.php', array(
			'title' => 'Ajout d\'un nouvel utilisateur',
			'user' => $user
		));

	}

});

# Affichage du formulaire pour modifier un utilisateur
$app->get('/user/:id/edit', function($req, $res, $matches) use($app){

	$user = User::Get($matches['id']);

	if(!$user){

		$res->redirect('index.php');

	}else{

		return $app->getView('users/edit.php', array(
			'title' => 'Modification de l\'utilisateur ' . $user->login,
			'login' => $user->login,
			'user' => $user
		));

	}

});

# Modification de l'utilisateur
$app->put('/user/:id', function($req, $res, $matches) use($app){

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

			Flash::set(
				'notice',
				'L\'utilisateur ' . $login . ' a bien été modifié.'
			);

			$res->redirect('index.php');

		}else{

			return $app->getView('users/edit.php', array(
				'title' => 'Modification de l\'utilisateur ' . $login,
				'login' => $login,
				'user' => $user
			));

		}

	}

});

# Suppression de l'utilisateur
$app->delete('/user/:id/', function($req, $res, $matches) use($app){

	$user = User::Get($matches['id']);

	if($user){

		$user->delete();

	}

	if($req->isAjax()){

		echo 'ok';

	}else{

		Flash::set(
			'notice',
			'L\'utilisateur ' . $user->login . ' a bien été supprimé.'
		);

		$res->redirect('index.php');

	}

});

?>
