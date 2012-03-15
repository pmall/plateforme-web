<? $this->partial('jobs/list.php', array('title' => 'Liste des tâches')) ?>
<section>
  <h1>Nouveau projet</h1>
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
</section>
<section>
  <h1>Liste des utilisateurs et de leur projets</h1>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs.
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <ul id="shortlinks">
    <? foreach($users as $user): ?>
    <li><a href="#<?= $user->login ?>"><?= $user->login ?></a></li>
    <? endforeach; ?>
  </ul>
  <? $this->partial('projects/_filter.php', array(
	'url' => '/elexir2/index.php/projects',
	'users' => $user_list))
  ?>
  <ul>
    <? foreach($users as $user): ?>
    <li>
      <h2>
        <a name="<?= $user->login ?>" href="/elexir2/index.php/user/<?= $user->id ?>">
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
  <script src="/elexir2/public/js/list.js"></script>
  <? endif ?>
</section>
