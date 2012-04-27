<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($user) ?>
  <form action="" method="post" class="form-horizontal">
    <? $this->partial('users/_fields.php') ?>
  </form>
</section>
