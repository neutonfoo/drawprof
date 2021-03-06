<?php
showHeader('Admin Panel');
?>

<div class="row">
  <div class="col">
    <h1>Admin Panel</h1>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <h2>Submissions</h2>
    <div class="list-group list-group-flush">
      <a href="?page=approvesubmissions" class="list-group-item list-group-item-action">Approve Submissions</a>
      <a href="?page=hallofshame" class="list-group-item list-group-item-action">Hall of Shame</a>
    </div>
  </div>
  <div class="col-md-6">
    <h2>Account</h2>
    <div class="list-group list-group-flush">
      <a href="?page=settings" class="list-group-item list-group-item-action">Settings</a>
    </div>
    <div class="list-group list-group-flush">
      <a href="?page=changepassword" class="list-group-item list-group-item-action">Change Password</a>
    </div>
    <?php if($_SESSION['adminIsSuperAdmin']) {
      ?>
      <div class="list-group list-group-flush">
        <a href="?page=createadminaccount" class="list-group-item list-group-item-action">Create Admin Account</a>
      </div>
      <div class="list-group list-group-flush">
        <a href="?page=manageadminaccounts" class="list-group-item list-group-item-action">Manage Admin Accounts</a>
      </div>
      <?php
    }
    ?>
    <div class="list-group list-group-flush">
      <a href="?page=milestones" class="list-group-item list-group-item-action">Milestones</a>
    </div>
  </div>
</div>
<?php
showFooter();
