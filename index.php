<?php
require 'requires/core.php';
showHeader('Home');

?>
<div class="row">
  <div class="col">
    <div class="alert alert-warning" role="alert">Friendly reminder to keep submissions wholesome!</div>
  </div>
</div>
<?php

if(isset($_GET['logout'])) {
  ?>
  <div class="row">
    <div class="col">
      <div class="alert alert-success" role="alert">Logout successful.</div>
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
  <div class="col text-center">
    <h2 class="profName">&nbsp;</h2>
    <h3 class="uniName">&nbsp;</h3>
  </div>
</div>

<!-- Canvas Container -->
<div class="row">
  <div class="col text-center">
    <div class="canvasContainer">
      <canvas id="c"></canvas>
    </div>
  </div>
</div>

<!-- Stroke Size Container -->
<div class="row">
  <div class="col my-3 text-center">
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
  <div class="col text-center">
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
    <input type="hidden" id="profUrlInput" name="profUrl" value="">
    <input type="hidden" id="artistNameInput" name="artistName" value="">
    <input type="hidden" id="isMobileInput" name="isMobile" value="">
    <input type="hidden" id="imageDataUrlInput" name="imageDataUrl" value="">
  </form>
</div>
<!-- Draw Prof Scripts -->
<script type="text/javascript" src="js/drawprof.js"></script>
<?php
showFooter();
