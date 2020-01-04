<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();
if (isset($_POST['id'])) {$id = cms_texttrafo_e_db($_POST['id']);} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.organisation.schulanmeldung.löschen")) {
	$dbs = cms_verbinden();
	$sql = $dbs->prepare("DELETE FROM voranmeldung_schueler WHERE id = ?");
  $sql->bind_param("i", $id);
  $sql->execute();
  $sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
