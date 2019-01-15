<?php
require 'requires/core.php';
require 'dbconfig.php';

if(isset($_POST['loggingIn'])) {

  $adminId = NULL;
  $adminName = NULL;

  $adminEmail = $_POST['adminEmail'];
  $adminPassword = $_POST['adminPassword'];

  $stmt = $conn->prepare("SELECT adminId, adminName, email FROM drawprof_admins WHERE email = ? AND passwordHash = ?");
  $stmt->execute([$adminEmail, md5($adminPassword)]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $adminId = $row['adminId'];
    $adminName = $row['adminName'];
    $adminEmail = $row['adminEmail'];
  }

  // Successful login
  if(!is_null($adminId)) {
    $_SESSION['adminId'] = $adminId;
    $_SESSION['adminName'] = $adminName;
    $_SESSION['adminEmail'] = $adminEmail;
  }

  header("Location: admin.php");
} else {
  header("Location: index.php");
}
//
// $stmt = $conn->prepare("SELECT drawprof_profs.profName, drawprof_profs.profSlug, drawprof_unis.uniName, drawprof_unis.uniSlug FROM drawprof_profs LEFT JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE profName LIKE ? ORDER BY uniName DESC");
// $stmt->execute(['%'.$query.'%']);
//
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//
// }
