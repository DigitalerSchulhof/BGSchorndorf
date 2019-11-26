<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['mitglieder']))	{$mitglieder = $_POST['mitglieder'];} 	else {$mitglieder = '';}
if (isset($_SESSION["GRUPPEID"])) {$gruppenid = $_SESSION["GRUPPEID"];} else {$gruppenid = '-';}
if (isset($_SESSION["GRUPPEBEZEICHNUNG"])) {$gruppenbezeichnung = $_SESSION["GRUPPEBEZEICHNUNG"];} else {$gruppenbezeichnung = '-';}
if (isset($_SESSION["GRUPPE"])) {$gruppe = $_SESSION["GRUPPE"];} else {$gruppe = '-';}
$gruppek = strtolower($gruppe);

$angemeldet = cms_angemeldet();
$gruppenrechte = cms_gruppenrechte_laden($gruppe, $gruppenid);

$zugriff = $gruppenrechte['mv'];

if ($angemeldet && $zugriff) {
	$fehler = false;

	if ($gruppenid == '-') {$fehler = true;}

	if (!$fehler) {
		// Prüfen, ob die Personen in den des Gremiums der richtigen Personengruppe angehören
		$personenfehler = false;

		$mitgliederwhere = str_replace("|", ", ", $mitglieder);
		$dbs = cms_verbinden('s');
		if (strlen($mitgliederwhere) > 2) {
			$mitgliederwhere = "(".substr($mitgliederwhere, 2).")";
			$sql = "SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$mitgliederwhere." AND (art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL') AND art != AES_ENCRYPT('v', '$CMS_SCHLUESSEL'));";
			$anfrage = $dbs->query($sql);	// TODO: Irgendwie safe machen
			if ($anfrage) {
				if ($daten = $anfrage->fetch_assoc()) {
					if ($daten['anzahl'] != 0) {
						$personenfehler = true;
					}
				}
				else {$fehler = true;}
				$anfrage->free();
			}
			else {$fehler = true;}
		}
		cms_trennen($dbs);

		if ($personenfehler) {
			$fehler = true;
			echo "PERSONEN";
		}
	}

	if (!$fehler) {

		// Mitglieder hinzufügen
		$mitglieder = explode("|", $mitglieder);

		// i läuft über die einzelnen personen
		// erst alle Mitglieder löschen
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare($sql);
		$sql->bind_param("is", $gruppenid, $gruppe);
		$sql->execute();

		for ($i = 1; $i <count($mitglieder); $i++) {
			// Rechte laden
			$vorsitz = 0; $mv = 0; $sch = 0;
			$dho = 0; $dru = 0; $dum = 0; $dlo = 0;
			$oan = 0; $oum = 0; $olo = 0;
			if (isset($_POST['mitglieder_vorsitz'.$mitglieder[$i]])) {if ($_POST['mitglieder_vorsitz'.$mitglieder[$i]] == 1) {$vorsitz = 1;}}
			if (isset($_POST['mitglieder_mv'.$mitglieder[$i]])) {if ($_POST['mitglieder_mv'.$mitglieder[$i]] == 1) {$mv = 1;}}
			if (isset($_POST['mitglieder_sch'.$mitglieder[$i]])) {if ($_POST['mitglieder_sch'.$mitglieder[$i]] == 1) {$sch = 1;}}
			if (isset($_POST['mitglieder_dho'.$mitglieder[$i]])) {if ($_POST['mitglieder_dho'.$mitglieder[$i]] == 1) {$dho = 1;}}
			if (isset($_POST['mitglieder_dru'.$mitglieder[$i]])) {if ($_POST['mitglieder_dru'.$mitglieder[$i]] == 1) {$dru = 1;}}
			if (isset($_POST['mitglieder_dum'.$mitglieder[$i]])) {if ($_POST['mitglieder_dum'.$mitglieder[$i]] == 1) {$dum = 1;}}
			if (isset($_POST['mitglieder_dlo'.$mitglieder[$i]])) {if ($_POST['mitglieder_dlo'.$mitglieder[$i]] == 1) {$dlo = 1;}}
			if (isset($_POST['mitglieder_oan'.$mitglieder[$i]])) {if ($_POST['mitglieder_oan'.$mitglieder[$i]] == 1) {$oan = 1;}}
			if (isset($_POST['mitglieder_oum'.$mitglieder[$i]])) {if ($_POST['mitglieder_oum'.$mitglieder[$i]] == 1) {$oum = 1;}}
			if (isset($_POST['mitglieder_olo'.$mitglieder[$i]])) {if ($_POST['mitglieder_olo'.$mitglieder[$i]] == 1) {$olo = 1;}}

			// MITGLIEDER DEM GREMIUM ZUORDNEN
			$sqlwerte = "AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL'), $gruppenid, ".$mitglieder[$i].", AES_ENCRYPT('$vorsitz', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$mv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$sch', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dho', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dru', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dum', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dlo', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$oan', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$oum', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$olo', '$CMS_SCHLUESSEL')";
			$sql = "INSERT INTO mitgliedschaften (gruppe, gruppenid, person, vorsitz, mv, sch, dho, dru, dum, dlo, oan, oum, olo) VALUES ($sqlwerte);";
			$dbs->query($sql);	// TODO: Irgendwie safe machen
		}

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
