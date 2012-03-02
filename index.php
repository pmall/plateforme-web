<?php

require('config.php');

require('framework/App.php');
require('framework/Request.php');
require('framework/Response.php');
require('framework/Route.php');
require('framework/Router.php');
require('framework/Dbh.php');
require('framework/Model.php');
require('framework/Error.php');
require('framework/View.php');

require('models/Dir.php');
require('models/User.php');
require('models/Project.php');

$app = new App('elexir2');

# ==============================================================================
# Accueil
# ==============================================================================

$app->get('/', function(){

	$dirs = Dir::All();
	$users = User::AllWithProjects();

	return new View('accueil.php', array(
		'title' => 'Accueil plateforme',
		'dirs' => $dirs,
		'users' => $users
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
# On run l'appli !!
# ==============================================================================

$app->run();

?>
