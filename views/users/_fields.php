<div class="field">
  <?= label($user, 'login', 'login : ') ?>
  <?= field($user, 'login', 'text'); ?>
</div>
<div class="field">
  <?= label($user, 'password', 'password : ') ?>
  <?= field($user, 'password', 'password'); ?>
</div>
<div class="field">
  <?= label($user, 'password_confirm', 'Confirmation du password : ') ?>
  <?= field($user, 'password_confirm', 'password'); ?>
</div>
<div class="field">
  <?= label($user, 'admin', 'Admin ? ') ?>
  <?= checkbox($user, 'admin', 1); ?>
</div>
