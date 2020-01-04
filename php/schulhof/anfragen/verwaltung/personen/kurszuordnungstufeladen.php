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
$zugriff = $CMS_RECHTE['Personen']['Personen den Kursen zuordnen'];

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');
	$STUFEN = "";
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge");
	$sql->bind_param("i", $sj);
	if ($sql->execute()) {
		$sql->bind_result($sid, $sbez);
		while ($sql->fetch()) {
			$STUFEN .= "<option value=\"$sid\">$sbez</option>";
		}
	}
	$sql->close();
	cms_trennen($dbs);
	echo $STUFEN;
}
else {
	echo "FEHLER";
}
?>
