<?php

# Affichage du formulaire pour ajouter une analyse
$app->get('/project/:id_project/analysis', function($req, $res, $matches) use($app){

	$project = Project::GetWithConditions($matches['id_project']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$analysis = new Analysis();

		return $app->getView('analyses/new.php', array(
			'title' => 'Ajout d\'une nouvelle analyse au projet ' . $project->name . '.',
			'project' => $project,
			'analysis' => $analysis
		));

	}

});

# Insertion d'une nouvelle analyse dans la base de données
$app->post('/project/:id_project/analysis', function($req, $res, $matches) use($app){

	$project = Project::GetWithConditions($matches['id_project']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$analysis = new Analysis($req->param('analysis'));

		$analysis->id_project = $project->id;

		if($analysis->save()){

			Flash::set(
				'notice',
				'L\'analyse ' . $analysis->name . ' a bien été ajoutée.'
			);

			$res->redirect('index.php');

		}else{

			return $app->getView('analyses/new.php', array(
				'title' => 'Ajout d\'une nouvelle analyse au projet ' . $project->name . '.',
				'project' => $project,
				'analysis' => $analysis
			));

		}

	}

});

# Affichage du formulaire pour modifier une analyse
$app->get('/project/:id_project/analysis/:id_analysis/edit', function($req, $res, $matches) use($app){

	$project = Project::GetWithConditions($matches['id_project']);
	$analysis = Analysis::GetWithGroups($matches['id_analysis']);

	if(!$project or!$analysis){

		$res->redirect('index.php');

	}else{

		return $app->getView('analyses/edit.php', array(
			'title' => 'Modification de l\'analyse ' . $analysis->name . '.',
			'project' => $project,
			'analysis' => $analysis
		));

	}

});

# Modification du formulaire dans la base de données
$app->put('/project/:id_project/analysis/:id_analysis/edit', function($req, $res, $matches) use($app){

	$project = Project::GetWithConditions($matches['id_project']);
	$analysis = Analysis::GetWithGroups($matches['id_analysis']);

	if(!$project or!$analysis){

		$res->redirect('index.php');

	}else{

		# On garde le nom du projet avant l'assignation
		$name = $analysis->name;

		$analysis->assign($req->param('analysis'));

		if($analysis->save()){

			Flash::set(
				'notice',
				'L\'analyse ' . $name . ' a bien été modifiée.'
			);

			$res->redirect('index.php');

		}else{

			return $app->getView('analyses/edit.php', array(
				'title' => 'Modification de l\'analyse ' . $name . '.',
				'project' => $project,
				'analysis' => $analysis
			));

		}

	}

});

# Suppression de l'analyse
$app->delete('/project/:id_project/analysis/:id_analysis', function($req, $res, $matches) use($app){

	$analysis = Analysis::Get($matches['id_analysis']);

	if($analysis){

		$analysis->delete();

	}

	if($req->isAjax()){

		echo 'ok';

	}else{

		Flash::set(
			'notice',
			'L\'analyse ' . $analysis->name . ' a bien été supprimée.'
		);

		$res->redirect('index.php');

	}

});

# fichier xls !
$app->get('/project/:id_project/anaysis/:id_analysis/:filename.zip', function($req, $res, $matches) use($app){

	# Répertoire contenant les fichiers excel
	$file = implode('/', array(
		$app->getConf('dir_xls'),
		$matches['id_project'],
		$matches['id_analysis'],
		'files.zip'
	));

	# Si le fichier existe
	if(file_exists($file)){

		# On envoit les bon headers
		$res->setContentType('Content-type: application/x-zip');
		$res->addHeader('Content-Disposition: attachment; filename="' . $matches['filename'] . '.zip"');

		# On donne le contenu du zip au body
		$res->setBody(file_get_contents($file));

	}else{

		# Sinon on envoitune erreur 404
		$res->addHeader('HTTP/1.1 404 Not Found');

	}

});

?>
