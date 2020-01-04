<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($schuljahr,0)) && ($schuljahr != '-')) {echo "FEHLER"; exit;}
if (isset($_POST['gewaehlt'])) {$gewaehlt = $_POST['gewaehlt'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($gewaehlt,0)) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.gruppen.stufen.anlegen")) {

	// Finde Anzahl an Gruppen
	$anzahl = 0;
	if ($schuljahr == '-') {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM stufen WHERE schuljahr IS NULL");
	}
	else {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM stufen WHERE schuljahr = ?");
		$sql->bind_param("i", $schuljahr);
	}
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		$sql->fetch();
		if ($anzahl === null) {$anzahl = 0;}
	}
	$sql->close();

	$code = "";
	for ($i = 1; $i <= $anzahl; $i++) {
		if ($gewaehlt == $i) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
		$code .= "<option value=\"$i\"$wahl>$i</option>";
	}
	if ($gewaehlt == $i) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
	$code .= "<option value=\"$i\"$wahl>$i</option>";
	echo $code;
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
