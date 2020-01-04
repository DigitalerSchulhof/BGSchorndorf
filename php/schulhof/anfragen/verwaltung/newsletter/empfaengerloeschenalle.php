<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen("id");
cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.information.newsletter.empfänger.löschen")) {
  $dbs = cms_verbinden("s");
  $sql = "DELETE FROM newsletterempfaenger WHERE newsletter = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $id);
  if(!$sql->execute() || !$dbs->affected_rows)
    die("FEHLER");
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
