<?php

session_start();

require 'env.php';

function showHeader($title) {
  global $base_url;
  global $show_analytics;
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

      <?php
        if($show_analytics) {
          ?>
          <!-- Global site tag (gtag.js) - Google Analytics -->
          <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132697667-2"></script>
          <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-132697667-2');
        </script>

          <?php
        }
      ?>

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
            <li class="nav-item">
              <a class="nav-link" href="gallery.php?sort=recent">Recent</a>
            </li>
          </ul>
          <form class="form-inline" action="search.php" method="GET">
            <input name="query" class="form-control mr-sm-2" type="search" placeholder="University or Professor" aria-label="Search" size="22">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
      </nav>

      <!-- Main Container -->
      <div class="container-fluid">
        <div class="row pt-3">
          <div class="col">
            <!-- Main Alert -->
            <div class="alert alert-info" role="alert">
              <div class="h4 alert-heading">Announcement</div>
              <p class="mb-0">DrawProf is currently in <b>Closed Beta</b>. We are working on optimizing mobile support. The site may occasionally go down for maintenance.</p>
                <!-- You can send all bug reports to our email <a href="mailto:drawprof@salhacks.com?Subject=About%DrawProf" class="alert-link ">drawprof@salhacks.com</a>. -->
            </div>
          </div>
        </div>
  <?php
  if(isset($_SESSION['adminId'])) {
    ?>
    <div class="row">
      <div class="col">
        <div class="alert alert-success" role="alert">Logged in as <strong><?=$_SESSION['adminName']; ?></strong> (<?=$_SESSION['adminEmail']; ?>).</div>
      </div>
    </div>
    <?php
  }
}

function showFooter() {
  global $base_url;
  ?>
        <footer class="mt-3 mb-3 pt-3 pb-1 border-top">
          <div class="row">
            <div class="col-12 text-center">
              <span class="p-1">&copy; 2019 DrawProf</span>
              <span class="mx-2">&bull;</span>
              <a href="<?=$base_url . 'about.php'; ?>" class="p-1">About</a>
              <span class="mx-2">&bull;</span>
              <a href="<?=$base_url . 'contact.php'; ?>" class="p-1">Contact</a>
              <!-- <span class="mx-2">&bull;</span>
              <a href="https://devpost.com/software/drawprof" class="p-1">DevPost</a> -->
              <span class="mx-2">&bull;</span>
              <a href="admin.php" class="p-1<?php if(isset($_SESSION['adminId'])) {?> bg-dark text-white<?php  } ?>">Admin</a>
              <?php
              // If logged in
              if(isset($_SESSION['adminId'])) {
                ?>
                <span class="mx-2">&bull;</span>
                <a href="login.php?logout=1" class="p-1 bg-warning text-dark">Logout</a>
              <?php
              }
              ?>
            </div>
          </div>
        </footer>
      </div>
    </body>
  </html>
  <?php
}

function isSuperAdmin() {
  if(isset($_SESSION['adminIsSuperAdmin'])) {
    if($_SESSION['adminIsSuperAdmin'] == 1) {
      return true;
    }
  }

  return false;
}

function parseTimestamp($timestamp) {
  return date("F j, Y, g:i a", $timestamp);
  // return gmdate("Y-m-d\TH:i:s\Z", $timestamp);
}
