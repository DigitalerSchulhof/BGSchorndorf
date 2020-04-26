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
if ((!cms_check_ganzzahl($gewaehlt,0)) && ($gewaehlt != '-')) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');


if (cms_angemeldet() && cms_r("schulhof.gruppen.[|klassen,kurse].anlegen")) {

	// Finde Anzahl an Gruppen
	$stufen = array();
	if ($schuljahr == '-') {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr IS NULL ORDER BY reihenfolge");
	}
	else {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge");
		$sql->bind_param("i", $schuljahr);
	}
	if ($sql->execute()) {
		$sql->bind_result($sid, $sbez);
		while ($sql->fetch()) {
			$s = array();
			$s['id'] = $sid;
			$s['bez'] = $sbez;
			array_push($stufen, $s);
		}
	}
	$sql->close();

	$code = "";
	foreach ($stufen AS $s) {
		if ($gewaehlt == $s['id']) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
		$code .= "<option value=\"".$s['id']."\"$wahl>".$s['bez']."</option>";
	}
	if ($gewaehlt == '-') {$wahl = " selected=\"selected\";";} else {$wahl = "";}
	$code .= "<option value=\"-\"$wahl>stufenübergreifend</option>";
	echo $code;
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
