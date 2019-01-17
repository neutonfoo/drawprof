<?php
showHeader('Hall of Shame ');

?>
<h1><a href="?">AP</a> &bull; Hall of Shame</h1>
<p class="lead">All <mark>unwholesome</mark> submissions will be displayed here.</p>
<div class="form-row">
<?php

$stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, profName, profSlug, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status = 3 AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY drawingId DESC LIMIT 12");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

  $drawingId = $row['drawingId'];
  $submittedTime = $row['submittedTime'];
  $isMobile = $row['isMobile'];
  $profName = $row['profName'];
  $profSlug = $row['profSlug'];
  $uniName = $row['uniName'];
  $uniSlug = $row['uniSlug'];

  $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

  ?>

  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
    <div class="card my-2">
      <a href="<?=$base_url . "drawing.php?drawing=$drawingId"; ?>"><img src="drawings/<?=$drawingFilename; ?>" class="card-img-top" alt="..."></a>
      <div class="card-body">
        <h5 class="card-title"><?=$profName; ?></h5>
        <p class="card-text"> from <u><?=$uniName; ?></u>.</p>
      </div>
    </div>
  </div>
  <?php
}
?>
</div>
<?php
showFooter();
