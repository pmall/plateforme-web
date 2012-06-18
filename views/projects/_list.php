<ul class="projects">
  <? foreach($projects as $project): ?>
  <li id="project_<?= $project->id ?>">
    <p>
      <div class="options btn-group">
        <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
          Options <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#" class="job" data-type="qc" data-id_project="<?= $project->id ?>">QC</a></li>
          <li><a href="#" class="job" data-type="preprocessing" data-id_project="<?= $project->id ?>">Preprocessing</a></li>
          <li><a href="/plateforme2/index.php/project/<?= $project->id ?>/analysis">Nouvelle analyse</a></li>
          <li><a href="/plateforme2/index.php/project/<?= $project->id; ?>/edit">Modifier</a></li>
          <li class="divider"></li>
          <li><a href="#" class="delete" data-type="project" data-id="<?= $project->id ?>" data-name="<?= $project->name ?>">Supprimer</a></li>
        </ul>
      </div>
      <span class="id"><?= $project->id ?></span> |
      <a href="/plateforme2/index.php/project/<?= $project->id; ?>/edit"><strong><?= $project->name ?></strong></a> |
      <a href="/plateforme2/index.php/projects/?type=<?= $project->type ?>"><?= $project->type ?></a> |
      <a href="/plateforme2/index.php/projects/?organism=<?= $project->organism ?>"><?= $project->organism ?></a> |
      <? if($project->cell_line): ?>
      <a href="/plateforme2/index.php/projects/?cell_line=<?= $project->cell_line ?>"><?= $project->cell_line ?></a> |
      <? endif ?>
      <a href="/plateforme2/index.php/project/<?= $project->id ?>/<?= $project->name ?>.pdf">Fichier contrôle qualité</a>
    </p>
    <? if($project->dirty): ?>
    <p id="notice_dirty_<?= $project->id ?>" class="alert alert-block">
      Attention : La description des puces du projet a changé depuis le dernier
      préprocessing. Il faut relancer le préprocessing.
    </p>
    <? endif ?>
    <? if($project->comment): ?>
    <p class="alert alert-success">
      <?= nl2br(h($project->comment)); ?>
    </p>
    <? endif ?>
    <? if(count($project->analyses) > 0): ?>
    <ul>
      <? foreach($project->analyses as $analysis): ?>
      <li id="analysis_<?= $analysis->id ?>">
        <div class="options btn-group">
          <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
            Options <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#" class="job" data-type="analysis" data-id_project="<?= $analysis->id_project ?>" data-id_analysis="<?= $analysis->id ?>">Run</a></li>
            <li><a href="#" class="job" data-type="excels" data-id_project="<?= $analysis->id_project ?>" data-id_analysis="<?= $analysis->id ?>">Run excels</a></li>
            <li><a href="/plateforme2/index.php/project/<?= $analysis->id_project ?>/analysis/<?= $analysis->id ?>/edit">Modifier</a></li>
            <li class="divider"></li>
            <li><a href="#" class="delete" data-type="analysis" data-id="<?= $analysis->id ?>" data-id_project="<?= $analysis->id_project ?>" data-name="<?= $analysis->name ?>">Supprimer</a></li>
          </ul>
        </div>
        <span class="id"><?= $analysis->id ?></span> |
        <a href="/plateforme2/index.php/project/<?= $project->id ?>/analysis/<?= $analysis->id ?>/edit"><?= $analysis->name ?></a> |
        <?= $analysis->version ?> |
        <?= $analysis->type ?> |
        <? if($analysis->paired): ?>
        paire
        <? else: ?>
        Non paire
        <? endif ?>|
	<a href="/plateforme2/index.php/project/<?= $project->id ?>/anaysis/<?= $analysis->id ?>/<?= $project->name ?>__<?= $analysis->name ?>.zip">Fichier xls</a>
      </li>
      <? endforeach; ?>
    </ul>
    <? endif; ?>
  </li>
  <? endforeach; ?>
</ul>
