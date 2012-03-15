<fieldset>
  <legend>Description de l'analyse</legend>
  <div class="algo_desc">
    <ul>
      <li>paire : A vs B, réplicats de A contre réplicats de B (autant de réplicats dans A et B)</li>
      <li>impaire : A vs B, moyenne des réplicats de A contre moyenne des réplicats de B</li>
      <li>J / O : A/C vs B/D ou je sais plus quoi</li>
    </ul>
  </div>
  <div class="field">
    <?= label($analysis, 'name', 'Nom :'); ?>
    <?= field($analysis, 'name', 'text', array('maxlength' => 255)); ?>
  </div>
  <div class="field">
    <?= label($analysis, 'type', 'Type d\'analyse :') ?>
    <?= select($analysis, 'type', array(
	'paire' => 'paire',
	'impaire' => 'impaire',
	'J/O' => 'J / O'
    )) ?>
  </div>
</fieldset>
<fieldset>
  <legend>Conditions du projet</legend>
  <ul class="list">
    <? $i = 0; ?>
    <? foreach($project->conditions as $id_condition => $condition): ?>
    <li>
      <div class="field">
        <? $error = ''; if($analysis->hasError($condition)){ $error = ' class="error"'; } ?>
        <label for="group_<?= ++$i; ?>"<?= $error; ?>><?= $condition ?></label>
        <input name="analysis[groups][<?= $condition ?>][id_condition]" type="hidden" value="<?= $id_condition ?>" />
        <input name="analysis[groups][<?= $condition ?>][name]" type="hidden" value="<?= $condition ?>" />
        <select id="group_<?= $i; ?>" class="group" name="analysis[groups][<?= $condition ?>][letter]">
          <option value=""></option>
          <option value="A"<? if($analysis->groups[$condition]['letter'] == 'A'): ?> selected="selected"<? endif; ?>>A</option>
          <option value="B"<? if($analysis->groups[$condition]['letter'] == 'B'): ?> selected="selected"<? endif; ?>>B</option>
          <option value="C"<? if($analysis->groups[$condition]['letter'] == 'C'): ?> selected="selected"<? endif; ?>>C</option>
          <option value="D"<? if($analysis->groups[$condition]['letter'] == 'D'): ?> selected="selected"<? endif; ?>>D</option>
        </select>
      </div>
    </li>
    <? endforeach; ?>
  </ul>
</fieldset>
<div class="field">
  <label for="valider">Valider :</label>
  <input id="valider" type="submit" value="Valider" />
</div>
