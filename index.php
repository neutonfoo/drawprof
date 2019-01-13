<?php
require 'requires/core.php';
showHeader('Home');
?>
<!-- Professor URL -->
<div class="input-group">
  <input type="text" id="loadProfUrl" class="form-control" placeholder="Professor's Rate My Professor URL" value="">
  <div class="input-group-append">
    <button type="button" id="loadProfButton" class="btn btn-outline-secondary">Load Info</button>
  </div>
</div>

<!-- Professor Meta Container -->
<div id="profMetaContainer">
  <h1 id="profName">&nbsp;</h1>
  <h2 id="uniName">&nbsp;</h2>
</div>

<!-- Canvas Container -->
<div id="canvasContainer">
  <canvas id="c"></canvas>
</div>

<!-- Color Picker Container -->
<div id="colorPickerContainer"></div>

<!-- Controls Container -->
<div id="controlsContainer">
  <button id="clearCanvasButton" type="button" class="btn btn-outline-dark">Clear</button>
  <button id="uploadButton" type="button" class="btn btn-success">Upload</button>
  <form id="uploadForm" action="upload.php" method="post">
    <input type="hidden" id="profNameInput" name="profName" value="">
    <input type="hidden" id="uniNameInput" name="uniName" value="">
    <input type="hidden" id="isMobileInput" name="isMobile" value="">
    <input type="hidden" id="imageDataUrlInput" name="imageDataUrl" value="">
  </form>
</div>
<?php
showFooter();
