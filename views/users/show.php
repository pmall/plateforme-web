<section>
  <h1><?= $title; ?></h1>
  <? if(count($user->projects) == 0): ?>
  <p>Il n'y a pas de projets correspondant a cet utilisateur.</p>
  <? else: ?>
  <? $this->partial('projects/_list.php', array(
	'projects' => $user->projects
  )); ?>
  <? endif; ?>
</section>
