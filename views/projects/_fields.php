<fieldset>
  <legend>Description du projet</legend>
<div class="control-group<?= hasError($project, 'id_user'); ?>">
  <?= label($project, 'id_user', 'Utilisateur'); ?>
  <div class="controls">
    <?= select($project, 'id_user', $users); ?>
  </div>
</div>
<div class="control-group<?= hasError($project, 'name'); ?>">
  <?= label($project, 'name', 'nom'); ?>
  <div class="controls">
    <?= field($project, 'name', 'text', array('maxlength' => 255)); ?>
  </div>
</div>
<div class="control-group<?= hasError($project, 'type'); ?>">
  <?= label($project, 'type', 'type'); ?>
  <div class="controls">
    <?= select($project, 'type', array(
	'exon' => 'exon',
	'ggh' => 'ggh'
    )); ?>
  </div>
</div>
<div class="control-group<?= hasError($project, 'organism'); ?>">
  <?= label($project, 'organism', 'organisme'); ?>
  <div class="controls">
    <?= select($project, 'organism', array(
	'human' => 'humain',
	'mouse' => 'souris'
    )); ?>
  </div>
</div>
<div class="control-group<?= hasError($project, 'cell_line'); ?>">
  <?= label($project, 'cell_line', 'Lignée'); ?>
  <div class="controls">
    <?= field($project, 'cell_line', 'text', array('maxlength' => 20, 'list' => 'cell_lines')); ?>
    <? if(count($cell_lines) > 0): ?>
    <datalist id="cell_lines">
    <? foreach($cell_lines as $cell_line): ?>
    <option value="<?= $cell_line ?>"><?= $cell_line ?></option>
    <? endforeach; ?>
    </datalist>
    <? endif; ?>
  </div>
</div>
<div class="control-group">
  <?= label($project, 'comment', 'Commentaire'); ?>
  <div class="controls">
    <?= textarea($project, 'comment'); ?>
  </div>
</div>
<div class="control-group">
  <?= label($project, 'public', 'public ?'); ?>
  <div class="controls">
    <?= checkbox($project, 'public', 1); ?>
  </div>
</div>
</fieldset>
<fieldset>
  <legend>Fichiers cel du répertoire</legend>
    <? $i = 0; ?>
    <? foreach($celfiles as $celfile): ?>
    <? $error = ''; if($project->hasError($celfile)){ $error = ' error'; } ?>
    <div class="control-group<?= $error ?>">
      <label for="chip_<?= ++$i ?>" class="control-label"><?= $celfile ?></label>
      <div class="controls">
        <input name="project[chips][<?= $celfile ?>][name]" type="hidden" value="<?= $celfile ?>" />
        <input
		id="chip_<?= $i ?>"
		name="project[chips][<?= $celfile ?>][num]"
		type="text"
		value="<?= h($project->getChipNum($celfile)) ?>"
		maxlength="4"
		placeholder="Num"
		class="span1"
	/>
        <input
		name="project[chips][<?= $celfile ?>][condition]"
		type="text"
		value="<?= h($project->getChipCondition($celfile)) ?>"
		maxlength="20"
		placeholder="Condition"
		class="span3"
	/>
      </div>
    </div>
    <? endforeach; ?>
</fieldset>
<div class="form-actions">
  <button type="submit" class="btn-success" />Valider</button>
</div>
