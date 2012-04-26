<script src="/plateforme2/public/js/project.js"></script>
<section>
  <h2><?= h($title) ?></h2>
  <p>
    Repertoire du projet : <em><?= $dir ?></em>
  </p>
  <?= displayErrors($project) ?>
  <form action="/plateforme2/index.php/project/<?= h($project->id) ?>" method="post" class="form-horizontal">
    <? $this->partial('projects/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
