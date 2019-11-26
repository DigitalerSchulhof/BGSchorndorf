<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) 	{$gruppe = $_POST['gruppe'];} else {echo 'FEHLER';exit;}
if (isset($_POST['bezeichnung'])) 	{$bezeichnung = $_POST['bezeichnung'];} else {$bezeichnung = '';}
if (isset($_POST['icon'])) 	{$icon = $_POST['icon'];} else {$icon = '';}
if (isset($_POST['sichtbar']))		{$sichtbar = $_POST['sichtbar'];} 		else {$sichtbar = '';}
if (isset($_POST['mitglieder']))	{$mitglieder = $_POST['mitglieder'];} 	else {$mitglieder = '';}
$aufsicht = '';
if ($gruppe == 'Fachschaften') {
	if (isset($_POST['aufsicht']))	{$aufsicht = $_POST['aufsicht'];}
}

if (isset($_SESSION["GRUPPEBEARBEITEN"])) {$id = $_SESSION["GRUPPEBEARBEITEN"];} else {$id = '-';}
if (isset($_SESSION["GRUPPE"])) {$gruppecheck = $_SESSION["GRUPPE"];} else {$gruppecheck = '-';}

$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Organisation'][$gruppe.' bearbeiten'];
$gruppek = strtolower($gruppe);

if ($angemeldet && $zugriff) {
	$fehler = false;

	if ($gruppe != $gruppecheck) {
		$fehler = true;
	}

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

	if ($id == '-') {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Prüfen, ob es bereits ein Gremium in dieser Bezeichnung existiert
		$bezeichnung = cms_texttrafo_e_db($bezeichnung);
		$sql = "SELECT COUNT(id) AS anzahl FROM $gruppek WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("si", $bezeichnung, $id);
		if ($sql->execute()) {
			$sql->bind_result($anz);
			if ($sql->fetch()) {
				if ($anz != 0) {
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

		$aufsichtwhere = str_replace("|", ", ", $aufsicht);
		if (strlen($aufsichtwhere) > 2) {
			$aufsichtwhere = "(".substr($aufsichtwhere, 2).")";
			$sql = "SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$aufsichtwhere." AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL');";
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

		// GREMIUM EINTRAGEN
		$dbs = cms_verbinden('s');
		$bezeichnung = cms_texttrafo_e_db($bezeichnung);
		$sql = "UPDATE $gruppek SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("ssii", $bezeichnung, $icon, $sichtbar, $id);
		$sql->execute();

		// Mitglieder hinzufügen
		$mitglieder = explode("|", $mitglieder);

		// i läuft über die einzelnen personen
		// erst alle Mitglieder löschen
		$sql = "DELETE FROM mitgliedschaften WHERE gruppenid = ? AND gruppe = AES_ENCRYPT('?', '$CMS_SCHLUESSEL')";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("is", $id, $gruppe);
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
			$sqlrechte = "AES_ENCRYPT('$vorsitz', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$mv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$sch', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dho', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dru', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dum', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$dlo', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$oan', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$oum', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$olo', '$CMS_SCHLUESSEL')";
			$sql = "INSERT INTO mitgliedschaften (gruppe, gruppenid, person, vorsitz, mv, sch, dho, dru, dum, dlo, oan, oum, olo) VALUES (AES_ENCRYPT('?', '$CMS_SCHLUESSEL'), ?, ?, $sqlrechte);";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("sii", $gruppe, $id, $mitglieder[$i]);
			$sql->execute();
		}

		// Aufsicht hinzufügen
		$aufsicht = explode("|", $aufsicht);

		// i läuft über die einzelnen personen
		// erst alle Aufsichten löschen
		$sql = "DELETE FROM aufsichten WHERE gruppenid = ? AND gruppe = AES_ENCRYPT('?', '$CMS_SCHLUESSEL')";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("is", $id, $gruppe);
		$sql->execute();
		for ($i = 1; $i <count($aufsicht); $i++) {
			$sql = "INSERT INTO aufsichten (gruppe, gruppenid, person) VALUES (AES_ENCRYPT('?', '$CMS_SCHLUESSEL'), ?, ?);";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("sii", $gruppe, $id, $aufsicht[$i]);
			$sql->execute();
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
