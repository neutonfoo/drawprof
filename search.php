<?php
require 'requires/core.php';
require 'dbconfig.php';

$query = $_GET['query'];

showHeader($query);
?>
<h1>Search Results for <mark><?=$query; ?></mark></h1>
<h2>Professors</h2>
<?php
  $stmt = $conn->prepare("SELECT drawprof_profs.profId, drawprof_profs.profName, drawprof_profs.profSlug, drawprof_unis.uniName, drawprof_unis.uniSlug FROM drawprof_profs LEFT JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE profName LIKE ? ORDER BY profName ASC");
  $stmt->execute(['%'.$query.'%']);

  if($stmt->rowCount() == 0) {
    ?>
    <p>No results found.</p>
    <?php
  } else {
    ?>
    <div class="list-group">
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $profId = $row['profId'];
      $profName = $row['profName'];
      $profSlug = $row['profSlug'];
      ?>
      <a href="drawing.php?prof=<?=$profId; ?>" class="list-group-item list-group-item-action"><?=$profName; ?></a>
      <?php
    }
    ?>
    </div>
    <?php
  }
?>
<h2>Universities</h2>
<div class="list-group">
<?php
  $stmt = $conn->prepare("SELECT uniId, uniName, uniSlug FROM drawprof_unis WHERE uniName LIKE ? ORDER BY uniName ASC");
  $stmt->execute(['%'.$query.'%']);

  if($stmt->rowCount() == 0) {
    ?>
    <p>No results found.</p>
    <?php
  } else {
    ?>
    <div class="list-group">
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $uniId = $row['uniId'];
      $uniName = $row['uniName'];
      $uniSlug = $row['uniSlug'];
      ?>
      <a href="drawing.php?uni=<?=$uniId?>" class="list-group-item list-group-item-action"><?=$uniName; ?></a>
      <?php
    }
    ?>
  </div>
    <?php
  }
?>
</div>
<?php
showFooter();
