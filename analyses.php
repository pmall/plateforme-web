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
		$app->getConf('xlsdir'),
		$matches['id_project'],
		$matches['id_analysis'],
		$matches['filename']
	)) . '.zip';

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

/*

	# Liste des noms de fichiers
	$filename_trans = $matches['filename'] . '_transcription.xls';
	$filename_splicing = $matches['filename'] . '_epissage.xls';
	$filename_splicing_SI = $matches['filename'] . '_epissage_SI.xls';
	$filename_splicing_SIsd = $matches['filename'] . '_epissage_SIsd.xls';
	$filename_splicing_psi = $matches['filename'] . '_epissage_psi.xls';

	# Liste de fichiers
	$file_trans = $dir . '/' . $filename_trans;
	$file_splicing = $dir . '/' . $filename_splicing;
	$file_splicing_SI = $dir . '/' . $filename_splicing_SI;
	$file_splicing_SIsd = $dir . '/' . $filename_splicing_SIsd;
	$file_splicing_psi = $dir . '/' . $filename_splicing_psi;

	# On vérifie que tout les fichiers existent
	$files_ok = true;
	$files_ok = ($files_ok and file_exists($file_trans));
	$files_ok = ($files_ok and file_exists($file_splicing));
	$files_ok = ($files_ok and file_exists($file_splicing_SI));
	$files_ok = ($files_ok and file_exists($file_splicing_SIsd));
	$files_ok = ($files_ok and file_exists($file_splicing_psi));

	# Si ils existent
	if($files_ok){

		# On initialise un fichier zip
		$zipfile = tempnam(sys_get_temp_dir(), 'zip');

		# On crée un fichier zip
		$zip = new ZipArchive();

		# On ouvre le fichier
		$zip_res = $zip->open($zipfile, ZIPARCHIVE::CREATE|ZIPARCHIVE::OVERWRITE);

		# Si le zip est bien ouvert
		if($zip_res === true){

			# On ajoute les fichiers au zip
			$zip->addFile($file_trans, $filename_trans);
			$zip->addFile($file_splicing, $filename_splicing);
			$zip->addFile($file_splicing_SI, $filename_splicing_SI);
			$zip->addFile($file_splicing_SIsd, $filename_splicing_SIsd);
			$zip->addFile($file_splicing_psi, $filename_splicing_psi);

			# On ferme le zip
			$zip->close();

			# On envoit les bon headers
			$res->setContentType('Content-type: application/x-zip');
			$res->addHeader('Content-Disposition: attachment; filename="' . $matches['filename'] . '.zip"');

			# On donne le contenu du zip au body
			$res->setBody(file_get_contents($zipfile));

		}else{

			var_dump($zip_res);

		}

	}else{

		$res->addHeader('HTTP/1.1 404 Not Found');

	}

*/

});

?>
