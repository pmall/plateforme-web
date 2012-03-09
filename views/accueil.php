<section>
  <h1><?= $title ?></h1>
  <h2>Nouveau projet</h2>
  <form action="/elexir2/index.php/project" method="get">
    <fieldset>
      <legend>Ajouter un projet</legend>
      <select name="dir">
        <option value="">Choisir une expérience</option>
        <? foreach($dirs as $dir): ?>
        <option value="<?= urlencode($dir) ?>">
          <?= $dir ?>
        </option>
        <? endforeach ?>
      </select>
      <input type="submit" value="Créer un projet" />
    </fieldset>
  </form>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs.
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <h2>Liste des projets</h2>
  <? $this->partial('projects/_filter.php', array('users' => null)); ?>
  <ul>
    <? foreach($users as $user): ?>
    <li>
      <h2>
        <a name="<?= $user->id ?>" href="/elexir2/index.php/user/<?= $user->id ?>">
          <?= $user->login ?>
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
      <? endif ?>
    </li>
    <? endforeach ?>
  </ul>
  <? endif ?>
</section>
