<?php

# Affichage de la liste des projets
$app->get('/projects', function($req){

	$filter = array(
		'id_user' => $req->param('id_user'),
		'name' => $req->param('name'),
		'type' => $req->param('type'),
		'organism' => $req->param('organism'),
		'cell_line' => $req->param('cell_line')
	);

	$projects = Project::AllWithAnalyses($filter);

	return new View('projects/list.php', array(
		'title' => 'Liste des projets',
		'users' => User::OptionArray(),
		'projects' => $projects,
		'filter' => $filter
	));

});

# Affichage du formulaire pour ajouter un nouveau projet
$app->get('/project', function($req, $res) use($config){

	$dir = trim($req->param('dir'), '/');

	if(!$dir or !file_exists($config['celdir'] . '/' . $dir)){

		$res->redirect('index.php');

	}else{

		$project = new Project();
		$project->dir = $req->param('dir');

		$celfiles = $project->getCelfiles();

		sort($celfiles);

		return new View('projects/new.php', array(
			'title' => 'Ajout d\'un nouveau projet',
			'dir' => $dir,
			'project' => $project,
			'users' => User::OptionArray(),
			'celfiles' => $celfiles
		));

	}

});

# Ajout du formulaire dans la base de données
$app->post('/project', function($req, $res) use($config){

	$dir = trim($req->param('dir'), '/');

	if(!$dir or !file_exists($config['celdir'] . '/' . $dir)){

		$res->redirect('index.php');

	}else{

		$project = new Project($req->param('project'));

		$celfiles = $project->getCelfiles();

		sort($celfiles);

		if($project->save()){

			$res->redirect('index.php');

		}else{

			return new View('projects/new.php', array(
				'title' => 'Ajout d\'un nouveau projet',
				'dir' => $dir,
				'project' => $project,
				'users' => User::OptionArray(),
				'celfiles' => $celfiles
			));

		}

	}

});

# Affichage du formulaire pour modifier un projet
$app->get('/project/:id/edit', function($req, $res, $matches) use($config){

	$project = Project::GetWithChips($matches['id']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$dir = $project->dir;

		if(!file_exists($config['celdir'] . '/' . $dir)){

			$res->redirect('index.php');

		}else{

			$celfiles = $project->getCelfiles();

			sort($celfiles);

			return new View('projects/edit.php', array(
				'title' => 'Modification du projet ' . $project->name,
				'dir' => $dir,
				'project' => $project,
				'users' => User::OptionArray(),
				'celfiles' => $celfiles
			));

		}

	}

});

# Modification du projet dans la base de données
$app->put('/project/:id', function($req, $res, $matches) use($config){

	$project = Project::Get($matches['id']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$dir = $project->dir;

		if(!file_exists($config['celdir'] . '/' . $dir)){

			$res->redirect('index.php');

		}else{

			$name = $project->name;

			$project->assign($req->param('project'));

			$celfiles = $project->getCelfiles();

			sort($celfiles);

			if($project->save()){

				$res->redirect('index.php');

			}else{

				return new View('projects/edit.php', array(
					'title' => 'Modification du projet ' . $name,
					'dir' => $project->dir,
					'project' => $project,
					'users' => User::OptionArray(),
					'celfiles' => $celfiles
				));

			}

		}

	}

});

# Suppression du projet
$app->delete('/project/:id/', function($req, $res, $matches){

	$project = Project::Get($matches['id']);

	if($project){

		$project->delete();

	}

	$res->redirect('index.php');

});

?>
