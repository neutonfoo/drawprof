<?php
require 'requires/core.php';

if(isset($_GET['logout'])) {
  // Logging out

  unset($_SESSION['adminId']);
  unset($_SESSION['adminName']);
  unset($_SESSION['adminEmail']);

  header("Location: index.php?logout=1");
} else if(isset($_POST['loggingIn'])) {
  // Logging in

  require 'dbconfig.php';

  $adminId = NULL;
  $adminName = NULL;

  $adminEmail = $_POST['adminEmail'];
  $adminPassword = $_POST['adminPassword'];

  $stmt = $conn->prepare("SELECT adminId, adminName, email, isSuperAdmin FROM drawprof_admins WHERE email = ? AND passwordHash = ?");
  $stmt->execute([$adminEmail, md5($adminPassword)]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $adminId = $row['adminId'];
    $adminName = $row['adminName'];
    $adminEmail = $row['email'];
    $adminIsSuperAdmin = $row['isSuperAdmin'];
  }

  if(is_null($adminId)) {
    // Unsuccessful login

    header("Location: admin.php?loginFailed=1");

  } else {
    // Successful login

    $_SESSION['adminId'] = $adminId;
    $_SESSION['adminName'] = $adminName;
    $_SESSION['adminEmail'] = $adminEmail;
    $_SESSION['adminIsSuperAdmin'] = $adminIsSuperAdmin;

    header("Location: admin.php?loginSuccessful=1");

  }
} else {
  // Other access

  header("Location: index.php");
}

//
// $stmt = $conn->prepare("SELECT drawprof_profs.profName, drawprof_profs.profSlug, drawprof_unis.uniName, drawprof_unis.uniSlug FROM drawprof_profs LEFT JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE profName LIKE ? ORDER BY uniName DESC");
// $stmt->execute(['%'.$query.'%']);
//
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//
// }
