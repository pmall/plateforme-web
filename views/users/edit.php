<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($user) ?>
  <form action="/plateforme2/index.php/user/<?= h($user->id) ?>" method="post" class="form-horizontal">
    <? $this->partial('users/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
