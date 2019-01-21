<?php
require 'config.php';

$meta['og:url'] = [];
$meta['og:url']['content'] = "$base_url/contact";

$meta['og:type'] = [];
$meta['og:type']['content'] = "article";

$meta['og:title'] = [];
$meta['og:title']['content'] = "Contact DrawProf";

$meta['og:description'] = [];
$meta['og:description']['content'] = "The contact page for DrawProf. Show off your artistic and creative abilities! Draw your college professors on DrawProf!";

showHeader('Contact', $meta);
?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1>Contact</h1>
  </div>
</div>
<div class="row justify-content-center">
  <div class="col-md-8">
    <p>You can reach us at <a href="mailto:drawprof@salhacks.com?Subject=About%DrawProf">drawprof@salhacks.com</a> (:</p>
  </div>
</div>
<?php
showFooter();
