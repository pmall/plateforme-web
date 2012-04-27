<section>
  <h2><?= h($title) ?></h2>
  <p>
    Repertoire du projet : <em><?= $dir ?></em>
  </p>
  <?= displayErrors($project) ?>
  <form id="form_analysis" action="/plateforme2/index.php/project/<?= h($project->id) ?>" method="post" class="form-horizontal">
    <? $this->partial('projects/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
