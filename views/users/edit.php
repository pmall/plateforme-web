<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($user) ?>
  <form action="/elexir2/index.php/user/<?= h($user->id) ?>" method="post">
    <? $this->partial('users/_fields.php') ?>
    <div class="field">
      <label for="valider">Valider :</label>
      <input id="valider" type="submit" value="valider" />
    </div>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
