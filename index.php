<?php

function __autoload($classname){

	require 'framework/' . $classname . '.php';

}

# On inclu les fichiers
require('config.php');
require('models/Dir.php');
require('models/User.php');
require('models/Project.php');
require('models/Analysis.php');

# On déclare l'app
$app = new App('elexir2');

# ==============================================================================
# Accueil
# ==============================================================================

$app->get('/', function($req){

	$filter = array(
		'name' => $req->param('name'),
		'type' => $req->param('type'),
		'organism' => $req->param('organism'),
		'cell_line' => $req->param('cell_line')
	);

	$dirs = Dir::All();
	$users = User::AllWithProjects($filter);

	return new View('accueil.php', array(
		'title' => 'Accueil plateforme',
		'notice' => $req->getFlash('notice'),
		'dirs' => $dirs,
		'users' => $users,
		'filter' => $filter
	));

});

# ==============================================================================
# Utilisateurs
# ==============================================================================

include('users.php');

# ==============================================================================
# Projets
# ==============================================================================

include('projects.php');

# ==============================================================================
# Analyses !
# ==============================================================================

include('analyses.php');

# ==============================================================================
# On run l'appli !!
# ==============================================================================

$app->run();

?>
