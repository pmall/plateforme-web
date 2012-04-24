<ul class="projects">
  <? foreach($projects as $project): ?>
  <li id="project_<?= $project->id ?>" class="project">
    <p>
      <strong><?= $project->id ?></strong> /
      <a href="/plateforme2/index.php/project/<?= $project->id; ?>/edit"><strong><?= $project->name ?></strong></a> /
      <a href="/plateforme2/index.php/projects/?type=<?= $project->type ?>"><?= $project->type ?></a> /
      <a href="/plateforme2/index.php/projects/?organism=<?= $project->organism ?>"><?= $project->organism ?></a> /
      <a href="/plateforme2/index.php/projects/?cell_line=<?= $project->cell_line ?>"><?= $project->cell_line ?></a> /
      <a href="/plateforme2/index.php/project/<?= $project->id ?>/<?= $project->name ?>.pdf">Fichier contrôle qualité</a>
    </p>
    <? if($project->dirty): ?>
    <p id="notice_dirty_<?= $project->id ?>" class="warning">
      Attention : La description des puces du projet a changé depuis le dernier
      préprocessing. Il faut relancer le préprocessing.
    </p>
    <? endif ?>
    <? if($project->comment): ?>
    <p class="comment">
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
        <input type="submit" value="Contrôle Qualité" />
      </form>
      <form action="/plateforme2/index.php/job"
            method="post"
            class="action job"
            data-type="preprocessing"
            data-id_project="<?= $project->id ?>"
            >
        <input type="hidden" name="job[type]" value="preprocessing" />
        <input type="hidden" name="job[id_project]" value="<?= $project->id ?>" />
        <input type="submit" value="Preprocessing" />
      </form>
      <form action="/plateforme2/index.php/project/<?= $project->id ?>/analysis" method="get" class="action">
        <input type="submit" value="Nouvelle analyse" />
      </form>
      <form action="/plateforme2/index.php/project/<?= $project->id ?>"
            method="post"
            class="action delete"
            data-type="project"
            data-id="<?= $project->id ?>"
            data-name="<?= $project->name ?>">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </p>
    <? if(count($project->analyses) > 0): ?>
    <ul class="analyses">
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
          <input type="submit" value="Run" />
        </form>
        <form action="/plateforme2/index.php/project/<?= $project->id ?>/analysis/<?= $analysis->id ?>"
              method="post"
              class="action delete"
              data-type="analysis"
              data-id="<?= $analysis->id ?>"
              data-id_project="<?= $project->id ?>"
              data-name="<?= $analysis->name ?>">
          <input type="hidden" name="_method" value="delete" />
          <input type="submit" value="supprimer" />
        </form>
        <strong><?= $analysis->id ?></strong> /
        <a href="/plateforme2/index.php/project/<?= $project->id ?>/analysis/<?= $analysis->id ?>/edit"><?= $analysis->name ?></a> /
        <?= $analysis->type ?> /
	<a href="/plateforme2/index.php/project/<?= $project->id ?>/anaysis/<?= $analysis->id ?>/<?= $analysis->name ?>.xls">Fichier xls</a>
      </li>
      <? endforeach; ?>
    </ul>
    <? endif; ?>
  </li>
  <? endforeach; ?>
</ul>
