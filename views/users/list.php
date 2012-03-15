<section>
  <h1><?= $title; ?></h1>
  <? if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs. <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <? else: ?>
  <ul>
    <li>
      <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>
    </li>
    <? foreach($users as $user): ?>
    <li id="user_<?= $user->id ?>">
      <a href="/elexir2/index.php/user/<?= $user->id; ?>"><?= $user->login ?></a>
      [<a href="/elexir2/index.php/user/<?= $user->id ?>/edit">modifier</a>]
      <form action="/elexir2/index.php/user/<?= $user->id ?>"
            method="post"
            class="action delete"
            data-type="user"
            data-id="<?= $user->id ?>">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </li>
    <? endforeach; ?>
  <ul>
  <script src="/elexir2/public/js/list.js"></script>
  <? endif; ?>
</section>
