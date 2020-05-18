<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

if (cms_angemeldet() && cms_r("shop.bestellungen.löschen")) {
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("DELETE FROM ebestellung");
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
