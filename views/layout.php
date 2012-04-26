<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Elexir : <?= h($title) ?></title>
    <!-- <link type="text/css" rel="stylesheet" href="/plateforme2/public/css/style.css" /> -->
    <link type="text/css" rel="stylesheet" href="/plateforme2/public/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="/plateforme2/public/css/style.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <nav class="navbar navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <a class="brand" href="/plateforme2/index.php">Elexir</a>
            <ul class="nav">
              <li class="active">
                <a href="/plateforme2/index.php" title="Accueil">Accueil</a>
              </li>
              <li>
                <a href="/plateforme2/index.php/users" title="Liste des utilisateurs">Liste des utilisateurs</a>
              </li>
              <li>
                <a href="/plateforme2/index.php/projects" title="Liste des projets">Liste des projets</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="hero-unit">
        <h1>Elexir</h1>
        <p>
          Bienvenue sur la plateforme d'analyse de puces du labo !
        </p>
      </div>
      <? if(!empty($notice)): ?>
      <div class="alert alert-success">
        <button class="close" data-dismiss="alert">×</button>
        <?= h($notice); ?>
      </div>
      <? endif; ?>
      <?= $out ?>
    </div>
  </body>
</html>
