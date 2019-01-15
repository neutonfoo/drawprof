<?php
showHeader('Admin Panel');
?>
<div class="alert alert-success" role="alert">Logged in as <strong><?=$_SESSION['adminName']; ?></strong> (<?=$_SESSION['adminEmail']; ?>).</div>
<h1>Admin Panel</h1>
<div class="row">
  <div class="col-md-6">
    <h2>Posts</h2>
    <div class="list-group list-group-flush">
      <a href="?page=approvesubmissions" class="list-group-item list-group-item-action">Approve Submissions</a>
      <a href="?page=hallofshame" class="list-group-item list-group-item-action">Hall of Shame</a>
    </div>
  </div>
  <div class="col-md-6">
    <h2>Account</h2>
    <div class="list-group list-group-flush">
      <a href="?page=changepassword" class="list-group-item list-group-item-action">Change Password</a>
    </div>
    <?php if($_SESSION['adminIsSuperAdmin']) {
      ?>
      <div class="list-group list-group-flush">
        <a href="?page=createadminaccount" class="list-group-item list-group-item-action">Create Admin Account</a>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<?php
showFooter();
