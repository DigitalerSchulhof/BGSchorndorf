<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['fach'])) {$fach = $_POST['fach'];} else {echo "FEHLER";exit;}
if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER";exit;}
if (isset($_POST['gewaehlt'])) {$gewaehlt = $_POST['gewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHIENESCHULJAHR'])) {$SCHULJAHR = $_SESSION['SCHIENESCHULJAHR'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($fach, 0) && ($fach != '-')) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($stufe, 0) && ($stufe != '-')) {echo "FEHLER";exit;}
if (!cms_check_idfeld($gewaehlt)) {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.schienen.[|anlegen,bearbeiten]")) {
	$code = "";

	// gewählt zu array:
	$kurse = array();
	$suchmuster = "";
	if (strlen($gewaehlt) > 0) {
		$kurse = explode('|', substr($gewaehlt,1));
		$suchmuster = "OR kurse.id IN (".implode(',', $kurse).")";
		$suchmusterfrei = "AND kurse.id IN (".implode(',', $kurse).")";
	}

	$dbs = cms_verbinden('s');
	if (($fach != '-') && ($stufe != '-')) {
		$sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ? AND ((stufe = ? AND fach = ?) $suchmuster)) AS x ORDER BY reihenfolge, bez");
		$sql->bind_param("iii", $SCHULJAHR, $stufe, $fach);
	}
	else if ($fach != '-') {
		$sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ? AND (fach = ? $suchmuster)) AS x ORDER BY reihenfolge, bez");
		$sql->bind_param("ii", $SCHULJAHR, $fach);
	}
	else if ($stufe != '-') {
		$sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ? AND (stufe = ? $suchmuster)) AS x ORDER BY reihenfolge, bez");
		$sql->bind_param("ii", $SCHULJAHR, $stufe);
	}
	else {
		$sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ? $suchmusterfrei) AS x ORDER BY reihenfolge, bez");
		$sql->bind_param("i", $SCHULJAHR);
	}

	if ($sql->execute()) {
		$sql->bind_result($kid, $kbez, $kreihe);
		while ($sql->fetch()) {
			if (in_array($kid, $kurse)) {$code .= cms_togglebutton_generieren ("cms_schiene_kurs_".$kid, $kbez, 1, 'cms_schienen_fach_auswaehlen(\''.$kid.'\')')." ";}
			else {$code .= cms_togglebutton_generieren ("cms_schiene_kurs_".$kid, $kbez, 0, 'cms_schienen_fach_auswaehlen(\''.$kid.'\')')." ";}
		}
	}
	$sql->close();
	if (strlen($code) == 0) {
		$code .= "<span class=\"cms_notiz\">Zu dieser Filterauswahl wurden keine Kurse gefunden.</span>";
	}
	$code .= "<input type=\"hidden\" name=\"cms_schiene_kursegewaehltids\" id=\"cms_schiene_kursegewaehltids\" value=\"$gewaehlt\">";

	echo $code;

	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
