<?php
require 'requires/core.php';
showHeader('Home');

if(isset($_GET['logout'])) {
  ?>
  <div class="alert alert-success" role="alert">Logout successful.</div>
  <?php
}
?>
<!-- Professor URL -->
<div class="input-group">
  <input type="text" id="loadProfUrl" class="form-control" placeholder="Professor's Rate My Professors URL" value="">
  <div class="input-group-append">
    <button type="button" id="loadProfButton" class="btn btn-outline-secondary">Load Info</button>
  </div>
</div>

<!-- Professor Meta Container -->
<div class="profMetaContainer">
  <h2 class="profName">&nbsp;</h2>
  <h3 class="uniName">&nbsp;</h3>
</div>

<!-- Canvas Container -->
<div class="canvasContainer">
  <canvas id="c"></canvas>
</div>

<!-- Color Picker Container -->
<div id="colorPickerContainer"></div>

<!-- Controls Container -->
<div id="controlsContainer">
  <div class="row">
    <div class="col-3"></div>
    <div class="col-2">
      <button id="clearCanvasButton" type="button" class="btn btn-outline-dark">Clear</button>
    </div>
    <div class="col-4">
      <div class="input-group">
        <input type="text" class="form-control" id="artistName" placeholder="Artist Name" size="10">
          <div class="input-group-append">
            <button id="submitButton" type="button" class="btn btn-success">Submit</button>
          </div>
      </div>
    </div>
    <div class="col-3"></div>
  </div>
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