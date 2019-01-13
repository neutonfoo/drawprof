<?php
require 'requires/core.php';
showHeader('Drawings');
?>
<p class="text-primary">Filtering options in development.</p>
<?php
require('dbconfig.php');

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT drawingId, publishedDate, isMobile, profName, uniName FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY drawingId DESC");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  ?>
    <p><strong><?=$row['profName']; ?></strong> from <u><?=$row['uniName']; ?></u></p>
    <p>
      Published on <em><?=$row['publishedDate']; ?></em>
      <?php
        if($row['isMobile'] == 'T') {
          ?>
          <small>(mobile)</small>
          <?php
        }
      ?>
    </p>
    <img src="drawings/<?=$row['drawingId'] ?>.png">
  <?php
}

showFooter();
