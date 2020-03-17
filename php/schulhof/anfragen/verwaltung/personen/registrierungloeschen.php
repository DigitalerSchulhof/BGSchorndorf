<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['idreg'])) {$idreg = $_POST['idreg'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($idreg,0)) {echo "FEHLER"; exit;}
$fehler = false;

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Nutzerkonten anlegen'];
if (cms_angemeldet() && $zugriff) {
	// Prüfen, ob der benutzer bereits besteht
	$dbs = cms_verbinden('s');
	// Prüfen, ob der Benutzername bereits vergeben ist
	$sql = $dbs->prepare("DELETE FROM nutzerregistrierung WHERE id = ?");
  $sql->bind_param("i", $idreg);
  $sql->execute();
  $sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
