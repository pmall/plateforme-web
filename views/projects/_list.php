<ul class="projects">
  <? foreach($projects as $project): ?>
  <li class="project">
    <p>
      <strong><?= $project->id ?></strong> /
      <a href="/elexir2/index.php/project/<?= $project->id; ?>/edit"><?= $project->name ?></a> /
      <a href="/elexir2/index.php/projects/?type=<?= $project->type ?>"><?= $project->type ?></a> /
      <a href="/elexir2/index.php/projects/?organism=<?= $project->organism ?>"><?= $project->organism ?></a> /
      <a href="/elexir2/index.php/projects/?cell_line=<?= $project->cell_line ?>"><?= $project->cell_line ?></a>
    </p>
    <? if($project->comment): ?>
    <p class="comment">
      <?= $project->comment; ?>
    </p>
    <? endif ?>
    <p>
      <form action="" method="get" class="action">
        <input type="submit" value="Contrôle Qualité" />
      </form>
      <form action="" method="get" class="action">
        <input type="submit" value="preprocessing" />
      </form>
      <form action="/elexir2/index.php/project/<?= $project->id ?>/analysis" method="get" class="action">
        <input type="submit" value="Nouvelle analyse" />
      </form>
      <form action="/elexir2/index.php/project/<?= $project->id ?>" method="post" class="action">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </p>
    <? if(count($project->analyses) > 0): ?>
    <ul class="analyses">
      <? foreach($project->analyses as $analysis): ?>
      <li>
        [<a href="">run</a>]
        [<a href="">delete</a>]
        <a href="/elexir2/index.php/project/<?= $project->id ?>/analysis/<?= $analysis->id ?>/edit"><?= $analysis->name ?></a>
      </li>
      <? endforeach; ?>
    </ul>
    <? endif; ?>
  </li>
  <? endforeach; ?>
</ul>

