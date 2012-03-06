    <div class="field">
      <?= label($analysis, 'name', 'Nom :'); ?>
      <?= field($analysis, 'name', 'text'); ?>
    </div>
    <div class="field">
      <?= label($analysis, 'type', 'Type d\'analyse :') ?>
      <?= select($analysis, 'type', array(
	'paire' => 'paire',
	'impaire' => 'impaire',
	'J/O' => 'J / O'
      )) ?>
    </div>
    <div class="algo_desc">
      <ul>
        <li>paire : A vs B, réplicats de A contre réplicats de B (autant de réplicats dans A et B)</li>
        <li>impaire : A vs B, moyenne des réplicats de A contre moyenne des réplicats de B</li>
        <li>J / O : A/C vs B/D ou je sais plus quoi</li>
      </ul>
    </div>
    <fieldset>
      <legend>Conditions du projet</legend>
      <ul>
        <? $i = 0; ?>
        <? foreach($project->conditions as $condition): ?>
        <li>
          <div class="field">
            <? $error = ''; if($analysis->hasError($condition['name'])){ $error = ' class="error"'; } ?>
            <label for="condition_<?= ++$i; ?>"<?= $error; ?>><?= $condition['name'] ?></label>
            <input name="analysis[groups][<?= $condition['name'] ?>][id_condition]" type="hidden" value="<?= $condition['id'] ?>" />
            <input name="analysis[groups][<?= $condition['name'] ?>][name]" type="hidden" value="<?= $condition['name'] ?>" />
            <select id="condition_<?= $i; ?>" name="analysis[groups][<?= $condition['name'] ?>][letter]">
              <option value=""></option>
              <option value="A"<? if($analysis->groups[$condition['name']]['letter'] == 'A'): ?> selected="selected"<? endif; ?>>A</option>
              <option value="B"<? if($analysis->groups[$condition['name']]['letter'] == 'B'): ?> selected="selected"<? endif; ?>>B</option>
              <option value="C"<? if($analysis->groups[$condition['name']]['letter'] == 'C'): ?> selected="selected"<? endif; ?>>C</option>
              <option value="D"<? if($analysis->groups[$condition['name']]['letter'] == 'D'): ?> selected="selected"<? endif; ?>>D</option>
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
