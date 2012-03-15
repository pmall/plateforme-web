<? if(!isset($url)): $url = ''; endif; ?>
<form action="<?= $url ?>" method="get">
  <fieldset>
    <legend>Filtrer les projets</legend>
    <? if(isset($users)): ?>
    <select name="id_user">
      <option value="">Tous les utilisateurs</option>
      <? foreach($users as $id => $name): ?>
      <option value="<?= $id ?>"<? if($filter['id_user'] == $id): ?> selected="selected"<? endif; ?>><?= $name ?></option>
      <? endforeach; ?>
    </select>
    <? endif; ?>
    <input name="name" type="text" value="<?= $filter['name'] ?>" placeholder="Nom du projet" />
    <select name="type">
      <option value="">Tout types de puces</option>
      <option value="exon"<? if($filter['type'] == 'exon'): ?> selected="selected"<? endif; ?>>exon</option>
      <option value="ggh"<? if($filter['type'] == 'ggh'): ?> selected="selected"<? endif; ?>>ggh</option>
    </select>
    <select name="organism">
      <option value="">Tout organismes</option>
      <option value="human"<? if($filter['organism'] == 'human'): ?> selected="selected"<? endif; ?>>Humain</option>
      <option value="mouse"<? if($filter['organism'] == 'mouse'): ?> selected="selected"<? endif; ?>>Mouse</option>
    </select>
    <input name="cell_line" type="text" value="<?= $filter['cell_line'] ?>" placeholder="Lignée cellulaire" />
    <input type="submit" value="filtrer" />
    <p>
      Pour les champs nom du projet et lignée cellulaire, le caractère % peut être utilisé pour remplacer 0 ou plusieurs caractères. Exemple : MCF% retourne à la fois MCF7 et MCF10.
    </p>
  </fieldset>
</form>
