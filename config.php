<?php
  // Main Config File

  session_start();

  if(!isset($_SESSION['likedDrawings'])) {
    $_SESSION['likedDrawings'] = [];
  }

  require 'config/env.php';
  require 'config/dbconfig.php';
  require 'core.php';
  require 'siteSettings.php';
