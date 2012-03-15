<? require 'helpers/form.php' ?>
<script src="/elexir2/public/js/project.js"></script>
<section>
  <h1><?= $title; ?></h1>
  <?= displayErrors($project) ?>
  <form action="/elexir2/index.php/project/<?= $project->id ?>" method="post">
    <? $this->partial('projects/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
