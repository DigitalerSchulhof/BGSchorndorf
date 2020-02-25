<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['sj'])) {$sj = $_POST['sj'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($sj, 0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.personen.löschen")) {
	$dbs = cms_verbinden('s');
	$SCHUELER = "";
	$SCHUELERIDS = "";
	$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND id NOT IN (SELECT person FROM kursemitglieder JOIN kurse ON kursemitglieder.gruppe = kurse.id WHERE schuljahr = ?) AND id NOT IN (SELECT person FROM klassenmitglieder JOIN klassen ON klassenmitglieder.gruppe = klassen.id WHERE schuljahr = ?) AND id NOT IN (SELECT person FROM stufenmitglieder JOIN stufen ON stufenmitglieder.gruppe = stufen.id WHERE schuljahr = ?)) AS x ORDER BY nachname, vorname, titel");
	$sql->bind_param("iii", $sj, $sj, $sj);
	if ($sql->execute()) {
		$sql->bind_result($sid, $svor, $snach, $stitel);
		while ($sql->fetch()) {
			$SCHUELER .= "<li>".cms_generiere_anzeigename($svor, $snach, $stitel)."</li>";
			$SCHUELERIDS .= "|".$sid;
		}
	}
	$sql->close();
	cms_trennen($dbs);

	$code = "";
	if (strlen($SCHUELER) > 0) {$code .= "<ul>".$SCHUELER."</ul>";}
	else {$code .= "<p class=\"cms_notiz\">-- Keine Schüler ohne Zuordnung in diesem Schuljahr gefunden --</p>";}
	$code .= "<input type=\"hidden\" id=\"cms_nicht_zugeordnet_schueler\" name=\"cms_nicht_zugeordnet_schueler\" value=\"$SCHUELERIDS\">";
	echo $code;
}
else {
	echo "FEHLER";
}
?>
