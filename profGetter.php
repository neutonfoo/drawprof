<?php

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function getProfMeta($profUrl) {
  $html = file_get_contents($profUrl);

  $title = get_string_between($html, '<title>', '</title>');

  if($title == "Rate My Professors -  Review Teachers and Professors, School Reviews, College Campus Ratings") {
    return "INVALID";
  } else {
    $titleSections = explode(" at ", $title);

    $profName = $titleSections[0];
    $uniName = explode(" - RateMyProfessors.com", $titleSections[1])[0];

    return $profName . "|" . $uniName;
  }
}

// If used as AJAX
if(isset($_POST['profUrl'])) {
  $profUrl = $_POST['profUrl'];
  echo getProfMeta($profUrl);
}
