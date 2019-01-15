<?php

session_start();

require 'env.php';

function showHeader($title) {
  ?>
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title><?=$title; ?> | DrawProf</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/bootstrap.min.css"/>
      <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="css/master.css">
    </head>
    <body>
      <!-- Navigation Bar -->
      <nav class="navbar navbar-dark bg-dark navbar-expand-sm">
        <a class="navbar-brand" href="index.php">DrawProf</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
          <ul class="navbar-nav mr-auto">
            <!-- <li class="nav-item">
              <a class="nav-link" href="howto.php">How To</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="gallery.php">Gallery</a>
            </li>
          </ul>
          <form class="form-inline" action="search.php" method="GET">
            <input name="query" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
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
        <div id="footerContainer">
          <footer class="mt-3 mb-3 pt-3 pb-2 border-top">
            <div class="row">
              <div class="col-12 text-center">
                &copy; 2019 DrawProf
                &bull;
                <a href="#">About</a>
                &bull;
                <a href="#">Contact</a>
                &bull;
                <a href="https://devpost.com/software/drawprof">DevPost</a>
                &bull;
                <a href="admin.php">Admin</a>
              </div>
          </footer>
        </div>
      </div>
    </body>
  </html>
  <?php
}
