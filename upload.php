<?php
  require 'dbconfig.php';

  // Slug function for filename
  function slug($z){
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
  }

  // Get POST values
  $profName = $_POST['profName'];
  $uniName = $_POST['uniName'];
  $imageDataUrl = $_POST['imageDataUrl'];
  $isMobile = $_POST['isMobile'];

  // Default
  $uniId = NULL;
  $uniSlug = NULL;
  $profId = NULL;
  $profSlug = NULL;

  // Get university
  $stmt = $conn->prepare("SELECT uniId, uniSlug FROM drawprof_unis WHERE uniName = ?");
  $stmt->execute([$uniName]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $uniId = $row['uniId'];
    $uniSlug = $row['uniSlug'];
  }

  // No university
  if(is_null($uniId)) {
    $uniSlug = slug($uniName);

    $stmt = $conn->prepare("INSERT INTO drawprof_unis (uniName, uniSlug) VALUES(?,?)");
    $stmt->execute([$uniName, $uniSlug]);

    $uniId = $conn->lastInsertId();
  }

  // Get professor
  $stmt = $conn->prepare("SELECT profId, profSlug FROM drawprof_profs WHERE (uniId = ? AND profName = ?)");
  $stmt->execute([$uniId, $profName]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $profId = $row['profId'];
    $profSlug = $row['profSlug'];
  }

  // No professor
  if(is_null($profId)) {
    $profSlug = slug($profName);

    $stmt = $conn->prepare("INSERT INTO drawprof_profs (uniId, profName, profSlug) VALUES(?, ?, ?)");
    $stmt->execute([$uniId, $profName, $profSlug]);

    $profId = $conn->lastInsertId();
  }

  // Insert drawing
  $stmt = $conn->prepare("INSERT INTO drawprof_drawings (profId, publishedDate, isMobile, approvalStatus) VALUES(?, ?, ?, 0)");
  $stmt->execute([$profId, date("Y-m-d H:i:s"), $isMobile]);

  $drawingId = $conn->lastInsertId();

  // Save image
  $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));
  file_put_contents("drawings/$uniSlug-$profSlug-$drawingId.png", $data);

  header("Location: drawing.php?drawing=$drawingId");
