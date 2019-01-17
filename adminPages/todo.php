<?php
showHeader('Todo');

if(isset($_GET['loginFailed'])) {
  ?>
  <div class="alert alert-danger" role="alert">Login failed.</div>
  <?php
}
?>
<h1>Todo</h1>
<ul class="list-group">
  <li class="list-group-item">MORE COLOR OPTIONS</li>
  <li class="list-group-item">Need to fix formatting before anything else</li>
  <li class="list-group-item">Clean URLs</li>
  <li class="list-group-item">Implement Veto</li>
  <li class="list-group-item">Like button</li>
  <li class="list-group-item">Fix search --> click on Uni or Prof</li>
  <li class="list-group-item">Analytics</li>
  <li class="list-group-item">Top and Recent</li>
  <li class="list-group-item">Super Admin Manage Accounts</li>
</ul>
<?php
showFooter();
