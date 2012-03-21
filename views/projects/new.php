<script src="/elexir2/public/js/project.js"></script>
<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($project) ?>
  <? if(count($users) == 0): ?>
  <p>
    Vous devez créer au moins un utilisateur avant de pouvoir crée un projet.
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <? if(count($celfiles) == 0): ?>
  <p>
    Le répertoire <?= h($dir) ?> ne contient pas de fichier cel valide.
    <a href="/elexir2/index.php">Retour a l'accueil</a>.
  </p>
  <? else: ?>
  <form action="" method="post">
    <? $this->partial('projects/_fields.php') ?>
    <?= field($project, 'dir', 'hidden', array('value' => $dir)) ?>
  </form>
  <? endif; ?>
  <? endif; ?>
</section>
