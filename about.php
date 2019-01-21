<?php
require 'config.php';

$meta['og:url'] = [];
$meta['og:url']['content'] = "$base_url/about";

$meta['og:type'] = [];
$meta['og:type']['content'] = "article";

$meta['og:title'] = [];
$meta['og:title']['content'] = "About DrawProf";

$meta['og:description'] = [];
$meta['og:description']['content'] = "DrawProf was inspired by the now defunct Draw Your Professor. DrawProf was started at SB Hacks V. Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

showHeader('About', $meta);
?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1>About</h1>
  </div>
</div>
<div class="row justify-content-center">
  <div class="col-md-8">
    <p>DrawProf was inspired by the now defunct <a href="https://twitter.com/drawyourprof?lang=en">Draw Your Professor</a>.</p> DrawProf was started at <a href="https://www.sbhacks.com/">SB Hacks V</a>. The original DevPost can be found here: <a href="https://devpost.com/software/drawprof">https://devpost.com/software/drawprof</a>.</p>
  </div>
</div>
<?php
showFooter();
