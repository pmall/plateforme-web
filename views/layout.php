<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <link type="text/css" rel="stylesheet" href="/elexir2/public/css/style.css" />
    <!-- shim pour que IE < 9 reconnaisse les balises html5 >
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
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
      <?= $notice; ?>
    </div>
    <? endif; ?>
    <?= $out ?>
  </body>
</html>
