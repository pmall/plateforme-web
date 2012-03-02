<?php require 'helpers/form.php' ?>
<section>
  <h1><?php echo $title; ?></h1>
  <?php echo displayErrors($project) ?>
  <form action="/elexir2/index.php/project/<?php echo $project->id ?>" method="post">
    <p>A partir du répertoire <?php echo $dir; ?></p>
    <?php $this->partial('projects/_fields.php') ?>
    <div class="field">
      <label for="valider">Valider :</label>
      <input id="valider" type="submit" value="valider" />
    </div>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
