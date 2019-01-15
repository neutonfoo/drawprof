<?php
require 'requires/core.php';
require 'dbconfig.php';

showHeader('About');

$uniSlug = NULL;
$profSlug = NULL;
$drawingId = NULL;

if(isset($_GET['uni'])) {
  $uniSlug = $_GET['uni'];
}

if(isset($_GET['prof'])) {
  $profSlug = $_GET['prof'];
}

if(isset($_GET['drawing'])) {
  $drawingId = $_GET['drawing'];
}

// Specific Drawing
if(!is_null($drawingId)) {
  $stmt = $conn->prepare("SELECT drawingId, approvalStatus, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE drawprof_drawings.drawingId = ?");
  $stmt->execute([$drawingId]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $drawingId = $row['drawingId'];
    $approvalStatus = $row['approvalStatus'];

    $profName = $row['profName'];
    $profSlug = $row['profSlug'];
    $uniName = $row['uniName'];
    $uniSlug = $row['uniSlug'];

    $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

    $link = $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    if($approvalStatus == 0) {
      ?>
      <div class="alert alert-dark" role="alert">This post is pending approval.</div>
      <div class="alert alert-dark" role="alert">Use this link to check on its status: <a href="<?=$link; ?>"><?=$link; ?></a>.</div>
      <?php
    } else if($approvalStatus == 2) {
      ?>
      <div class="alert alert-danger" role="alert">This post has been rejected.</div>
      <?php
    }
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
    <?php
  }
}
?>
<?php
showFooter();
