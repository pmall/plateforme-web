<section>
  <h1><?= $title; ?></h1>
  <? if($numProjects == 0): ?>
  <p>
    Il n'y a aucun projet correspondant à cet utilisateur.
    <a href="/elexir2/index.php">Retour à l'accueil</a>.
  </p>
  <? else: ?>
  <? $this->partial('projects/_filter.php') ?>
  <? if(count($user->projects) == 0): ?>
  <p>
    Aucun projet ne correspond aux critères.
  </p>
  <? else: ?>
  <? $this->partial('projects/_list.php', array(
	'projects' => $user->projects
  )); ?>
  <? endif; ?>
  <? endif; ?>
</section>
