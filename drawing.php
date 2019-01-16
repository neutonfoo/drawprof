<?php
require 'requires/core.php';
require 'dbconfig.php';

showHeader('About');

$uniSlug = NULL;
$profSlug = NULL;
$drawingId = NULL;

if(isset($_GET['uni'])) {
  $uniId = $_GET['uni'];
}

if(isset($_GET['prof'])) {
  $profId = $_GET['prof'];
}

if(isset($_GET['drawing'])) {
  $drawingId = $_GET['drawing'];
}

// Specific Drawing
if(!is_null($drawingId)) {
  $stmt = $conn->prepare("SELECT drawingId, artist, status, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE drawprof_drawings.drawingId = ?");
  $stmt->execute([$drawingId]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $drawingId = $row['drawingId'];
    $artist = $row['artist'];
    $status = $row['status'];

    $profName = $row['profName'];
    $profSlug = $row['profSlug'];
    $uniName = $row['uniName'];
    $uniSlug = $row['uniSlug'];

    $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

    $link = $base_url . "drawing.php?drawing=$drawingId";

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
      <div class="profMetaContainer">
        <h2 class="profName"><?=$profName; ?></h2>
        <h3 class="uniName"><?=$uniName; ?></h3>
      </div>
      <!-- Canvas Container -->
      <div class="drawingContainer">
        <img class="d" src="drawings/<?=$drawingFilename; ?>">
      </div>

      <div class="row">
        <div class="col text-center">
          Drawing by <u><?=$artist; ?></u>.
        </div>
      </div>
      <?php
    }

    /*
    if(isSuperAdmin()) {
      ?>
      <div class="row">
        <div class="col-4"></div>
          <div class="col-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="drawings[<?=$drawingId; ?>]" id="drawing_<?=$drawingId; ?>_approve" value="1" checked>
              <label class="form-check-label" for="drawing_<?=$drawingId; ?>_approve">
                Approve
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="drawings[<?=$drawingId; ?>]" id="drawing_<?=$drawingId; ?>_reject" value="2">
              <label class="form-check-label" for="drawing_<?=$drawingId; ?>_reject">
                Reject
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="drawings[<?=$drawingId; ?>]" id="drawing_<?=$drawingId; ?>_pending" value="0">
              <label class="form-check-label" for="drawing_<?=$drawingId; ?>_pending">
                Pending
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="drawings[<?=$drawingId; ?>]" id="drawing_<?=$drawingId; ?>_unwholesome" value="3">
              <label class="form-check-label" for="drawing_<?=$drawingId; ?>_unwholesome">
                Unwholesome
              </label>
            </div>
            <button type="submit" class="btn btn-primary">- V E T O -</button>
        </div>
        <div class="col-4"></div>
      </div>

      <?php
    }
    */
  }
} else if(!is_null($profId)) {
  // Professor Filter
  $stmt = $conn->prepare("SELECT drawingId, status, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE drawprof_drawings.drawingId = ?");
  $stmt->execute([$drawingId]);

}
showFooter();
