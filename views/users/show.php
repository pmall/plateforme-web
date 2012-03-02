<section>
  <h1><?php echo $title; ?></h1>
  <?php if(count($user->projects) == 0): ?>
  <p>Il n'y a pas de projets correspondant a cet utilisateur.</p>
  <?php else: ?>
  <?php $this->partial('projects/_list.php', array(
	'projects' => $user->projects
  )); ?>
  <?php endif; ?>
</section>
