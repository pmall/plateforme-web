<? require 'helpers/form.php' ?>
<section>
  <h1><?= $title; ?></h1>
  <?= displayErrors($project) ?>
  <form action="/elexir2/index.php/project/<?= $project->id ?>" method="post">
    <? $this->partial('projects/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
