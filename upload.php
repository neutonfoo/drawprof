<?php
  require('dbconfig.php');

  $profName = $_POST['profName'];
  $uniName = $_POST['uniName'];
  $imageDataUrl = $_POST['imageDataUrl'];
  $isMobile = $_POST['isMobile'];

  // Default
  $uniId = NULL;
  $profId = NULL;

  // Open connection
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT uniId FROM drawprof_unis WHERE uniName = ?");
  $stmt->execute([$uniName]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $uniId = $row['uniId'];
  }

  // No university
  if(is_null($uniId)) {
    $stmt = $conn->prepare("INSERT INTO drawprof_unis (uniName) VALUES(?)");
    $stmt->execute([$uniName]);

    $uniId = $conn->lastInsertId();
  }

  $stmt = $conn->prepare("SELECT profId FROM drawprof_profs WHERE (uniId = ? AND profName = ?)");
  $stmt->execute([$uniId, $profName]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $profId = $row['profId'];
  }

  // No professor
  if(is_null($profId)) {
    $stmt = $conn->prepare("INSERT INTO drawprof_profs (uniId, profName) VALUES(?, ?)");
    $stmt->execute([$uniId, $profName]);

    $profId = $conn->lastInsertId();
  }

  $stmt = $conn->prepare("INSERT INTO drawprof_drawings (profId, publishedDate, isMobile) VALUES(?, ?, ?)");
  $stmt->execute([$profId, date("Y-m-d H:i:s"), $isMobile]);

  $drawingId = $conn->lastInsertId();

  // Save image
  $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));
  file_put_contents("drawings/$drawingId.png", $data);

  header("Location: drawings.php");
