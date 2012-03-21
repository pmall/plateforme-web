<? $this->partial('jobs/list.php', array('title' => 'Liste des tâches')); ?>
<section>
  <h1><?= h($title) ?></h1>
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
  <script src="/elexir2/public/js/list.js"></script>
  <? endif; ?>
  <? endif; ?>
</section>
