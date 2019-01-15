<?php
require 'requires/core.php';
require 'dbconfig.php';

showHeader('Gallery');
?>
<p class="text-primary">Filtering options in development.</p>
<?php

$stmt = $conn->prepare("SELECT drawingId, publishedDate, isMobile, profName, profSlug, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY drawingId DESC");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

  $drawingId = $row['drawingId'];
  $publishedDate = $row['publishedDate'];
  $isMobile = $row['isMobile'];
  $profName = $row['profName'];
  $profSlug = $row['profSlug'];
  $uniName = $row['uniName'];
  $uniSlug = $row['uniSlug'];

  $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

  ?>
    <p><strong><?=$profName; ?></strong> from <u><?=$uniName; ?></u></p>
    <p>
      Published on <em><?=$publishedDate; ?></em>
      <?php
        if($isMobile == 1) {
          ?>
          <small>(mobile)</small>
          <?php
        }
      ?>
    </p>

    <img src="drawings/<?=$drawingFilename; ?>">
  <?php
}

showFooter();
