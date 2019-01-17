<?php
showHeader('Login');

if(isset($_GET['loginFailed'])) {
  ?>
  <div class="alert alert-danger" role="alert">Login failed.</div>
  <?php
}
?>
<div class="row">
  <div class="col">
    <h1>Login</h1>
  </div>
</div>
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
showFooter();
