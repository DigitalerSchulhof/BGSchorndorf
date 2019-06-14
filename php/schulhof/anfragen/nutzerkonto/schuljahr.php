<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}
if (($modus != '0') && ($modus != '1')) {echo "FEHLER"; exit;}

$zugriff = false;
$CMS_RECHTE = cms_rechte_laden();

if ($modus == "1") {
	$zugriff = $CMS_RECHTE['Personen']['Personen bearbeiten'];
	if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
	if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}
}
else {
	$zugriff = true;
}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	$dbs = cms_verbinden('s');

	// Laden
  $sql = $dbs->prepare("SELECT count(id) AS anzahl FROM schuljahre WHERE id = ?");
  $sql->bind_param("i", $schuljahr);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();


	if (!$fehler) {
		// PROFILDATEN UPDATEN
		$sql = $dbs->prepare("UPDATE nutzerkonten SET schuljahr = ? WHERE id = ?;");
	  $sql->bind_param("ii", $schuljahr, $id);
	  $sql->execute();
	  $sql->close();

		if ($modus != 1) {$_SESSION['BENUTZERSCHULJAHR'] = $schuljahr;}
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
