<?php
require 'requires/core.php';

// Not logged in
if(!isset($_SESSION['adminId']) || is_null($_SESSION['adminId'])) {
  showHeader('Login');
  ?>
  <h1>Login</h1>
  <form action="login.php" method="POST">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="adminEmailInput">Email address</label>
        <input type="email" class="form-control" id="adminEmailInput" name="adminEmail" placeholder="Enter email">
      </div>
      <div class="form-group col-md-6">
        <label for="adminPasswordInput">Password</label>
        <input type="password" class="form-control" id="adminPasswordInput" name="adminPassword" placeholder="Password">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-12">
        <input type="hidden" class="form-control" name="loggingIn" value="1">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </div>
  </form>

  <?php
} else {

  showHeader('Admin Panel');

  if(isset($_GET['action'])) {
    require 'dbconfig.php';
    $action = $_GET['action'];

    if($action == "approveposts") {
      if(isset($_POST['formSubmit'])) {

        $drawings = $_POST['drawings'];

        foreach($drawings as $drawingId => $approvalStatus) {
          $stmt = $conn->prepare("UPDATE drawprof_drawings SET approvalStatus = ? WHERE drawingId = ?");
          $stmt->execute([$approvalStatus, $drawingId]);
        }
      }

      ?>
      <h1>Admin Panel &bull; Approve Posts</h1>
      <form method="post">
      <?php
      $stmt = $conn->prepare("SELECT drawingId, profName, profSlug, uniName, uniSlug FROM drawprof_drawings JOIN drawprof_profs ON drawprof_drawings.profId = drawprof_profs.profId JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE drawprof_drawings.approvalStatus = 0");
      $stmt->execute();

      if ($stmt->rowCount() == 0) {
        ?>
        <p>No pending posts!</p>
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

            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
              <div class="card">
                <img src="drawings/<?=$drawingFilename; ?>" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title"><?=$profName; ?></h5>
                  <p class="card-text"> from <u><?=$uniName; ?></u>.</p>
                  <div class="card-body">
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
    } else if($action == "changepassword") {
      ?>
      <h1>Admin Panel &bull; Change Password</h1>
      <?php
    }
  } else {
    ?>
    <h1>Admin Panel</h1>
    <div class="row">
      <div class="col-md-6">
        <h2>Posts</h2>
        <div class="list-group list-group-flush">
          <a href="?action=approveposts" class="list-group-item list-group-item-action">Approve Posts</a>
        </div>
      </div>
      <div class="col-md-6">
        <h2>Account</h2>
        <div class="list-group list-group-flush">
          <a href="?action=changepassword" class="list-group-item list-group-item-action">Change Password</a>
        </div>
      </div>
    </div>
    <?php
  }
}

showFooter();
