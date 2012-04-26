<? $this->partial('jobs/list.php', array('title' => 'Liste des tâches')) ?>
<section>
  <h2>Nouveau projet</h2>
  <form action="/plateforme2/index.php/project" method="get" class="form-inline">
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
      <button type="submit" class="btn btn-success"><i class="icon-plus-sign icon-white"></i> Créer un projet</button>
    </fieldset>
  </form>
</section>
<section>
  <h2>Liste des utilisateurs et de leur projets</h2>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs.
    <a href="/plateforme2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <ul class="nav nav-pills">
    <? foreach($users as $user): ?>
    <li><a href="#<?= h($user->login) ?>"><?= h($user->login) ?></a></li>
    <? endforeach; ?>
  </ul>
  <? $this->partial('projects/_filter.php', array(
	'url' => '/plateforme2/index.php/projects',
	'users' => $user_list))
  ?>
  <ul>
    <? foreach($users as $user): ?>
    <li>
      <h2>
        <a name="<?= h($user->login) ?>" href="/plateforme2/index.php/user/<?= h($user->id) ?>">
          <?= h($user->login) ?>
        </a>
      </h2>
      <? if(count($user->projects) == 0): ?>
      <p>
        Il n'y a pas de projet associé à l'utilisateur <?= h($user->login) ?>.
      </p>
      <? else: ?>
      <? $this->partial('projects/_list.php', array(
	    'projects' => $user->projects
      )); ?>
      <? endif ?>
    </li>
    <? endforeach ?>
  </ul>
  <script src="/plateforme2/public/js/list.js"></script>
  <? endif ?>
</section>
