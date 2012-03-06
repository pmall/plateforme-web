<? include('helpers/form.php') ?>
<section>
  <h1><?= $title ?></h1>
  <?= displayErrors($analysis); ?>
  <form action="" method="post">
    <? $this->partial('analyses/_fields.php') ?>
  </form>
</section>
