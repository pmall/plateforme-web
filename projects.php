<?php

# Affichage de la liste des projets
$app->get('/projects', function($req) use($app){

	$filter = array(
		'id_user' => $req->param('id_user'),
		'name' => $req->param('name'),
		'type' => $req->param('type'),
		'organism' => $req->param('organism'),
		'cell_line' => $req->param('cell_line')
	);

	$projects = Project::AllWithAnalyses($filter);

	return $app->getView('projects/list.php', array(
		'title' => 'Liste des projets',
		'jobs' => Job::All(10),
		'numProjects' => Project::count(),
		'filter' => $filter,
		'users' => User::OptionArray(),
		'projects' => $projects
	));

});

# Affichage du formulaire pour ajouter un nouveau projet
$app->get('/project', function($req, $res) use($app){

	$dir = trim($req->param('dir'), '/');

	if(!$dir or !file_exists($app->getConf('celdir') . '/' . $dir)){

		$res->redirect('index.php');

	}else{

		$project = new Project();
		$project->dir = $dir;

		# par défaut le nom du projet est le nom du répertoire
		$project->name = $dir;

		$celfiles = $project->getCelfiles();

		sort($celfiles);

		return $app->getView('projects/new.php', array(
			'title' => 'Ajout d\'un nouveau projet à partir du répertoire ' . $dir,
			'dir' => $dir,
			'project' => $project,
			'users' => User::OptionArray(),
			'cell_lines' => Project::CellLines(),
			'celfiles' => $celfiles
		));

	}

});

# Ajout du formulaire dans la base de données
$app->post('/project', function($req, $res) use($app){

	$dir = trim($req->param('dir'), '/');

	if(!$dir or !file_exists($app->getConf('celdir') . '/' . $dir)){

		$res->redirect('index.php');

	}else{

		$project = new Project($req->param('project'));

		$celfiles = $project->getCelfiles();

		sort($celfiles);

		if($project->save()){

			Flash::set(
				'notice',
				'Le projet ' . $project->name . ' a bien été ajouté.'
			);

			$res->redirect('index.php');

		}else{

			return $app->getView('projects/new.php', array(
				'title' => 'Ajout d\'un nouveau projet',
				'dir' => $dir,
				'project' => $project,
				'users' => User::OptionArray(),
				'cell_lines' => Project::CellLines(),
				'celfiles' => $celfiles
			));

		}

	}

});

# Affichage du formulaire pour modifier un projet
$app->get('/project/:id/edit', function($req, $res, $matches) use($app){

	$project = Project::GetWithChips($matches['id']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$dir = $project->dir;

		if(!file_exists($app->getConf('celdir') . '/' . $dir)){

			$res->redirect('index.php');

		}else{

			$celfiles = $project->getCelfiles();

			sort($celfiles);

			return $app->getView('projects/edit.php', array(
				'title' => 'Modification du projet ' . $project->name,
				'dir' => $dir,
				'project' => $project,
				'users' => User::OptionArray(),
				'cell_lines' => Project::CellLines(),
				'celfiles' => $celfiles
			));

		}

	}

});

# Modification du projet dans la base de données
$app->put('/project/:id', function($req, $res, $matches) use($app){

	$project = Project::Get($matches['id']);

	if(!$project){

		$res->redirect('index.php');

	}else{

		$dir = $project->dir;

		if(!file_exists($app->getConf('celdir') . '/' . $dir)){

			$res->redirect('index.php');

		}else{

			$name = $project->name;

			$project->assign($req->param('project'));

			$celfiles = $project->getCelfiles();

			sort($celfiles);

			if($project->save()){

				Flash::set(
					'notice',
					'Le projet ' . $name . ' a bien été modifié.'
				);

				$res->redirect('index.php');

			}else{

				return $app->getView('projects/edit.php', array(
					'title' => 'Modification du projet ' . $name,
					'dir' => $dir,
					'project' => $project,
					'users' => User::OptionArray(),
					'cell_lines' => Project::CellLines(),
					'celfiles' => $celfiles
				));

			}

		}

	}

});

# Suppression du projet
$app->delete('/project/:id/', function($req, $res, $matches) use($app){

	$project = Project::Get($matches['id']);

	if($project){

		$project->delete();

	}

	if($req->isAjax()){

		echo 'ok';

	}else{

		Flash::set(
			'notice',
			'Le projet ' . $project->name . ' a bien été supprimé.'
		);

		$res->redirect('index.php');

	}

});

# Controle qualité !
$app->get('/project/:id_project/:filename.pdf', function($req, $res, $matches) use($app){

	$file = implode('/', array(
		$app->getConf('qcdir'),
		$matches['id_project'],
		$matches['filename'] . '.pdf'
	));

	if(file_exists($file)){

		$res->setContentType('application/pdf');

		$res->setBody(file_get_contents($file));

	}else{

		$res->addHeader('HTTP/1.1 404 Not Found');

	}

});

?>
