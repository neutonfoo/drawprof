<?php
showHeader('Change Password');

if(isset($_POST['formSubmit'])) {
  $adminPassword1 = $_POST['adminPassword1'];
  $adminPassword2 = $_POST['adminPassword2'];

  if($adminPassword1 == "" || $adminPassword2 == "") {
    ?>
    <div class="alert alert-danger" role="alert">Password fields cannot be empty.</div>
    <?php
  } else if($adminPassword1 != $adminPassword2) {
    ?>
    <div class="alert alert-danger" role="alert">Passwords do not match.</div>
    <?php
  } else {
    $stmt = $conn->prepare("UPDATE drawprof_admins SET passwordHash = ? WHERE adminId = ?");
    $stmt->execute([md5($adminPassword1), $_SESSION['adminId']]);
    ?>
    <div class="alert alert-success" role="alert">Password successfully changed.</div>
    <?php
  }
}
?>

<div class="row">
  <div class="col">
    <h1><a href="?">AP</a> &bull; Change Password</h1>
  </div>
</div>


<form method="POST">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="adminPasswordInput1">New Password</label>
      <input type="password" class="form-control" id="adminPasswordInput1" name="adminPassword1" placeholder="Password">
    </div>
    <div class="form-group col-md-6">
      <label for="adminPasswordInput2">Confirm Password</label>
      <input type="password" class="form-control" id="adminPasswordInput2" name="adminPassword2" placeholder="Confirm Password">
    </div>
  </div>
  <div class="form-row my-3">
    <div class="form-group col-12">
      <input type="hidden" name="formSubmit" value="1">
      <button type="submit" class="btn btn-primary">Change Password</button>
    </div>
  </div>
</form>
<?php
showFooter();
