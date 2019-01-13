<?php
  function showHeader($title) {
    ?>
    <!DOCTYPE html>
    <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title><?=$title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/master.css">
        <script type="text/javascript" src="js/drawprofNew.js"></script>
      </head>
      <body>
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
          <a class="navbar-brand" href="index.php">DrawProf</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="drawings.php">Gallery</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li> -->
            </ul>
          </div>
        </nav>
        <!-- Main Container -->
        <div class="container-fluid">
          <div class="row">
            <div id="mainContainer" class="col">
    <?php
  }

  function showFooter() {
    ?>
            </div>
          </div>
        </div>
      </body>
    </html>

    <?php
  }
