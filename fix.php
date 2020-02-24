<?php
include_once('php/allgemein/funktionen/sql.php');
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/generieren.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
$dbs = cms_verbinden('s');
// $sql = $dbs->prepare("SELECT id FROM unterricht WHERE tkurs IN (SELECT id FROM kurse WHERE stufe IN (SELECT id FROM stufen WHERE tagebuch = 1))");
// $tagebuch = array();
// if ($sql->execute()) {
//   $sql->bind_result($tid);
//   while ($sql->fetch()) {
//     echo "INSERT INTO tagebuch (id) VALUES ($tid);<br>";
//   }
// }
// $sql->close();
cms_trennen($dbs);

foreach ($CMS_GRUPPEN AS $g) {
  $gk = cms_textzudb($g);
  echo "ALTER TABLE `$gk"."termineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
  echo "ALTER TABLE `$gk"."termineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
}
?>
