<?php
  include_once("../php/schulhof/anfragen/verwaltung/gruppen/initial.php");
  include_once("../php/schulhof/funktionen/generieren.php");

  foreach($CMS_GRUPPEN as $g) {
    $g = cms_textzudb($g);
    $sql = "ALTER TABLE ".$g."blogeintraegeintern ADD notifikationen INT(1) NOT NULL AFTER aktiv;";
    echo $sql, "<br>";
  }
  echo "<br>";
  foreach($CMS_GRUPPEN as $g) {
    $g = cms_textzudb($g);
    $sql = "ALTER TABLE ".$g."termineintern ADD notifikationen INT(1) NOT NULL AFTER aktiv;";
    echo $sql, "<br>";
  }

  $sql = "ALTER TABLE blogeintraege ADD notifikationen INT(1) NOT NULL AFTER aktiv;";
  echo $sql, "<br>";
  $sql = "ALTER TABLE termine ADD notifikationen INT(1) NOT NULL AFTER aktiv;";
  echo $sql, "<br>";
?>
