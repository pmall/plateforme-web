<?php

function __autoload($classname){

	require 'framework/' . $classname . '.php';

}

# On inclu la configuration
require('config.php');

# On inclu les modèles
require('models/Dir.php');
require('models/Job.php');
require('models/User.php');
require('models/Project.php');
require('models/Analysis.php');

# On inclu les ptits helpers pour les vues
require('helpers/html.php');
require('helpers/forms.php');
require('helpers/jobs.php');

# On déclare l'app
$app = new App($config['dir_app'], $config);

# ==============================================================================
# Accueil
# ==============================================================================

$app->get('/', function($req) use($app){

	$dirs = Dir::All();
	$users = User::AllWithProjects();

	sort($dirs);

	return $app->getView('accueil.php', array(
		'title' => 'Accueil plateforme',
		'notice' => $req->param('notice'),
		'jobs' => Job::All(10),
		'dirs' => $dirs,
		'users' => $users,
		'user_list' => User::OptionArray(),
		'filter' => array(
			'id_user' => '',
			'name' => '',
			'type' => '',
			'organism' => '',
			'cell_line' => ''
		)
	));

});

# ==============================================================================
# Jobs !!
# ==============================================================================

$app->get('jobs', function($req, $res) use($app){

	$limit = $req->param('limit');

	if(empty($limit)){ $limit = 10; }

	$jobs = Job::All($limit);

	if($req->isAjax()){

		$view = $app->getView('jobs/_list.php', array('jobs' => $jobs));

		return $view->render(false);

	}else{

		return $app->getView('jobs/list.php', array(
			'title' => 'Liste des tâches',
			'jobs' => $jobs
		));

	}

});

$app->post('job', function($req, $res) use($app){

	$job = new Job($req->param('job'));

	if($job->save()){

		if($req->isAjax()){

			echo "ok";

		}else{

			Flash::set(
				'notice',
				'La tâche a bien été ajoutée à la liste.'
			);

			$res->redirect('index.php');

		}

	}else{

		if($req->isAjax()){

			echo "Cette tache est déjà en cours de traitement";

		}else{

			$res->redirect('index.php');

		}

	}

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
