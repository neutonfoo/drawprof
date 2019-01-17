<?php
require 'requires/core.php';
require 'dbconfig.php';

$drawingId = NULL;

if(isset($_GET['drawing'])) {
  $drawingId = $_GET['drawing'];
}

if(is_null($drawingId) || $drawingId == '') {
  header("Location: gallery.php");
} else {
  // Drawing Spec
  $stmt = $conn->prepare("SELECT drawingId, artist, submittedTime, status, profRMPId, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE drawprof_drawings.drawingId = ? LIMIT 1");
  $stmt->execute([$drawingId]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $drawingId = $row['drawingId'];
    $artist = $row['artist'] == '' ? 'anonymous' : $row['artist'];
    $submittedTime = $row['submittedTime'];
    $status = $row['status'];

    $profRMPId = $row['profRMPId'];
    $profName = $row['profName'];
    $profSlug = $row['profSlug'];
    $uniName = $row['uniName'];
    $uniSlug = $row['uniSlug'];

    $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

    $link = $base_url . "drawing.php?drawing=$drawingId";

    // Since only 1, safe to do this
    showHeader("$profName by $artist");

    if($status == 1) {
      // If Approved, redirect to clean URL
    } else if($status == 0) {
      ?>
      <div class="alert alert-info" role="alert">This submission is pending approval.</div>
      <div class="alert alert-info" role="alert">Use this link to check on its status: <a href="<?=$link; ?>"><?=$link; ?></a>.</div>
      <?php
    } else if($status == 2) {
      ?>
      <div class="alert alert-danger" role="alert">This submission has been rejected.</div>
      <?php
    } else if($status == 3) {
      ?>
      <div class="alert alert-danger" role="alert">This submission has been removed for being unwholesome.</div>
      <?php
    }

    if($status != 3 || isSuperAdmin()) {
      ?>

      <!-- Professor Meta Container -->
      <div class="row justify-content-center">
        <div class="col text-center">
          <h2 class="profName"><?=$profName; ?></h2>
          <h3 class="uniName"><?=$uniName; ?></h3>
        </div>
      </div>

      <!-- Canvas Container -->
      <div class="row">
        <div class="col text-center">
          <div class="drawingContainer">
            <img class="d" src="drawings/<?=$drawingFilename; ?>">
          </div>
        </div>
      </div>

      <!-- Artist -->
      <div class="row">
        <div class="col text-center pt-3">
          <blockquote class="blockquote">
            <footer class="blockquote-footer"><?=$artist; ?></footer>
          </blockquote>
        </div>
      </div>

      <!-- Submitted Time -->
      <div class="row">
        <div class="col text-center">
          <p><small>Submitted On: <u><em><?=parseTimestamp($submittedTime); ?></em></u>.</small></p>
        </div>
      </div>
      <?php
    }
  }
}

showFooter();
