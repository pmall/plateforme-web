<fieldset>
  <legend>Description de l'analyse</legend>
  <div class="alert alert-info">
    <dl class="dl-horizontal">
      <dt>paire</dt>
      <dd>A : controle, B : test, réplicats de B contre réplicats de A (autant de réplicats dans A et B)</dd>
      <dt>impaire</dt>
      <dd>A : controle, B : test, moyenne des réplicats de B contre moyenne des réplicats de A</dd>
      <dt>J/O</dt>
      <dd>A/C vs B/D ou je sais plus quoi</dd>
    </dl>
  </div>
  <div class="control-group<?= hasError($analysis, 'name'); ?>">
    <?= label($analysis, 'name', 'Nom'); ?>
    <div class="controls">
      <?= field($analysis, 'name', 'text', array('maxlength' => 255)); ?>
    </div>
  </div>
  <div class="control-group<?= hasError($analysis, 'version'); ?>">
    <?= label($analysis, 'version', 'Version fasterdb') ?>
    <div class="controls">
      <?= select($analysis, 'version', array(
	'fdb1' => 'fasterdb 1',
	'fdb2' => 'fasterdb 2'
      )) ?>
    </div>
  </div>
  <div class="control-group<?= hasError($analysis, 'type'); ?>">
    <?= label($analysis, 'type', 'Type d\'analyse') ?>
    <div class="controls">
      <?= select($analysis, 'type', array(
	'simple' => 'Simple',
	'compose' => 'Compose',
	'apriori' => 'A priori',
	'jonction' => 'Jonction'
      )) ?>
    </div>
  </div>
  <div class="control-group">
    <?= label($analysis, 'paired', 'Paire ? ') ?>
    <div class="controls">
      <?= checkbox($analysis, 'paired', 1); ?>
    </div>
  </div>
</fieldset>
<fieldset>
  <legend>Conditions du projet</legend>
  <? $i = 0; ?>
  <? foreach($project->conditions as $condition): ?>
  <div class="control-group">
    <? $error = ''; if($analysis->hasError($condition)){ $error = ' error'; } ?>
    <label for="group_<?= ++$i; ?>" class="control-label<?= $error ?>"><?= $condition ?></label>
    <div class="controls">
      <input name="analysis[groups][<?= h($condition) ?>][condition]" type="hidden" value="<?= h($condition) ?>" />
      <select id="group_<?= $i; ?>" class="group" name="analysis[groups][<?= h($condition) ?>][letter]">
        <option value=""></option>
        <option value="A"<? if($analysis->groups[$condition]['letter'] == 'A'): ?> selected="selected"<? endif; ?>>A</option>
        <option value="B"<? if($analysis->groups[$condition]['letter'] == 'B'): ?> selected="selected"<? endif; ?>>B</option>
        <option value="C"<? if($analysis->groups[$condition]['letter'] == 'C'): ?> selected="selected"<? endif; ?>>C</option>
        <option value="D"<? if($analysis->groups[$condition]['letter'] == 'D'): ?> selected="selected"<? endif; ?>>D</option>
      </select>
    </div>
  </div>
  <? endforeach; ?>
</fieldset>
<div class="form-actions">
  <button type="submit" class="btn-success" />Valider</button>
</div>
<script type="text/javascript">setLetters();</script>
