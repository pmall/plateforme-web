<div class="field">
  <?php echo label($project, 'id_user', 'Utilisateur :'); ?>
  <?php echo select($project, 'id_user', $users); ?>
</div>
<div class="field">
  <?php echo label($project, 'name', 'nom :'); ?>
  <?php echo field($project, 'name', 'text'); ?>
</div>
<div class="field">
  <?php echo label($project, 'type', 'type :'); ?>
  <?php echo select($project, 'type', array(
	'exon' => 'exon',
	'ggh' => 'ggh'
  )); ?>
</div>
<div class="field">
  <?php echo label($project, 'organism', 'organisme :'); ?>
  <?php echo select($project, 'organism', array(
	'human' => 'humain',
	'mouse' => 'souris'
  )); ?>
</div>
<div class="field">
  <?php echo label($project, 'cell_line', 'Lignée :'); ?>
  <?php echo field($project, 'cell_line', 'text'); ?>
</div>
<div class="field">
  <?php echo label($project, 'comment', 'commentaire :'); ?>
  <?php echo textarea($project, 'comment'); ?>
</div>
<div class="field">
  <?php echo label($project, 'public', 'public ?'); ?>
  <?php echo checkbox($project, 'public', 1); ?>
</div>
<fieldset>
  <legend>Fichiers cel du répertoire</legend>
  <ul>
    <?php $i = 0; ?>
    <?php foreach($celfiles as $celfile): ?>
    <li>
      <div class="field">
	<?php $error = ''; if($project->hasError($celfile)){ $error = ' class="error"'; } ?>
        <label for="chip_<?php echo ++$i ?>"<?php echo $error ?>><?php echo $celfile ?></label>
        <input id="chip_<?php echo $i ?>" name="project[chips][<?php echo $celfile ?>][num]" type="text" size="3" value="<?php echo $project->getChipNum($celfile) ?>" />
        <input name="project[chips][<?php echo $celfile ?>][condition]" type="text" value="<?php echo $project->getChipCondition($celfile) ?>" />
        <input name="project[chips][<?php echo $celfile ?>][name]" type="hidden" value="<?php echo $celfile ?>" />
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
</fieldset>
