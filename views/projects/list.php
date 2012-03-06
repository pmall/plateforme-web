<section>
  <h1><?php echo $title ?></h1>
  <form action="" method="get">
    <fieldset>
      <legend>Filtrer les projets</legend>
      <select name="id_user">
        <option value="">Tous les utilisateurs</option>
        <?php foreach($users as $id => $name): ?>
        <option value="<?php echo $id ?>"<?php if($filter['id_user'] == $id): ?> selected="selected"<?php endif; ?>><?php echo $name ?></option>
        <?php endforeach; ?>
      </select>
      <input name="name" type="text" value="<?php echo $filter['name'] ?>" placeholder="Nom du projet" />
      <select name="type">
        <option value="">Tout types de puces</option>
        <option value="exon"<?php if($filter['type'] == 'exon'): ?> selected="selected"<?php endif; ?>>exon</option>
        <option value="ggh"<?php if($filter['type'] == 'ggh'): ?> selected="selected"<?php endif; ?>>ggh</option>
      </select>
      <select name="organism">
        <option value="">Tout organismes</option>
        <option value="human"<?php if($filter['organism'] == 'human'): ?> selected="selected"<?php endif; ?>>Humain</option>
        <option value="mouse"<?php if($filter['organism'] == 'mouse'): ?> selected="selected"<?php endif; ?>>Mouse</option>
      </select>
      <input name="cell_line" type="text" value="<?php echo $filter['cell_line'] ?>" placeholder="Lignée cellulaire" />
      <input type="submit" value="filtrer" />
    </fieldset>
  </form>
  <?php if(count($projects) == 0): ?>
  <p>
    Il n'y a aucun projet a afficher.
  </p>
  <p>
    <a href="/elexir2/index.php">Retour à l'accueil</a>.
  </p>
  <?php else: ?>
  <?php $this->partial('projects/_list.php'); ?>
  <?php endif; ?>
</section>
