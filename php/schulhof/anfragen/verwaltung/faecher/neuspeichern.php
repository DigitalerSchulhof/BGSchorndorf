<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {$bezeichnung = '';}
if (isset($_POST['kuerzel'])) {$kuerzel = $_POST['kuerzel'];} else {$kuerzel = '';}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Fächer anlegen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}
	if (!cms_check_titel($kuerzel)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $bezeichnung);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELTB"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $kuerzel);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELTK"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		cms_trennen($dbs);
	}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('faecher');
		if ($id == '-') {$fehler = true;}
	}

	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = "UPDATE faecher SET bezeichnung = AES_ENCRYPT('$bezeichnung', '$CMS_SCHLUESSEL'), kuerzel = AES_ENCRYPT('$kuerzel', '$CMS_SCHLUESSEL') WHERE id = $id";
		$anfrage = $dbs->query($sql);

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
