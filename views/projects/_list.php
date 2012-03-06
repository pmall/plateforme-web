<ul class="projects">
  <?php foreach($projects as $project): ?>
  <li class="project">
    <p>
      <strong><?php echo $project->id ?></strong> /
      <a href="/elexir2/index.php/project/<?php echo $project->id; ?>/edit"><?php echo $project->name ?></a> /
      <a href="/elexir2/index.php/projects/?type=<?php echo $project->type ?>"><?php echo $project->type ?></a> /
      <a href="/elexir2/index.php/projects/?organism=<?php echo $project->organism ?>"><?php echo $project->organism ?></a> /
      <a href="/elexir2/index.php/projects/?cell_line=<?php echo $project->cell_line ?>"><?php echo $project->cell_line ?></a>
    </p>
    <p>
      <form action="" method="get" class="action">
        <input type="submit" value="Contrôle Qualité" />
      </form>
      <form action="" method="get" class="action">
        <input type="submit" value="preprocessing" />
      </form>
      <form action="/elexir2/index.php/project/<?php echo $project->id ?>/analysis" method="get" class="action">
        <input type="submit" value="Nouvelle analyse" />
      </form>
      <form action="/elexir2/index.php/project/<?php echo $project->id ?>" method="post" class="action">
        <input name="_method" type="hidden" value="delete" />
        <input type="submit" value="supprimer" />
      </form>
    </p>
    <!-- <ul class="analyses"> -->
      <!-- <li><a href="">Nouvelle analyse</a></li> -->
    <!-- </ul> -->
    <?php if($project->comment): ?>
    <p class="comment">
      <?php echo $project->comment; ?>
    </p>
    <?php endif ?>
  </li>
  <?php endforeach; ?>
</ul>
