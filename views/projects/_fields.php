<p>A partir du répertoire <?= $dir; ?></p>
<fieldset>
  <legend>Description du projet</legend>
<div class="field">
  <?= label($project, 'id_user', 'Utilisateur :'); ?>
  <?= select($project, 'id_user', $users); ?>
</div>
<div class="field">
  <?= label($project, 'name', 'nom :'); ?>
  <?= field($project, 'name', 'text'); ?>
</div>
<div class="field">
  <?= label($project, 'type', 'type :'); ?>
  <?= select($project, 'type', array(
	'exon' => 'exon',
	'ggh' => 'ggh'
  )); ?>
</div>
<div class="field">
  <?= label($project, 'organism', 'organisme :'); ?>
  <?= select($project, 'organism', array(
	'human' => 'humain',
	'mouse' => 'souris'
  )); ?>
</div>
<div class="field">
  <?= label($project, 'cell_line', 'Lignée :'); ?>
  <?= field($project, 'cell_line', 'text'); ?>
</div>
<div class="field">
  <?= label($project, 'comment', 'commentaire :'); ?>
  <?= textarea($project, 'comment'); ?>
</div>
<div class="field">
  <?= label($project, 'public', 'public ?'); ?>
  <?= checkbox($project, 'public', 1); ?>
</div>
</fieldset>
<fieldset>
  <legend>Fichiers cel du répertoire</legend>
  <ul class="list">
    <? $i = 0; ?>
    <? foreach($celfiles as $celfile): ?>
    <li>
      <div class="field">
	<? $error = ''; if($project->hasError($celfile)){ $error = ' class="error"'; } ?>
        <label for="chip_<?= ++$i ?>"<?= $error ?>><?= $celfile ?></label>
        <input id="chip_<?= $i ?>" name="project[chips][<?= $celfile ?>][num]" type="text" size="3" value="<?= $project->getChipNum($celfile) ?>" />
        <input name="project[chips][<?= $celfile ?>][condition]" type="text" value="<?= $project->getChipCondition($celfile) ?>" />
        <input name="project[chips][<?= $celfile ?>][name]" type="hidden" value="<?= $celfile ?>" />
      </div>
    </li>
    <? endforeach; ?>
  </ul>
</fieldset>
<div class="field">
  <label for="valider">Valider :</label>
  <input id="valider" type="submit" value="valider" />
</div>
