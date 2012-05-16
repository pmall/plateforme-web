<? if(!isset($url)): $url = ''; endif; ?>
<form action="<?= $url ?>" method="get" class="form-inline">
  <fieldset>
    <legend>Filtrer les projets</legend>
    <input name="name" type="text" value="<?= h($filter['name']) ?>" placeholder="Nom du projet" class="span3" />
    <select name="id_user" class="span2"<? if($user){ echo " disabled=\"disabled\""; } ?>>
      <option value="">Tous les utilisateurs</option>
      <? foreach($users as $id_user => $name_user): ?>
      <option value="<?= h($id_user) ?>"<? if($filter['id_user'] == $id): ?> selected="selected"<? endif; ?>><?= h($name_user) ?></option>
      <? endforeach; ?>
    </select>
    <select name="type" class="span2">
      <option value="">Tout type de puce</option>
      <option value="exon"<? if($filter['type'] == 'exon'): ?> selected="selected"<? endif; ?>>exon</option>
      <option value="ggh"<? if($filter['type'] == 'ggh'): ?> selected="selected"<? endif; ?>>ggh</option>
    </select>
    <select name="organism" class="span2">
      <option value="">Tout organisme</option>
      <option value="human"<? if($filter['organism'] == 'human'): ?> selected="selected"<? endif; ?>>Humain</option>
      <option value="mouse"<? if($filter['organism'] == 'mouse'): ?> selected="selected"<? endif; ?>>Mouse</option>
    </select>
    <input name="cell_line" type="text" value="<?= h($filter['cell_line']) ?>" placeholder="Lignée cellulaire" class="span2" />
    <button type="submit" class="btn btn-primary"><i class="icon-repeat icon-white"></i> filtrer</button>
    <p class="help-block">
      Pour les champs nom du projet et lignée cellulaire, le caractère % peut être utilisé pour remplacer 0 ou plusieurs caractères. Exemple : MCF% retourne à la fois MCF7 et MCF10.
    </p>
  </fieldset>
</form>
