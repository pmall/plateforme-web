<section>
  <h1><?php echo $title; ?></h1>
  <form action="/elexir2/index.php/project" method="get">
    <select name="dir">
      <option value=""></option>
      <?php foreach($dirs as $dir): ?>
      <option value="<?php echo urlencode($dir); ?>">
        <?php echo $dir; ?>
      </option>
      <?php endforeach; ?>
    </select>
    <input type="submit" value="Nouveau projet" />
  </form>
  <?php if(count($users) == 0): ?>
  <p>
    Il n'y a pas de projet à afficher.
  </p>
  <?php else: ?>
  <ul>
    <?php foreach($users as $user): ?>
    <li>
      <h2>
        <a name="<?php echo $user->id; ?>" href="/elexir2/index.php/user/<?php echo $user->id; ?>">
          <?php echo $user->login; ?>
        </a>
      </h2>
      <?php $this->partial('projects/_list.php', array(
	    'projects' => $user->projects
      )); ?>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</section>
