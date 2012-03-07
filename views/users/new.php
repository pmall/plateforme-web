<? require 'helpers/form.php' ?>
<section>
  <h1><?= $title; ?></h1>
  <?= displayErrors($user) ?>
  <form action="" method="post">
    <? $this->partial('users/_fields.php') ?>
    <div class="field">
      <label for="valider">Valider :</label>
      <input id="valider" type="submit" value="valider" />
    </div>
  </form>
</section>
