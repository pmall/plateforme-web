<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?= h($title) ?></title>
    <link type="text/css" rel="stylesheet" href="/elexir2/public/css/style.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  </head>
  <body>
    <h1>Plateforme</h1>
    <nav>
      <ul>
        <li>
          <a href="/elexir2/index.php" title="Accueil">Accueil</a>
        </li>
        <li>
          <a href="/elexir2/index.php/users" title="Liste des utilisateurs">Liste des utilisateurs</a>
        </li>
        <li>
          <a href="/elexir2/index.php/projects" title="Liste des projets">Liste des projets</a>
        </li>
      </ul>
    </nav>
    <? if(!empty($notice)): ?>
    <div id="notice">
      <?= h($notice); ?>
    </div>
    <? endif; ?>
    <?= $out ?>
  </body>
</html>
