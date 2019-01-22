<?php
require 'config.php';

$query = "";

if(isset($_GET['q'])) {
  $query = $_GET['q'];
}

if(strlen($query) < 3) {
  header("Location: $base_url/gallery?searcherror=1");
}

$meta['og:url'] = [];
$meta['og:url']['content'] = "$base_url/search";

$meta['og:type'] = [];
$meta['og:type']['content'] = "article";

$meta['og:title'] = [];
$meta['og:title']['content'] = "Search DrawYourProfessors";

$meta['og:description'] = [];
$meta['og:description']['content'] = "Show off your artistic and creative abilities! Draw your college professors on DrawYourProfessors!";

showHeader($query, $meta);

?>


<div class="row justify-content-center">
  <div class="col-md-8">
    <h1>Search Results for&nbsp;<mark><?=$query; ?></mark></h1>
    <p class="lead">Search results have been limited to 20 results.</p>
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
  $stmt = $conn->prepare("SELECT drawprof_profs.profName, drawprof_profs.profSlug, drawprof_unis.uniName, drawprof_unis.uniSlug FROM drawprof_profs INNER JOIN drawprof_unis ON drawprof_profs.uniId = drawprof_unis.uniId WHERE profName LIKE ? ORDER BY profName ASC LIMIT 20");
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
        $uniName = $row['uniName'];
        $uniSlug = $row['uniSlug'];
        $profName = $row['profName'];
        $profSlug = $row['profSlug'];
        ?>
        <a href="<?=$base_url; ?>/<?=$uniSlug; ?>/<?=$profSlug; ?>" class="list-group-item list-group-item-action"><?=$profName; ?></a>
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
  $stmt = $conn->prepare("SELECT uniName, uniSlug FROM drawprof_unis WHERE uniName LIKE ? ORDER BY uniName ASC LIMIT 10");
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
        $uniName = $row['uniName'];
        $uniSlug = $row['uniSlug'];
        ?>
        <a href="<?=$base_url?>/<?=$uniSlug?>" class="list-group-item list-group-item-action"><?=$uniName; ?></a>
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
