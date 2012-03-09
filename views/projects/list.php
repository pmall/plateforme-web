<section>
  <h1><?php echo $title ?></h1>
  <? if($numProjects == 0): ?>
  <p>
    Il n'y a aucun projet a afficher. <a href="/elexir2/index.php">Retour à l'accueil</a>.
  </p>
  <? else: ?>
  <? $this->partial('projects/_filter.php'); ?>
  <? if(count($projects) == 0): ?>
  <p>
    Aucun projet ne correspond aux critères.
  </p>
  <? else: ?>
  <? $this->partial('projects/_list.php'); ?>
  <? endif; ?>
  <? endif; ?>
</section>
