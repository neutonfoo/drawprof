<?php
showHeader('Admin Panel');

if(isset($_POST['formSubmit'])) {

  $drawings = $_POST['drawings'];

  foreach($drawings as $drawingId => $status) {
    $stmt = $conn->prepare("UPDATE drawprof_drawings SET status = ? WHERE drawingId = ?");
    $stmt->execute([$status, $drawingId]);
  }
}

?>
<h1><a href="?">AP</a> &bull; Approve Submissions</h1>
<p class="lead">Approve or reject pending submissions here. Submissions marked as <mark>unwholesome</mark> will be hidden.</p>
<form method="post">
<?php
$stmt = $conn->prepare("SELECT drawingId, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE drawprof_drawings.status = 0 ORDER BY drawingId ASC LIMIT 12");
$stmt->execute();

if ($stmt->rowCount() == 0) {
  ?>
  <p>No pending submissions!</p>
  <?php
} else {
  ?>
  <div class="form-row">
  <?php
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $drawingId = $row['drawingId'];
    $profName = $row['profName'];
    $profSlug = $row['profSlug'];
    $uniName = $row['uniName'];
    $uniSlug = $row['uniSlug'];

    $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";
    ?>
      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
        <div class="card my-2">
          <img src="drawings/<?=$drawingFilename; ?>" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title"><?=$profName; ?></h5>
            <p class="card-text"> from <u><?=$uniName; ?></u>.</p>
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
          </div>
        </div>
      </div>
    <?php
  }
  ?>
  </div>
  <div class="form-row">
    <div class="form-group col-12">
      <input type="hidden" name="formSubmit" value="1">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
<?php
}
showFooter();
