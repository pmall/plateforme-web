<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($analysis); ?>
  <form id="form_project" action="" method="post" class="form-horizontal">
    <? $this->partial('analyses/_fields.php') ?>
  </form>
</section>
