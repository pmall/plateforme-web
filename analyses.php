<?php

# Affichage du formulaire pour ajouter une analyse
$app->get('/project/:id_project/analysis', function($req, $res, $matches){

	$project = Project::GetWithConditions($matches['id_project']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$analysis = new Analysis();

		return new View('analyses/new.php', array(
			'title' => 'Ajout d\'une nouvelle analyse au projet ' . $project->name . '.',
			'project' => $project,
			'analysis' => $analysis
		));

	}

});

# Insertion d'une nouvelle analyse dans la base de données
$app->post('/project/:id_project/analysis', function($req, $res, $matches){

	$project = Project::GetWithConditions($matches['id_project']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$analysis = new Analysis($req->param('analysis'));

		$analysis->id_project = $project->id;

		if($analysis->save()){

			$res->redirect('index.php');

		}else{

			return new View('analyses/new.php', array(
				'title' => 'Ajout d\'une nouvelle analyse au projet ' . $project->name . '.',
				'project' => $project,
				'analysis' => $analysis
			));

		}

	}

});

# Affichage du formulaire pour modifier une analyse
$app->get('/project/:id_project/analysis/:id_analysis/edit', function($req, $res, $matches){

	$project = Project::GetWithConditions($matches['id_project']);
	$analysis = Analysis::GetWithGroups($matches['id_analysis']);

	if(!$project or!$analysis){

		$res->redirect('index.php');

	}else{

		return new View('analyses/edit.php', array(
			'title' => 'Modification de l\'analyse ' . $analysis->name . '.',
			'project' => $project,
			'analysis' => $analysis
		));

	}

});

# Modification du formulaire dans la base de données
$app->put('/project/:id_project/analysis/:id_analysis/edit', function($req, $res, $matches){

	$project = Project::GetWithConditions($matches['id_project']);
	$analysis = Analysis::GetWithGroups($matches['id_analysis']);

	if(!$project or!$analysis){

		$res->redirect('index.php');

	}else{

		# On garde le nom du projet avant l'assignation
		$name = $analysis->name;

		$analysis->assign($req->param('analysis'));

		if($analysis->save()){

			$res->redirect('index.php');

		}else{

			return new View('analyses/edit.php', array(
				'title' => 'Modification de l\'analyse ' . $name . '.',
				'project' => $project,
				'analysis' => $analysis
			));

		}

	}

});

?>
