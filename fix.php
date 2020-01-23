<?php
include_once('php/allgemein/funktionen/sql.php');
include_once("php/schulhof/funktionen/config.php");
$dbs = cms_verbinden('s');
$sql = $dbs->prepare("SELECT id FROM unterricht WHERE tkurs IN (SELECT id FROM kurse WHERE stufe IN (SELECT id FROM stufen WHERE tagebuch = 1))");
$tagebuch = array();
if ($sql->execute()) {
  $sql->bind_result($tid);
  while ($sql->fetch()) {
    echo "INSERT INTO tagebuch (id) VALUES ($tid);<br>";
  }
}
$sql->close();
cms_trennen($dbs);
?>
