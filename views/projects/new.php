<?php require 'helpers/form.php' ?>
<section>
  <h1><?php echo $title; ?></h1>
  <?php echo displayErrors($project) ?>
  <?php if(count($users) == 0): ?>
  <p>
    Vous devez créer au moins un utilisateur avant de pouvoir crée un projet.
  </p>
  <p>
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <?php else: ?>
  <?php if(count($celfiles) == 0): ?>
  <p>
    Le répertoire <?php echo $dir ?> ne contient pas de fichier cel valide.
  </p>
  <p>
    <a href="/elexir2/index.php">Retour a l'accueil</a>.
  </p>
  <?php else: ?>
  <form action="" method="post">
    <p>A partir du répertoire <?php echo $dir; ?></p>
    <?php $this->partial('projects/_fields.php') ?>
    <div class="field">
      <label for="valider">Valider :</label>
      <input id="valider" type="submit" value="valider" />
    </div>
    <?php echo field($project, 'dir', 'hidden', array('value' => $dir)) ?>
  </form>
  <?php endif; ?>
  <?php endif; ?>
</section>
