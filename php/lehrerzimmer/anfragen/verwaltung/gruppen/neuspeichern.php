<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

if (isset($_POST['gruppe'])) 	{$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['bezeichnung'])) 	{$bezeichnung = $_POST['bezeichnung'];} else {$bezeichnung = '';}
if (isset($_POST['icon'])) 	{$icon = $_POST['icon'];} else {$icon = '';}
if (isset($_POST['sichtbar']))		{$sichtbar = $_POST['sichtbar'];} 		else {$sichtbar = '';}
if (isset($_POST['mitglieder']))	{$mitglieder = $_POST['mitglieder'];} 	else {$mitglieder = '';}
$aufsicht = '';
if ($gruppe == 'Fachschaften') {
	if (isset($_POST['aufsicht']))	{$aufsicht = $_POST['aufsicht'];}
}

if (isset($_SESSION['IMLN'])) {$CMS_IMLN = $_SESSION['IMLN'];} else {$CMS_IMLN = false;}

if ($CMS_IMLN) {

	$angemeldet = cms_angemeldet();
	$CMS_RECHTE = cms_rechte_laden();
	$zugriff = $CMS_RECHTE['Organisation'][$gruppe.' anlegen'];
	$gruppek = strtolower($gruppe);

	if ($angemeldet && $zugriff) {
		$fehler = false;

		// Pflichteingaben prüfen
		if (!cms_check_titel($bezeichnung)) {$fehler = true;}

		if ((!is_file('../../../res/ereignisse/gross/'.$icon)) || (!is_file('../../../res/ereignisse/klein/'.$icon))) {
			$fehler = true;
			echo "ICON";
		}

		// Pflichteingaben prüfen
		if (($sichtbar != 0) && ($sichtbar != 1)) {
			$fehler = true;
		}

		if (!$fehler) {
			$dbs = cms_verbinden('s');
			// Prüfen, ob es bereits ein Gremium in dieser Bezeichnung existiert
			$bezeichnung = cms_texttrafo_e_db($bezeichnung);
			$sql = "SELECT COUNT(id) AS anzahl FROM $gruppek WHERE bezeichnung = AES_ENCRYPT('$bezeichnung', '$CMS_SCHLUESSEL')";
			if ($anfrage = $dbs->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()) {
					if ($daten['anzahl'] != 0) {
						$fehler = true;
						echo "DOPPELT";
					}
				}
				else {$fehler = true;}
				$anfrage->free();
			}
			else {$fehler = true;}
			cms_trennen($dbs);
		}

		if (!$fehler) {
			// Prüfen, ob die Personen in den Schlüsselpositionen der richtigen Personengruppe angehören
			$personenfehler = false;

			$mitgliederwhere = str_replace("|", ", ", $mitglieder);
			$dbs = cms_verbinden('s');
			if (strlen($mitgliederwhere) > 2) {
				$mitgliederwhere = "(".substr($mitgliederwhere, 2).")";
				$sql = "SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$mitgliederwhere." AND (art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL') AND art != AES_ENCRYPT('v', '$CMS_SCHLUESSEL'));";
				$anfrage = $dbs->query($sql);
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

			$aufsichtwhere = str_replace("|", ", ", $aufsicht);
			if (strlen($aufsichtwhere) > 2) {
				$aufsichtwhere = "(".substr($aufsichtwhere, 2).")";
				$sql = "SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$aufsichtwhere." AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL');";
				$anfrage = $dbs->query($sql);
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
			// NÄCHSTE FREIE ID SUCHEN
			$id = cms_generiere_kleinste_id($gruppek);
			if ($id == '-') {$fehler = true;}
		}

		if (!$fehler) {
			// GREMIUM EINTRAGEN
			$dbs = cms_verbinden('s');
			$bezeichnung = cms_texttrafo_e_db($bezeichnung);
			$sql = "UPDATE $gruppek SET bezeichnung = AES_ENCRYPT('$bezeichnung', '$CMS_SCHLUESSEL'), sichtbar = AES_ENCRYPT('$sichtbar', '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT('$icon', '$CMS_SCHLUESSEL') WHERE id = $id";
			$dbs->query($sql);

			// Mitglieder hinzufügen
			$mitglieder = explode("|", $mitglieder);

			// i läuft über die einzelnen personen
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
				$sqlrechte = "AES_ENCRYPT('$mv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$sch', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dho', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dru', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dum', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dlo', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$oan', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$oum', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$olo', '$CMS_SCHLUESSEL')";
				$sql = "INSERT INTO mitgliedschaften (gruppe, gruppenid, person, vorsitz, mv, sch, dho, dru, dum, dlo, oan, oum, olo) VALUES (AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL'), $id, ".$mitglieder[$i].", AES_ENCRYPT('$vorsitz', '$CMS_SCHLUESSEL'), $sqlrechte);";
				$dbs->query($sql);
			}

			$aufsicht = explode("|", $aufsicht);
			for ($i = 1; $i <count($aufsicht); $i++) {
				$sql = "INSERT INTO aufsichten (gruppe, gruppenid, person) VALUES (AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL'), $id, ".$aufsicht[$i].");";
				$dbs->query($sql);
			}
			cms_trennen($dbs);

			$pfad = '../../../dateien/schulhof/'.$gruppek.'/';
			chmod($pfad, 0777);
			if (!file_exists($pfad.$id)) {mkdir($pfad.$id, 0775);}
			chmod($pfad, 0775);

			echo $id."|ERFOLG";
		}
		else {
			echo "FEHLER";
		}
	}
	else {
		echo "BERECHTIGUNG";
	}
}
else {echo "FIREWALL";}
?>
