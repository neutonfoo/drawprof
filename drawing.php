<?php
require 'config.php';

$drawingId = NULL;

if(isset($_GET['drawing'])) {
  $drawingId = $_GET['drawing'];
}

if(is_null($drawingId) || $drawingId == '') {
  // header("Location: gallery");
} else {

  if(isset($_POST['formSubmit'])) {

    if(isset($_POST['like'])) {
      // Like Button

      if(!in_array($drawingId, $_SESSION['likedDrawings'])) {
        $stmt = $conn->prepare("UPDATE drawprof_drawings SET likes = likes + 1 WHERE drawingId = ?");
        $stmt->execute([$drawingId]);

        $_SESSION['likedDrawings'][] = $drawingId;
      }

    } else if(isset($_POST['drawing_hide'])) {
      // Hide Drawing
      $isHidden = $_POST['drawing_hide'];

      $stmt = $conn->prepare("UPDATE drawprof_drawings SET isHidden = ? WHERE drawingId = ?");
      $stmt->execute([$isHidden, $drawingId]);
    } else {
      $status = $_POST['drawing'];

      $stmt = $conn->prepare("UPDATE drawprof_drawings SET status = ?, statusChangeAdminId = ?, statusChangeTime = ? WHERE drawingId = ?");
      $stmt->execute([$status, $_SESSION['adminId'], time(), $drawingId]);
    }
  }

  // Drawing Spec
  $stmt = $conn->prepare("SELECT drawingId, artist, submittedTime, status, isHidden, statusChangeTime, likes, adminName, email, profRMPId, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId LEFT JOIN drawprof_admins ON drawprof_admins.adminId = drawprof_drawings.statusChangeAdminId WHERE drawprof_drawings.drawingId = ? LIMIT 1");
  $stmt->execute([$drawingId]);

  // if($stmt->rowCount() == 0) {
  //   header("Location: gallery.php");
  // }

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $drawingId = $row['drawingId'];
    $artist = $row['artist'] == '' ? 'anonymous' : $row['artist'];
    $submittedTime = $row['submittedTime'];
    $status = $row['status'];
    $isHidden = $row['isHidden'];
    $likes = $row['likes'];

    $profRMPId = $row['profRMPId'];
    $profName = $row['profName'];
    $profSlug = $row['profSlug'];
    $uniName = $row['uniName'];
    $uniSlug = $row['uniSlug'];

    $adminName = $row['adminName'];
    $adminEmail = $row['email'];
    $statusChangeTime = $row['statusChangeTime'];

    $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

    $link =  "$base_url/$uniSlug/$profSlug/$drawingId";

    // Constructing Meta Tags
    $title = "$profName by $artist";
    $meta = [];

    $meta['og:url'] = [];
    $meta['og:url']['content'] = $link;

    $meta['og:type'] = [];
    $meta['og:type']['content'] = "article";

    $meta['og:title'] = [];
    $meta['og:title']['content'] = $title;

    // Can't preview unwholesome posts
    if($status != 3) {
      $meta['og:description'] = [];
      $meta['og:description']['content'] = "A drawing of $profName from $uniName by $artist! Show off your artistic and creative abilities! Draw your college professors on DrawYourProfessors!";

      $meta['og:image'] = [];
      $meta['og:image']['content'] = "$base_url/drawings/$drawingFilename";
    }

    // Since only 1, safe to do this
    showHeader($title, $meta);

    if($status == 1) {
      // If Approved, redirect to clean URL
    } else if($status == 0) {
      ?>
      <div class="alert alert-warning" role="alert">This submission is pending approval.</div>
      <div class="alert alert-warning" role="alert">Use this link to check on its status: <a href="<?=$link; ?>"><?=$link; ?></a>.</div>
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
        <div class="col-12 text-center">
          <h2 class="profName">
            <a href="<?=$base_url;  ?>/<?=$uniSlug; ?>/<?=$profSlug; ?>" class="text-dark">
              <?=$profName; ?>
            </a>
          </h2>
          <h3 class="uniName">
            <a href="<?=$base_url;  ?>/<?=$uniSlug; ?>" class="text-secondary">
              <?=$uniName; ?>
            </a>
          </h3>
        </div>
      </div>

      <!-- Canvas Container -->
      <div class="row">
        <div class="col-12 text-center">
          <div class="drawingContainer">
            <img class="d" src="<?=$base_url?>/drawings/<?=$drawingFilename; ?>">
          </div>
        </div>
      </div>

      <!-- Artist -->
      <div class="row">
        <div class="col-12 text-center pt-3">
          <blockquote class="blockquote">
            <footer class="blockquote-footer"><?=$artist; ?></footer>
          </blockquote>
        </div>
      </div>

      <!-- Submitted Time -->
      <div class="row">
        <div class="col-12 text-center">
          <p><small>Submitted On: <u><em><?=parseTimestamp($submittedTime); ?></em></u>.</small></p>
        </div>
      </div>

      <?php

      // Can only like approved submissions
      if($status == 1) {
        ?>
        <!-- Like Button -->
        <form method="post">
          <div class="row">
            <div class="col-12 text-center">
              <small class="text-dark"><?=$likes; ?> Like<?php if($likes != 1) { ?>s<?php } ?></small>
              <?php
              if(in_array($drawingId, $_SESSION['likedDrawings'])) {
                ?>
                <button type="button" class="btn btn-secondary btn-sm align-baseline p-1" disabled>Already Liked</button>
                <?php
              } else {
                ?>
                <input type="hidden" name="like" value="1">
                <input type="hidden" name="formSubmit" value="1">
                <button type="submit" class="btn btn-primary btn-sm align-baseline p-1">Like</button>
              </form>
              <?php
            }
            ?>
          </div>
        </div>
        <?php
      }

      if(isSuperAdmin()) {
        if($status != 0) {
          ?>
          <!-- Status Change -->
          <div class="row">
            <div class="col-12 text-center">
              <p>
                <small>
                  <?php
                  if($status == 1) {
                    ?>
                    Approved By:
                    <?php
                  } else if($status == 2) {
                    ?>
                    Rejected By:
                    <?php
                  } else if($status == 3) {
                    ?>
                    Marked as <mark>unwholesome</mark> By:
                    <?php
                  }
                  ?>
                  <b><?=$adminName; ?></b> (<?=$adminEmail; ?>) on <u><em><?=parseTimestamp($statusChangeTime); ?></u></em>.
                </small>
              </p>
            </div>
          </div>
          <?php

          if($status == 2 || $status == 3) {
            ?>
            <hr>
            <form id="hideForm" method="post">
              <div class="form-row">
                <div class="col-12 text-center">
                  <p class="h4">Hide</p>
                  <p>Only rejected or <mark>unwholesome</mark> posts can be hidden.</p>
                </div>
              </div>
              <div class="form-row">
                <div class="col-12 text-center">
                  <div class="form-check">
                    <input class="form-check-input" type="hidden" name="drawing_hide" value="0">
                    <input class="form-check-input" type="checkbox" id="drawings_<?=$drawingId; ?>_hide" name="drawing_hide" <?php if($isHidden) { ?>checked<?php }?> value="1">
                    <label class="form-check-label" for="drawings_<?=$drawingId; ?>_hide" name="drawing_hide">Hide</label>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-12 text-center">
                  <div class="form-check form-check-inline">
                    <input type="hidden" name="formSubmit" value="1">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </div>
            </form>
            <?php
          }
          ?>
          <hr>
          <form id="vetoForm" method="post">
            <div class="form-row">
              <div class="col-12 text-center">
                <p class="h4">Veto</p>
              </div>
            </div>
            <div class="form-row">
              <div class="col-12 text-center">
                <div class="form-check form-check-inline">
                  <input class="form-check-input drawings_<?=$drawingId; ?>" type="radio" name="drawing" id="drawings_<?=$drawingId; ?>_approve" value="1" <?php if($status == 1) { ?>disabled<?php }?>>
                  <label class="form-check-label" for="drawings_<?=$drawingId; ?>_approve">Approve</label>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-12 text-center">
                <div class="form-check form-check-inline">
                  <input class="form-check-input drawings_<?=$drawingId; ?>" type="radio" name="drawing" id="drawings_<?=$drawingId; ?>_reject" value="2"<?php if($status == 2) { ?>disabled<?php }?>>
                  <label class="form-check-label" for="drawings_<?=$drawingId; ?>_reject">Reject</label>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-12 text-center">
                <div class="form-check form-check-inline">
                  <input class="form-check-input drawings_<?=$drawingId; ?>" type="radio" name="drawing" id="drawings_<?=$drawingId; ?>_pending" value="0"<?php if($status == 0) { ?>disabled<?php }?>>
                  <label class="form-check-label" for="drawings_<?=$drawingId; ?>_pending">Pending</label>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-12 text-center">
                <div class="form-check form-check-inline">
                  <input class="form-check-input drawings_<?=$drawingId; ?>" type="radio" name="drawing" id="drawings_<?=$drawingId; ?>_unwholesome" value="3" <?php if($status == 3) { ?>disabled<?php }?>>
                  <label class="form-check-label" for="drawings_<?=$drawingId; ?>_unwholesome">Unwholesome</label>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-12 text-center">
                <div class="form-check form-check-inline">
                  <input type="hidden" name="formSubmit" value="1">
                  <button type="button" id="vetoButton" class="btn btn-primary">Veto</button>
                  <script type="text/javascript">
                    $(document).ready(function() {
                      $vetoForm = $('#vetoForm')
                      $vetoButton = $('#vetoButton')

                      $vetoButton.on('click', function() {
                        if ($(".drawings_<?=$drawingId; ?>:checked").length == 0) {
                          alert('Click a status to veto this submission.')
                        } else {
                          $vetoForm.submit()
                        }
                      })
                    })
                  </script>
                </div>
              </div>
            </div>
          </form>
          <?php
        }
      }
    }
  }
}

showFooter();
