<?php require 'helpers/form.php' ?>
<section>
  <h1><?php echo $title; ?></h1>
  <?php echo displayErrors($user) ?>
  <form action="/elexir2/index.php/user/<?php echo $user->id ?>" method="post">
    <?php $this->partial('users/_fields.php') ?>
    <div class="field">
      <label for="valider">Valider :</label>
      <input id="valider" type="submit" value="valider" />
    </div>
    <input name="_method" type="hidden" value="put" />
  </form>
</section>
