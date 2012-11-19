<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Elexir : <?= h($title) ?></title>
    <link type="text/css" rel="stylesheet" href="/plateforme2/public/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="/plateforme2/public/css/style.css" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="/plateforme2/public/js/bootstrap-alert.js"></script>
    <script type="text/javascript" src="/plateforme2/public/js/bootstrap-dropdown.js"></script>
    <script type="text/javascript" src="/plateforme2/public/js/script.js"></script>
  </head>
  <body>
    <div class="container">
      <nav class="navbar navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <a class="brand" href="/plateforme2/index.php">Elexir</a>
            <ul class="nav">
              <li>
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
      <div class="alert alert-success">
        L'algo apriori ne marche plus : il a été intégré à l'analyse simple.
        Maintenant l'analyse simple sort 4 excels : transcription, epissage, apriori et le croisment des listes epissage et apriori.
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
