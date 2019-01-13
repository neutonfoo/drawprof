<?php
  $profUrl = $_POST['profUrl'];

  $html = file_get_contents($profUrl);

  function get_string_between($string, $start, $end){
      $string = ' ' . $string;
      $ini = strpos($string, $start);
      if ($ini == 0) return '';
      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;
      return substr($string, $ini, $len);
  }

  echo get_string_between($html, '<title>', '</title>');
