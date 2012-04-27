<fieldset>
  <legend>Informations de l'utilisateur</legend>
  <div class="control-group">
    <?= label($user, 'login', 'login') ?>
    <div class="controls">
      <?= field($user, 'login', 'text'); ?>
    </div>
  </div>
  <div class="control-group">
    <?= label($user, 'password', 'password') ?>
    <div class="controls">
      <?= field($user, 'password', 'password'); ?>
    </div>
  </div>
  <div class="control-group">
    <?= label($user, 'password_confirm', 'Confirmation du password') ?>
    <div class="controls">
      <?= field($user, 'password_confirm', 'password'); ?>
    </div>
  </div>
  <div class="control-group">
    <?= label($user, 'admin', 'Admin ? ') ?>
    <div class="controls">
      <?= checkbox($user, 'admin', 1); ?>
    </div>
  </div>
</fieldset>
<div class="form-actions">
  <button type="submit" class="btn-success" />Valider</button>
</div>
