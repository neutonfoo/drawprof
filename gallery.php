<?php
require 'requires/core.php';
require 'dbconfig.php';

$page = 1;

if(isset($_GET['page'])) {
  $page = $_GET['page'];

  if($page < 1) {
    $page = 1;
  }
}

// $totalApprovedPosts =

$offset = ($page - 1) * $posts_per_page;

showHeader('Gallery');
?>
<h1>Gallery</h1>
<?php

// To see if there's one extra to load next pagniation
$postsToLoad = $posts_per_page + 1;

$stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, profName, profSlug, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status = 1 AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY drawingId DESC LIMIT ? OFFSET ?");
$stmt->bindParam(1, $postsToLoad, PDO::PARAM_INT);
$stmt->bindParam(2, $offset, PDO::PARAM_INT);
$stmt->execute();

$numberOfPosts = $stmt->rowCount();

// No posts on page, only possible if URL is manually changed
if($numberOfPosts == 0) {
  ?>
  <div class="row">
    <div class="col-12">
      No posts on this page!
    </div>
  </div>
  <?php
} else {
  ?>
  <div class="form-row">
  <?php
  $postCount = 0;
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    // So the extra 1 post doesn't load
    if($postCount == $posts_per_page) {
      break;
    }

    $postCount++;

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
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <?php
      if($page > 1) {
        ?>
        <li class="page-item"><a class="page-link" href="?page=<?=($page - 1); ?>"><?=($page - 1); ?></a></li>
        <?php
      }
    ?>
    <li class="page-item active"><a class="page-link" href="?page=<?=$page; ?>"><?=$page; ?></a></li>
    <?php
      // There's an extra post, so allow next page
      if($numberOfPosts > $posts_per_page) {
        ?>
        <li class="page-item"><a class="page-link" href="?page=<?=($page + 1); ?>"><?=($page + 1); ?></a></li>
        <?php
      }
    ?>
  </ul>
</nav>
  <?php
}

showFooter();
