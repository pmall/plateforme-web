<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($analysis); ?>
  <form id="form_project" action="/plateforme2/index.php/project/<?= h($analysis->id_project) ?>/analysis/<?= h($analysis->id) ?>" method="post" class="form-horizontal">
    <? $this->partial('analyses/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
