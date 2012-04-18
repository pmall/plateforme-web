<section>
  <h1><?= h($title) ?></h1>
  <?= displayErrors($analysis); ?>
  <form action="" method="post">
    <? $this->partial('analyses/_fields.php') ?>
  </form>
  <script src="/plateforme2/public/js/analysis.js"></script>
</section>
