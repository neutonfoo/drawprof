<?php
  require 'dbconfig.php';
  require 'profGetter.php';

  // Slug function for filename
  function slug($z) {
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
  }

  // Get POST values
  $validURLS = ["http://www.ratemyprofessors.com/ShowRatings.jsp?tid=", "https://www.ratemyprofessors.com/ShowRatings.jsp?tid=", "http://ratemyprofessors.com/ShowRatings.jsp?tid=", "https://ratemyprofessors.com/ShowRatings.jsp?tid="];
  $profRMPId = str_replace($validURLS, '', $profUrl);

  // If invalid URL
  /*
  if(isNaN($profRMPId)) {

  } else {

  }
  */

  $profUrl = $_POST['profUrl'];
  $artist = $_POST['artistName'];
  $imageDataUrl = $_POST['imageDataUrl'];
  $isMobile = $_POST['isMobile'];

  // Get Prof Name
  $profMeta = explode("|", getProfMeta($profUrl));
  $profName = $profMeta[0];
  $uniName = $profMeta[1];

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
  $stmt = $conn->prepare("SELECT profId, profRMPId, profSlug FROM drawprof_profs WHERE (uniId = ? AND profRMPId = ?)");
  $stmt->execute([$uniId, $profRMPId]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $profId = $row['profId'];
    $profSlug = $row['profSlug'] . '-' . $row['profRMPId'];
  }

  // No professor
  if(is_null($profId)) {
    $profSlug = slug($profName) . '-' . $profRMPId;

    $stmt = $conn->prepare("INSERT INTO drawprof_profs (uniId, profRMPId, profName, profSlug) VALUES(?, ?, ?, ?)");
    $stmt->execute([$uniId, $profRMPId, $profName, $profSlug]);

    $profId = $conn->lastInsertId();
  }

  // Insert drawing
  $stmt = $conn->prepare("INSERT INTO drawprof_drawings (profId, artist, submittedTime, isMobile, status, statusChangeAdminId, statusChangeTime) VALUES(?, ?, ?, ?, 0, 0, 0)");
  $stmt->execute([$profId, trim($artist), time(), $isMobile]);

  $drawingId = $conn->lastInsertId();

  // Save image
  $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));
  file_put_contents("drawings/$uniSlug-$profSlug-$drawingId.png", $data);

  header("Location: drawing.php?drawing=$drawingId");
