<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "id"));
if(!cms_valide_gruppe($gruppe))
  die("FEHLER");
$dbs = cms_verbinden('s');
cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.verwaltung.nutzerkonten.verstöße.chatmeldungen")) {
  $gk = cms_textzudb($gruppe);
  $sql = "DELETE FROM $gk"."chatmeldungen WHERE nachricht = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $id);
  $sql->execute();

  // Spalte in der chat-tabelle setzten
  $sql = "UPDATE $gk"."chat SET loeschstatus = 1 WHERE id = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $id);
  $sql->execute();
  echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
