<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
$personen[0] = 'lehrer';
$personen[1] = 'verwaltungsangestellte';
$personen[2] = 'schueler';
$personen[3] = 'eltern';
$personen[4] = 'externe';

foreach ($personen as $p) {
	if (!isset($_POST['termine'.$p])) {echo "FEHLER";exit;}
	if (!isset($_POST['notizen'.$p])) {echo "FEHLER";exit;}
}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.verwaltung.einstellungen")) {
	$fehler = false;

	foreach ($personen as $p) {
		if (!cms_check_toggle($_POST['termine'.$p])) {$fehler = true;}
		if (!cms_check_toggle($_POST['notizen'.$p])) {$fehler = true;}
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$tpersonen[0] = 'Lehrer';
		$tpersonen[1] = 'Verwaltungsangestellte';
		$tpersonen[2] = 'Schüler';
		$tpersonen[3] = 'Eltern';
		$tpersonen[4] = 'Externe';

		$sql1 = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$sql2 = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		for ($i = 0; $i < count($personen); $i++) {
			$einstellungenname = $tpersonen[$i]." dürfen persönliche Termine anlegen";
			$sql1->bind_param("ss", $_POST['termine'.$personen[$i]], $einstellungenname);
		  $sql1->execute();

			$einstellungenname = $tpersonen[$i]." dürfen persönliche Notizen anlegen";
			$sql1->bind_param("ss", $_POST['notizen'.$personen[$i]], $einstellungenname);
		  $sql1->execute();
		}
		$sql1->close();
		$sql2->close();

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
