<?php
require 'config.php';

// Initialize Core 2 Page Variables
$filter = "sort";
$page = 1;

// Initialize Variables from GET
$sort = isset($_GET['sort']) ? $_GET['sort'] : NULL;
$page = isset($_GET['page']) ? $_GET['page'] : NULL;
$uniId = isset($_GET['uni']) ? $_GET['uni'] : NULL;
$profId = isset($_GET['prof']) ? $_GET['prof'] : NULL;
$drawingId = isset($_GET['drawing']) ? $_GET['drawing'] : NULL;

// Reset page if < 1
if($page < 1) {
  $page = 1;
}

// Drawings Offset for SQL
$offset = ($page - 1) * $posts_per_page;
$postsToLoad = $posts_per_page + 1;

// Prepare SQL Statements
if(!is_null($sort)) {
  // If sort
  $filter = "sort";

  $stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status = 1 AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  $stmt->bindParam(1, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam(2, $offset, PDO::PARAM_INT);
} else if(!is_null($uniId)) {
  // If uni
  $filter = "uni";

  // $stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, profName, profSlug, uniName, uniSlug FROM drawprof_drawings INNER JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId INNER JOIN drawprof_unis ON drawprof_unis.uniId = ? LIMIT ? OFFSET ?");
  $stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_unis INNER JOIN drawprof_profs ON drawprof_profs.uniId = drawprof_unis.uniId INNER JOIN drawprof_drawings ON drawprof_drawings.profId = drawprof_profs.profId WHERE drawprof_unis.uniId = ? AND drawprof_drawings.status = 1 ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  $stmt->bindParam(1, $uniId, PDO::PARAM_INT);
  $stmt->bindParam(2, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam(3, $offset, PDO::PARAM_INT);
} else if(!is_null($profId)) {
  // If prof
  $filter = "prof";

  $stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings INNER JOIN drawprof_profs ON drawprof_profs.profId = drawprof_drawings.profId INNER JOIN drawprof_unis ON drawprof_unis.uniId = drawprof_profs.uniId WHERE drawprof_drawings.profId = ? AND drawprof_drawings.status = 1 ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  $stmt->bindParam(1, $profId, PDO::PARAM_INT);
  $stmt->bindParam(2, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam(3, $offset, PDO::PARAM_INT);
} else {
  // Display all by recent by default
  $filter = "sort";

  $stmt = $conn->prepare("SELECT drawingId, submittedTime, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status = 1 AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  $stmt->bindParam(1, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam(2, $offset, PDO::PARAM_INT);
}

// To see if there's one extra to load next pagniation

$stmt->execute();

$numberOfPosts = $stmt->rowCount();
// No posts on page, only possible if URL is manually changed
if($numberOfPosts == 0) {
  showHeader('Gallery');

  ?>
  <div class="row">
    <div class="col">
      <h1>No results.</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      No posts on this page!
    </div>
  </div>
  <?php
} else {
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

      $profId = $row['profId'];
      $profName = $row['profName'];
      $profSlug = $row['profSlug'];

      $uniId = $row['uniId'];
      $uniName = $row['uniName'];
      $uniSlug = $row['uniSlug'];

      $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

      // If first post, load header
      if($postCount == 1) {

        $title = "";

        if($filter == "sort") {
          $title = "Recent";
        } else if($filter == "uni") {
          $title = $uniName;
        } else if($filter == "prof") {
          $title = $profName;
        }

        showHeader($title);
        ?>
        <div class="row">
          <div class="col">
            <h1><?=$title; ?></h1>
          </div>
        </div>
        <div class="form-row">
        <?php
      }
      ?>
      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
        <div class="card my-2">
          <a href="<?=$base_url . "drawing.php?drawing=$drawingId"; ?>">
            <img src="drawings/<?=$drawingFilename; ?>" class="card-img-top border-bottom" alt="...">
          </a>
          <div class="card-body">
            <h5 class="card-title">
              <a href="<?=$base_url;  ?>gallery.php?prof=<?=$profId; ?>" class="text-secondary"><?=$profName; ?>
              </a>
            </h5>
            <small class="text-muted">
              <a href="<?=$base_url;  ?>gallery.php?uni=<?=$uniId; ?>" class="text-dark">
                <?=$uniName; ?>
              </a>
            </small>
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
