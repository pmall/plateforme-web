<fieldset>
  <legend>Description de l'analyse</legend>
  <div class="algo_desc">
    <dl>
      <dt><em>paire</em></dt>
      <dd>A : controle, B : test, réplicats de B contre réplicats de A (autant de réplicats dans A et B)</dd>
      <dt><em>impaire</em></dt>
      <dd>A : controle, B : test, moyenne des réplicats de B contre moyenne des réplicats de A</dd>
      <dt><em>J/O</em></dt>
      <dd>A/C vs B/D ou je sais plus quoi</dd>
    </dl>
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
    <? foreach($project->conditions as $condition): ?>
    <li>
      <div class="field">
        <? $error = ''; if($analysis->hasError($condition)){ $error = ' class="error"'; } ?>
        <label for="group_<?= ++$i; ?>"<?= $error; ?>><?= $condition ?></label>
        <input name="analysis[groups][<?= h($condition) ?>][condition]" type="hidden" value="<?= h($condition) ?>" />
        <select id="group_<?= $i; ?>" class="group" name="analysis[groups][<?= h($condition) ?>][letter]">
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
