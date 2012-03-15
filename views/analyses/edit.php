<? include('helpers/form.php') ?>
<section>
  <h1><?= $title ?></h1>
  <?= displayErrors($analysis); ?>
  <form action="" method="post">
    <? $this->partial('analyses/_fields.php') ?>
    <input name="_method" type="hidden" value="put" />
  </form>
  <script src="/elexir2/public/js/analysis.js"></script>
</section>
