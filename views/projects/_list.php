<ul class="projects zebra-striped">
  <? foreach($projects as $project): ?>
  <li id="project_<?= $project->id ?>">
    <p>
      <span class="id"><?= $project->id ?></span> |
      <a href="/plateforme2/index.php/project/<?= $project->id; ?>/edit"><strong><?= $project->name ?></strong></a> |
      <a href="/plateforme2/index.php/projects/?type=<?= $project->type ?>"><?= $project->type ?></a> |
      <a href="/plateforme2/index.php/projects/?organism=<?= $project->organism ?>"><?= $project->organism ?></a> |
      <a href="/plateforme2/index.php/projects/?cell_line=<?= $project->cell_line ?>"><?= $project->cell_line ?></a> |
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
    <p>
      <form action="/plateforme2/index.php/job"
            method="post"
            class="action job"
            data-type="qc"
            data-id_project="<?= $project->id ?>"
            >
        <input type="hidden" name="job[type]" value="qc" />
        <input type="hidden" name="job[id_project]" value="<?= $project->id ?>" />
        <input type="submit" value="Contrôle Qualité" class="btn btn-warning" />
      </form>
      <form action="/plateforme2/index.php/job"
            method="post"
            class="action job"
            data-type="preprocessing"
            data-id_project="<?= $project->id ?>"
            >
        <input type="hidden" name="job[type]" value="preprocessing" />
        <input type="hidden" name="job[id_project]" value="<?= $project->id ?>" />
        <input type="submit" value="Preprocessing" class="btn btn-warning" />
      </form>
      <form action="/plateforme2/index.php/project/<?= $project->id ?>/analysis" method="get" class="action">
        <input type="submit" value="Nouvelle analyse" class="btn btn-warning" />
      </form>
      <form action="/plateforme2/index.php/project/<?= $project->id ?>"
            method="post"
            class="action delete"
            data-type="project"
            data-id="<?= $project->id ?>"
            data-name="<?= $project->name ?>">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" class="btn btn-warning" />
      </form>
    </p>
    <? if(count($project->analyses) > 0): ?>
    <ul>
      <? foreach($project->analyses as $analysis): ?>
      <li id="analysis_<?= $analysis->id ?>">
        <form action="/plateforme2/index.php/job"
              method="post"
              class="action job"
              data-type="<?= $analysis->type ?>"
              data-id_project="<?= $project->id ?>"
              data-id_analysis="<?= $analysis->id ?>">
          <input type="hidden" name="job[type]" value="<?= $analysis->type ?>" />
          <input type="hidden" name="job[id_project]" value="<?= $project->id ?>" />
          <input type="hidden" name="job[id_analysis]" value="<?= $analysis->id ?>" />
          <input type="submit" value="Run" class="btn btn-warning" />
        </form>
        <form action="/plateforme2/index.php/project/<?= $project->id ?>/analysis/<?= $analysis->id ?>"
              method="post"
              class="action delete"
              data-type="analysis"
              data-id="<?= $analysis->id ?>"
              data-id_project="<?= $project->id ?>"
              data-name="<?= $analysis->name ?>">
          <input type="hidden" name="_method" value="delete" />
          <input type="submit" value="supprimer" class="btn btn-warning" />
        </form>
        <span class="id"><?= $analysis->id ?></span> |
        <a href="/plateforme2/index.php/project/<?= $project->id ?>/analysis/<?= $analysis->id ?>/edit"><?= $analysis->name ?></a> |
        <?= $analysis->type ?> |
	<a href="/plateforme2/index.php/project/<?= $project->id ?>/anaysis/<?= $analysis->id ?>/<?= $analysis->name ?>.xls">Fichier xls</a>
      </li>
      <? endforeach; ?>
    </ul>
    <? endif; ?>
  </li>
  <? endforeach; ?>
</ul>
