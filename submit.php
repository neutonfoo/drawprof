<?php
  require 'config.php';
  require 'profGetter.php';

  $acceptableImgDims = [[500, 700], [300, 400]];

  // Slug function for filename
  function slug($z) {
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
  }

  function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }


  // Store it in a session temporarily
  if(isset($_POST['imageDataUrl'])) {
    // So drawing not lost
    $_SESSION['tempSavedDrawing'] = $_POST['imageDataUrl'];
  }

  // Get Prof Name
  $fetchedProfMeta = getProfMeta($profRMPId);

  // If invalid URL - $profRMPId defined in profGetter.php
  if($fetchedProfMeta == "NULL") {
    header("Location: index.php?linkerror=1");
  } else {

    $artist = $_POST['artistName'];
    $imageDataUrl = $_POST['imageDataUrl'];
    $isMobile = $_POST['isMobile'];

    $profMeta = explode("|", getProfMeta($profRMPId));
    $profName = $profMeta[0];
    $uniName = $profMeta[1];

    // Confirm image dimensions
    // Save image
    $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageDataUrl));
    $imgDims = getimagesizefromstring($imgData);

    $imgDimsArray = [$imgDims[0], $imgDims[1]];

    if(!in_array($imgDimsArray, $acceptableImgDims)) {
      header("Location: index.php?sizeerror=1");
    } else {
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
          $profSlug = $row['profSlug'];
        }

        // No professor
        if(is_null($profId)) {
          $profSlug = slug($profName) . '-' . $profRMPId;

          $stmt = $conn->prepare("INSERT INTO drawprof_profs (uniId, profRMPId, profName, profSlug) VALUES(?, ?, ?, ?)");
          $stmt->execute([$uniId, $profRMPId, $profName, $profSlug]);

          $profId = $conn->lastInsertId();
        }

        // Insert drawing
        $stmt = $conn->prepare("INSERT INTO drawprof_drawings (profId, artist, submittedTime, isMobile, status, isHidden, statusChangeAdminId, statusChangeTime, IPAddress, likes) VALUES(?, ?, ?, ?, 0, 0, 0, 0, ?, 0)");
        $stmt->execute([$profId, trim($artist), time(), $isMobile, getRealIpAddr()]);

        $drawingId = $conn->lastInsertId();

        // Saves image
        file_put_contents("drawings/$uniSlug-$profSlug-$drawingId.png", $imgData);

        // Destroys session
        unset($_SESSION['tempSavedDrawing']);

        header("Location: $base_url/$uniSlug/$profSlug/$drawingId");
    }
}
