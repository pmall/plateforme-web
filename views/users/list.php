<section>
  <h1><?= $title; ?></h1>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs.
  </p>
  <p>
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <ul>
    <li>
      <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>
    </li>
    <? foreach($users as $user): ?>
    <li>
      <a href="/elexir2/index.php/user/<?= $user->id; ?>/edit"><?= $user->login ?></a>
      <form action="/elexir2/index.php/user/<?= $user->id ?>" method="post" class="action">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </li>
    <? endforeach; ?>
  <ul>
  <? endif; ?>
</section>
