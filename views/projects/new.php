<section>
  <h2><?= h($title) ?></h2>
  <?= displayErrors($project) ?>
  <? if(count($users) == 0): ?>
  <p>
    Vous devez créer au moins un utilisateur avant de pouvoir crée un projet.
    <a href="/plateforme2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <? if(count($celfiles) == 0): ?>
  <p>
    Le répertoire <?= h($dir) ?> ne contient pas de fichier cel valide.
  </p>
  <p>
    Pour être valide, le nom d'un fichier cel ne doit contenir que des
    lettres, des chiffres, des undersocres, des tirets, des points et des parenthèses,
    pas d'espace et se finir par .CEL.
  </p>
  <p>
    <a href="/plateforme2/index.php">Retour a l'accueil</a>.
  </p>
  <? else: ?>
  <form id="form_project" action="" method="post" class="form-horizontal">
    <? $this->partial('projects/_fields.php') ?>
    <?= field($project, 'dir', 'hidden', array('value' => $dir)) ?>
  </form>
  <? endif; ?>
  <? endif; ?>
</section>
