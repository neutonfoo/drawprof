<?php
showHeader('Settings');

if(isset($_POST['formSubmit'])) {

  $userSettings = [];
  if(isset($_POST['settings'])) {
    $userSettings = $_POST['settings'];
  }

  $stmt = $conn->prepare("UPDATE drawprof_admins SET settingsJSON = ? WHERE adminId = ?");
  $stmt->execute([json_encode($userSettings), $_SESSION['adminId']]);

  // Need to update settings session
  $_SESSION['adminSettings'] = $userSettings;
}
?>

<div class="row">
  <div class="col">
    <h1><a href="?">AP</a> &bull; Settings</h1>
    <p class="lead">Change your admin settings here.</p>
  </div>
</div>

<form method="POST">
  <?php
    foreach($siteSettings as $settingKey => $settingLabel) {
      ?>
      <div class="row">
        <div class="col">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="<?=$settingKey?>" name="settings[<?=$settingKey?>]" <?php if(getAdminSetting($settingKey)) { ?>checked<?php } ?>>
            <label class="form-check-label" for="<?=$settingKey?>">
              <?=$settingLabel; ?>
            </label>
          </div>
        </div>
      </div>
      <?php
    }
  ?>
  <div class="form-row my-3">
    <div class="form-group col-12">
      <input type="hidden" name="formSubmit" value="1">
      <button type="submit" class="btn btn-primary">Create</button>
    </div>
  </div>
</form>
<?php
showFooter();
