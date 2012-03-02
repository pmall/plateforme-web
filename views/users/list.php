<section>
  <h1><?php echo $title; ?></h1>
  <?php if(count($users) == 0): ?>
  <p>
    Il n'y a pas d'utilisateurs.
  </p>
  <p>
    <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>.
  </p>
  <?php else: ?>
  <ul>
    <li>
      <a href="/elexir2/index.php/user">Ajouter un utilisateur</a>
    </li>
    <?php foreach($users as $user): ?>
    <li>
      <a href="/elexir2/index.php/user/<?php echo $user->id; ?>/edit"><?php echo $user->login ?></a>
      <form action="/elexir2/index.php/user/<?php echo $user->id ?>" method="post" class="action">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </li>
    <?php endforeach; ?>
  <ul>
  <?php endif; ?>
</section>
