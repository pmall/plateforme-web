<div class="field">
  <?php echo label($user, 'login', 'login : ') ?>
  <?php echo field($user, 'login', 'text'); ?>
</div>
<div class="field">
  <?php echo label($user, 'password', 'password : ') ?>
  <?php echo field($user, 'password', 'password'); ?>
</div>
<div class="field">
  <?php echo label($user, 'password_confirm', 'Confirmation du password : ') ?>
  <?php echo field($user, 'password_confirm', 'password'); ?>
</div>
<div class="field">
  <?php echo label($user, 'admin', 'Admin ? ') ?>
  <?php echo checkbox($user, 'admin', 1); ?>
</div>
