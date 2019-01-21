<?php
showHeader('Create Admin Account');

if(isset($_POST['formSubmit'])) {

  $adminName = $_POST['adminName'];
  $adminEmail = $_POST['adminEmail'];
  $adminPassword = $_POST['adminPassword'];
  $adminIsSuperAdminInput = 0;

  if(isset($_POST['adminIsSuperAdminInput'])) {
    $adminIsSuperAdminInput = 1;
  }

  $stmt = $conn->prepare("INSERT INTO drawprof_admins (timeCreated, adminName, email, passwordHash, isSuperAdmin, parentAdminId, settingsJSON) VALUES (?,?,?,?,?,?,'')");
  $stmt->execute([time(), $adminName, $adminEmail, md5($adminPassword), $adminIsSuperAdminInput, $_SESSION['adminId']]);

  ?>
  <div class="alert alert-success" role="alert">
    <u><?php if($adminIsSuperAdminInput == 1) { ?> Super <?php } ?>Admin</u> <strong><?=$adminName; ?></strong> (<?=$adminEmail; ?>) created.
  </div>
  <?php
}
?>

<div class="row">
  <div class="col">
    <h1><a href="?">AP</a> &bull; Create Admin Account</h1>
    <p class="lead">Please only create admin accounts to people you trust. Admins will be able to approve or reject submissions.</p>
  </div>
</div>

<div class="row">
  <div class="col">
    <form method="POST">
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="adminNameInput">Full Name</label>
          <input type="text" class="form-control" id="adminNameInput" name="adminName" placeholder="Enter name">
        </div>
      </div>
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
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="adminIsSuperAdminInput" name="adminIsSuperAdminInput" disabled>
            <label class="form-check-label" for="adminIsSuperAdminInput">
              Make Super Admin
            </label>
          </div>
        </div>
      </div>
      <div class="form-row my-3">
        <div class="form-group col-12">
          <input type="hidden" name="formSubmit" value="1">
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php
showFooter();
