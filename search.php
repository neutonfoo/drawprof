<?php
require 'requires/core.php';
require 'dbconfig.php';

$query = $_GET['query'];

showHeader($query);
?>


<div class="row justify-content-center">
  <div class="col-md-8">
    <h1>Search Results for <mark><?=$query; ?></mark></h1>
    <p class="lead">Search results have been limited to 10 results during <b>Closed Beta</b>.</p>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-8 my-2">
    <h2>Professors</h2>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-8">

  <?php
  $stmt = $conn->prepare("SELECT drawprof_profs.profId, drawprof_profs.profName, drawprof_profs.profSlug, drawprof_unis.uniName, drawprof_unis.uniSlug FROM drawprof_profs INNER JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE profName LIKE ? ORDER BY profName ASC LIMIT 10");
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
        <a href="<?=$base_url; ?>gallery.php?prof=<?=$profId; ?>" class="list-group-item list-group-item-action"><?=$profName; ?></a>
        <?php
      }
    ?>
    </div>
    <?php
  }
  ?>
  </div>
</div>
<div class="row justify-content-center">
  <div class="col-md-8 my-2">
    <h2>Universities</h2>
  </div>
</div>
<div class="row justify-content-center">
  <div class="col-md-8">
  <?php
  $stmt = $conn->prepare("SELECT uniId, uniName, uniSlug FROM drawprof_unis WHERE uniName LIKE ? ORDER BY uniName ASC LIMIT 10");
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
        <a href="gallery.php?uni=<?=$uniId?>" class="list-group-item list-group-item-action"><?=$uniName; ?></a>
        <?php
      }
      ?>
    </div>
    <?php
  }
  ?>
  </div>
</div>
<?php
showFooter();
