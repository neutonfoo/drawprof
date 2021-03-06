<?php
require 'config.php';

// Constructing Meta Tags
$title = "Draw Your Professors!";
$meta = [];

$meta['og:url'] = [];
$meta['og:url']['content'] = $base_url;

$meta['og:type'] = [];
$meta['og:type']['content'] = "article";

$meta['og:title'] = [];
$meta['og:title']['content'] = $title;

$meta['og:description'] = [];
$meta['og:description']['content'] = "Show off your artistic and creative abilities! Draw your college professors on DrawYourProfessors!";

// Just gonna hard code this for now
$meta['og:image'] = [];
$meta['og:image']['content'] = "$base_url/drawings/university-of-southern-california-yuka-kumagai-2450760-17.png";

/*
// Load Latest Approved Image
$stmt = $conn->prepare("SELECT drawingId, profSlug, uniSlug FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.status = 1 AND drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId ORDER BY submittedTime DESC LIMIT 1");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $drawingId = $row['drawingId'];
  $profSlug = $row['profSlug'];
  $uniSlug = $row['uniSlug'];

  $drawingFilename = "$uniSlug-$profSlug-$drawingId.png";

  $meta['og:image'] = [];
  $meta['og:image']['content'] = "$base_url/drawings/$drawingFilename";
}
*/
// Since only 1, safe to do this
showHeader($title, $meta);

?>
<div class="row">
  <div class="col">
    <div class="alert alert-warning" role="alert">Friendly reminder to keep submissions wholesome!</div>
  </div>
</div>

<?php
  // Error with url through evil means like editting the code and stuff
  if(isset($_GET['linkerror'])) {
    ?>
    <div class="row">
      <div class="col">
        <div class="alert alert-danger" role="alert">There was an error with your link.</div>
      </div>
    </div>
    <?php
  } else if(isset($_GET['sizeerror'])) {
    ?>
    <div class="row">
      <div class="col">
        <div class="alert alert-danger" role="alert">Do not alter the size of the canvas.</div>
      </div>
    </div>
    <?php
  }
?>

<!-- Professor URL -->
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="input-group">
      <input type="text" id="loadProfUrl" class="form-control" placeholder="Professor's Rate My Professors URL" value="">
      <div class="input-group-append">
        <button type="button" id="loadProfButton" class="btn btn-outline-secondary">Load</button>
      </div>
    </div>
  </div>
</div>

<!-- Professor Meta Container -->
<div class="row justify-content-center">
  <div class="col-12 text-center">
    <h2 class="profName">&nbsp;</h2>
    <h3 class="uniName">&nbsp;</h3>
  </div>
</div>

<!-- Canvas Container -->
<div class="row">
  <div class="col-12 text-center">
    <div class="canvasContainer">
      <canvas id="c"></canvas>
    </div>
  </div>
</div>

<?php

// To restore image in case of error
if(isset($_SESSION['tempSavedDrawing'])) {
  ?>
  <script type="text/javascript">
    var loadFromTemp = true;
    $(document).ready(function() {
      var canvas = document.getElementById('c');

      function drawDataURIOnCanvas(imageDataURI) {
        "use strict";
        var img = new window.Image();
        img.addEventListener("load", function () {
          canvas.getContext("2d").drawImage(img, 0, 0);
        });
        img.setAttribute("src", imageDataURI);
      }

      drawDataURIOnCanvas("<?=$_SESSION['tempSavedDrawing']; ?>");
    });
  </script>
  <?php
  unset($_SESSION['tempSavedDrawing']);
} else {
  ?>
  <script type="text/javascript">
    var loadFromTemp = false;
  </script>
  <?php
}
?>

<!-- Stroke Size Container -->
<div class="row">
  <div class="col-12 my-3 text-center">
    <div class="form-check form-check-inline">
      <input class="form-check-input canvasStrokeSizeRadio" name="canvasStrokeSizeRadio" type="radio" id="canvasStrokeSizeRadioThin" value="2">
      <label class="form-check-label" for="canvasStrokeSizeRadioThin">Thin</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input canvasStrokeSizeRadio" name="canvasStrokeSizeRadio" type="radio" id="canvasStrokeSizeRadioNormal" value="5" checked>
      <label class="form-check-label" for="canvasStrokeSizeRadioNormal">Normal</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input canvasStrokeSizeRadio" name="canvasStrokeSizeRadio" type="radio" id="canvasStrokeSizeRadioThick" value="10">
      <label class="form-check-label" for="canvasStrokeSizeRadioThick">Thick</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input canvasStrokeSizeRadio" name="canvasStrokeSizeRadio" type="radio" id="canvasStrokeSizeRadioVeryThick" value="20">
      <label class="form-check-label" for="canvasStrokeSizeRadioVeryThick">Very Thick</label>
    </div>
  </div>
</div>

<!-- Color Picker Container -->
<div class="row">
  <div class="col-12 text-center">
    <div id="colorPickerContainer"></div>
  </div>
</div>

<!-- Submit Container -->
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="input-group">
      <div class="input-group-prepend">
        <button id="clearCanvasButton" type="button" class="btn btn-outline-dark">Clear</button>
      </div>
      <input type="text" class="form-control" id="artistName" placeholder="Artist Name (optional)">
      <div class="input-group-append">
        <button id="submitButton" type="button" class="btn btn-success">Submit</button>
      </div>
    </div>
  </div>

  <!-- Fake Form -->
  <form id="submitForm" action="submit.php" method="post">
    <input type="hidden" id="profRMPIdInput" name="profRMPId" value="">
    <input type="hidden" id="artistNameInput" name="artistName" value="">
    <input type="hidden" id="isMobileInput" name="isMobile" value="">
    <input type="hidden" id="imageDataUrlInput" name="imageDataUrl" value="">
  </form>
</div>
<!-- Draw Prof Scripts -->
<script type="text/javascript" src="js/drawprof.js"></script>
<?php
showFooter();
