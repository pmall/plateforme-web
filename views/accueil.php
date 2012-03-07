<section>
  <h1><?= $title; ?></h1>
  <form action="/elexir2/index.php/project" method="get">
    <select name="dir">
      <option value=""></option>
      <? foreach($dirs as $dir): ?>
      <option value="<?= urlencode($dir); ?>">
        <? echo $dir; ?>
      </option>
      <? endforeach; ?>
    </select>
    <input type="submit" value="Nouveau projet" />
  </form>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs.
  </p>
  <p>
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <ul>
    <? foreach($users as $user): ?>
    <li>
      <h2>
        <a name="<?= $user->id; ?>" href="/elexir2/index.php/user/<?= $user->id; ?>">
          <?= $user->login; ?>
        </a>
      </h2>
      <? if(count($user->projects) == 0): ?>
      <p>
        Il n'y a pas de projet associé à l'utilisateur <?= $user->login ?>.
      </p>
      <? else: ?>
      <? $this->partial('projects/_list.php', array(
	    'projects' => $user->projects
      )); ?>
      <? endif; ?>
    </li>
    <? endforeach; ?>
  </ul>
  <? endif; ?>
</section>
