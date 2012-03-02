<ul class="projects">
  <?php foreach($projects as $project): ?>
  <li class="project">
    <p class="desc">
      <strong><?php echo $project->id ?></strong> /
      <?php echo $project->type ?> /
      <?php echo $project->organism ?> /
      <?php echo $project->cell_line ?> /
      <a href="/elexir2/index.php/project/<?php echo $project->id; ?>/edit"><?php echo $project->name ?></a> /
      <a href="">preprocessing</a> /
      <a href="">nouvelle analyse</a>
      <form action="/elexir2/index.php/project/<?php echo $project->id ?>" method="post" class="action">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </p>
    <ul class="analyses">
      <li>
        <a href="">Blah</a>
      </li>
    </ul>
    <?php if($project->comment): ?>
    <p class="comment">
      <?php echo $project->comment; ?>
    </p>
    <?php endif ?>
  </li>
  <?php endforeach; ?>
</ul>
