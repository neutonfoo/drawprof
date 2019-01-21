<?php
require 'config.php';

// Initialize Core 2 Page Variables
$filter = "sort";
$page = 1;

// Initialize Variables from GET
$sort = isset($_GET['sort']) ? $_GET['sort'] : "new";
$page = isset($_GET['page']) ? $_GET['page'] : NULL;
$uniSlug = isset($_GET['uni']) ? $_GET['uni'] : NULL;
$profSlug = isset($_GET['prof']) ? $_GET['prof'] : NULL;
$drawingId = isset($_GET['drawing']) ? $_GET['drawing'] : NULL;

// Reset page if < 1
if($page < 1) {
  $page = 1;
}

// Defaulting Meta Tags
$meta = [];

$meta['og:type'] = [];
$meta['og:type']['content'] = "article";

// Drawings Offset for SQL
$offset = ($page - 1) * $posts_per_page;
$postsToLoad = $posts_per_page + 1;

$inQuery = "";
$displaySubmissionStatuses = [];

$displaySubmissionStatuses[] = 1;

if(isAdmin()) {
  $displaySubmissionStatuses[] = 0;

  if(getAdminSetting('showRejectedPostsInGalleryViews')) {
    $displaySubmissionStatuses[] = 2;
  }
  if(getAdminSetting('showUnwholesomePostsInGalleryViews')) {
    $displaySubmissionStatuses[] = 3;
  }
}

$numberOfSubmissionStatuses = sizeof($displaySubmissionStatuses);
$inQuery = implode(',', array_fill(0, count($displaySubmissionStatuses), '?'));

// Prepare SQL Statements
if(!is_null($profSlug)) {
  // If prof
  $filter = "prof";

  if(getAdminSetting('showHiddenPostsInGalleryViews')) {
    $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_profs INNER JOIN drawprof_unis ON drawprof_unis.uniId = drawprof_profs.uniId INNER JOIN drawprof_drawings ON drawprof_drawings.profId = drawprof_profs.profId WHERE drawprof_profs.profSlug = ? AND drawprof_drawings.status IN ($inQuery) ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  } else {
    $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_profs INNER JOIN drawprof_unis ON drawprof_unis.uniId = drawprof_profs.uniId INNER JOIN drawprof_drawings ON drawprof_drawings.profId = drawprof_profs.profId WHERE drawprof_profs.profSlug = ? AND drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.isHidden = 0 ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  }

  // Creating Prof Meta Tags
  $meta['og:url'] = [];
  $meta['og:url']['content'] = "$base_url/$uniSlug/$profSlug";

  $stmt->bindParam(1, $profSlug, PDO::PARAM_STR);
  foreach($displaySubmissionStatuses as $parameterId => $submissionStatus) {
    $stmt->bindValue(($parameterId + 2), $submissionStatus, PDO::PARAM_INT);
  }

  $stmt->bindParam($numberOfSubmissionStatuses + 2, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam($numberOfSubmissionStatuses + 3, $offset, PDO::PARAM_INT);
} else if(!is_null($uniSlug)) {
  // If uni
  $filter = "uni";

  if(getAdminSetting('showHiddenPostsInGalleryViews')) {
    $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_unis INNER JOIN drawprof_profs ON drawprof_profs.uniId = drawprof_unis.uniId INNER JOIN drawprof_drawings ON drawprof_drawings.profId = drawprof_profs.profId WHERE drawprof_unis.uniSlug = ? AND drawprof_drawings.status IN ($inQuery) ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  } else {
    $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_unis INNER JOIN drawprof_profs ON drawprof_profs.uniId = drawprof_unis.uniId INNER JOIN drawprof_drawings ON drawprof_drawings.profId = drawprof_profs.profId WHERE drawprof_unis.uniSlug = ? AND drawprof_drawings.status AND drawprof_drawings.isHidden = 0 IN ($inQuery) ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
  }

  // Creating Uni Meta Tags
  $meta['og:url'] = [];
  $meta['og:url']['content'] = "$base_url/$uniSlug";

  $stmt->bindParam(1, $uniSlug, PDO::PARAM_STR);

  foreach($displaySubmissionStatuses as $parameterId => $submissionStatus) {
    $stmt->bindValue(($parameterId + 2), $submissionStatus, PDO::PARAM_INT);
  }

  $stmt->bindParam($numberOfSubmissionStatuses + 2, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam($numberOfSubmissionStatuses + 3, $offset, PDO::PARAM_INT);
} else {
  // Display all by new by default
  $filter = "sort";

  if($sort == "new") {
    if(getAdminSetting('showHiddenPostsInGalleryViews')) {
      $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
    } else {
      $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId AND drawprof_drawings.isHidden = 0 ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
    }
  } else if($sort == "top") {
    if(getAdminSetting('showHiddenPostsInGalleryViews')) {
      $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY likes DESC LIMIT ? OFFSET ?");
    } else {
      $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId AND drawprof_drawings.isHidden = 0 ORDER BY likes DESC LIMIT ? OFFSET ?");
    }
  } else {
    // Default to new
    if(getAdminSetting('showHiddenPostsInGalleryViews')) {
      $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
    } else {
      $stmt = $conn->prepare("SELECT drawingId, submittedTime, status, isMobile, drawprof_profs.profId, profName, profSlug, drawprof_unis.uniId, uniName, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status IN ($inQuery) AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId AND drawprof_drawings.isHidden = 0 ORDER BY submittedTime DESC LIMIT ? OFFSET ?");
    }
  }

  // Creating Sort Meta Tags
  $meta['og:url'] = [];
  $meta['og:url']['content'] = "$base_url/gallery/";

  foreach($displaySubmissionStatuses as $parameterId => $submissionStatus) {
    $stmt->bindValue(($parameterId + 1), $submissionStatus, PDO::PARAM_INT);
  }

  $stmt->bindParam($numberOfSubmissionStatuses + 1, $postsToLoad, PDO::PARAM_INT);
  $stmt->bindParam($numberOfSubmissionStatuses + 2, $offset, PDO::PARAM_INT);
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

      $profName = $row['profName'];
      $profSlug = $row['profSlug'];

      $uniName = $row['uniName'];
      $uniSlug = $row['uniSlug'];

      $status = $row['status'];

      $cardBody = "";
      $cardBorder = "";

      if(isAdmin()) {
        if($status == 0) {
          // $cardBody = "bg-dark";
          $cardBorder = "border-dark";
        } else if($status == 2) {
          // $cardBody = "bg-warning";
          $cardBorder = "border-warning";
        } else if($status == 3) {
          // $cardBody = "bg-danger";
          $cardBorder = "border-danger";
        }
      }

      $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

      // If first post, load header
      if($postCount == 1) {

        $title = "";

        if($filter == "sort") {

          if($sort == "new") {
            $title = "New";

            $meta['og:title'] = [];
            $meta['og:title']['content'] = "New Drawings of Professors!";

            $meta['og:description'] = [];
            $meta['og:description']['content'] = "New Drawings of Professors! Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

          } else if($sort == "top") {
            $title = "Top";

            $meta['og:title'] = [];
            $meta['og:title']['content'] = "Top Drawings of Professors!";

            $meta['og:description'] = [];
            $meta['og:description']['content'] = "Top Drawings of Professors! Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

          } else {
            // Default to New

            $meta['og:title'] = [];
            $meta['og:title']['content'] = "New Drawings of Professors!";

            $meta['og:description'] = [];
            $meta['og:description']['content'] = "New Drawings of Professors! Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

          }

        } else if($filter == "uni") {
          $title = $uniName;

          $meta['og:title'] = [];
          $meta['og:title']['content'] = "Drawings of Professors from $uniName!";

          $meta['og:description'] = [];
          $meta['og:description']['content'] = "Drawings of Professors from $uniName! Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

        } else if($filter == "prof") {
          $title = $profName;

          $meta['og:title'] = [];
          $meta['og:title']['content'] = "Drawings of $profName from $uniName!";

          $meta['og:description'] = [];
          $meta['og:description']['content'] = "Drawings of $profName from $uniName! Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

        }

        // If link is shared, only public/approved link will show so first approved drawing will show
        $meta['og:image'] = [];
        $meta['og:image']['content'] = "$base_url/drawings/$drawingFilename";

        showHeader($title, $meta);

        if(isset($_GET['searcherror'])) {
          ?>
          <div class="alert alert-warning" role="alert">
            Your search query has to be at least 5 characters.
          </div>
          <?php
        }

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
        <div class="card my-2 <?=$cardBody?> <?=$cardBorder?>">
          <a href="<?=$base_url;  ?>/<?=$uniSlug; ?>/<?=$profSlug; ?>/<?=$drawingId; ?>">
            <img src="<?=$base_url; ?>/drawings/<?=$drawingFilename; ?>" class="card-img-top border-bottom" alt="...">
          </a>
          <div class="card-body">
            <h5 class="card-title">
              <a href="<?=$base_url;  ?>/<?=$uniSlug; ?>/<?=$profSlug; ?>" class="text-dark"><?=$profName; ?></a>
            </h5>
            <small class="text-muted">
              <a href="<?=$base_url;  ?>/<?=$uniSlug; ?>" class="text-secondary"><?=$uniName; ?></a>
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
