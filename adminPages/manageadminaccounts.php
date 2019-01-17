<?php
showHeader('Manage Admin Accounts');

if(isset($_POST['formSubmit'])) {

}
?>
<div class="row">
  <div class="col">
    <h1><a href="?">AP</a> &bull; Manage Admin Accounts</h1>
  </div>
</div>

<table class="table table-responsive-lg">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Date Created</th>
      <th scope="col">Super Admin</th>
      <th scope="col">Parent #</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $stmt = $conn->prepare("SELECT adminId, timeCreated, adminName, email, isSuperAdmin, parentAdminId FROM drawprof_admins ORDER BY adminId ASC");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $adminId = $row['adminId'];
      $timeCreated = $row['timeCreated'];
      $adminName = $row['adminName'];
      $adminEmail = $row['email'];
      $isSuperAdmin = $row['isSuperAdmin'];
      $parentAdminId = $row['parentAdminId'];
      ?>
      <tr id="admin_<?=$adminId; ?>">
        <th scope="row"><?=$adminId; ?></th>
        <td>
          <?=$adminName; ?>
        </td>
        <td><?=$adminEmail; ?></td>
        <td>
          <?php
          if($timeCreated == 0) {
            ?>
            <mark><b>OG ACCOUNT</b></mark>
            <?php
          } else {
            ?>
            <?=parseTimestamp($timeCreated); ?>
            <?php
          }
          ?>
        </td>
        <td>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="adminIsSuperUser[<?=$adminId; ?>]" name="adminIsSuperUser[<?=$adminId; ?>]" <?php if($isSuperAdmin) { ?>checked<?php } ?> disabled>
          </div>
        </td>
        <td>
          <?php
            if($parentAdminId == 0) {
              ?>
              None
              <?php
            } else {
              ?>
              <a href="#admin_<?=$parentAdminId; ?>"><?=$parentAdminId; ?></a>
              <?php
            }
          ?>
        </td>
      </tr>
      <?php
    }
    ?>
  </tbody>
</table>
<?php
showFooter();
