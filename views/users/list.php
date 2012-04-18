<section>
  <h1><?= h($title) ?></h1>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs. <a href="/plateforme2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <p>
    <a href="/plateforme2/index.php/user">Ajouter un utilisateur</a>
  </p>
  <ul>
    <? foreach($users as $user): ?>
    <li id="user_<?= h($user->id) ?>" class="user">
      <span class="login">
        <a href="/plateforme2/index.php/user/<?= $user->id; ?>"><?= $user->login ?></a>
      </span>
      [<a href="/plateforme2/index.php/user/<?= $user->id ?>/edit">modifier</a>]
      <form action="/plateforme2/index.php/user/<?= $user->id ?>"
            method="post"
            class="action delete"
            data-type="user"
            data-id="<?= h($user->id) ?>">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </li>
    <? endforeach; ?>
  <ul>
  <script src="/plateforme2/public/js/list.js"></script>
  <? endif; ?>
</section>
