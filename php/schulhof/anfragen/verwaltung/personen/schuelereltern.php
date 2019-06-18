<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['zuordnung'])) {$zuordnung = $_POST['zuordnung'];} else {echo "FEHLER";exit;}
$zuordnungin = str_replace('|', ', ', $zuordnung);
$zuordnungin = "(".substr($zuordnungin, 2).")";
if (!cms_check_idliste($zuordnungin) && strlen($zuordnungin) > 2) {echo "FEHLER"; exit;}
if (isset($_SESSION['PERSONENDETAILS'])) {$person = $_SESSION['PERSONENDETAILS'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($person,0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet() && ($CMS_RECHTE['Personen']['Schüler und Eltern verknüpfen'])) {
	$fehler = false;

	$dbs = cms_verbinden('s');
	// Art der Person laden
	$personenart = "";

	$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?;");
  $sql->bind_param("i", $person);
  if ($sql->execute()) {
    $sql->bind_result($personenart);
    if (!$sql->fetch()) {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	// Prüfen, alle zu verknüpfenden Personen von der entsprechenden art sind
	$sqlwhere = "";
	$personenfehler = false;
	if (!$fehler) {
		if ($personenart == 's') {$art = "e";}
		else if ($personenart == 'e') {$art = "s";}
		else {$fehler = true;}
		if (strlen($zuordnungin) > 2) {
			if (cms_check_idliste($zuordnungin)) {
				$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$zuordnungin." AND art != AES_ENCRYPT(?, '$CMS_SCHLUESSEL');");
			  $sql->bind_param("s", $art);
			  if ($sql->execute()) {
			    $sql->bind_result($anzahl);
			    if ($sql->fetch()) {if ($anzahl != 0) {$personenfehler = true;}}
					else {$fehler = true;}
			  }
			  else {$fehler = true;}
			  $sql->close();
			}
			else {$fehler = true;}
		}

		if ($personenfehler) {
			$fehler = true;
			echo "PERSONEN";
		}
	}
	cms_trennen($dbs);



	if (!$fehler) {
		$dbs = cms_verbinden('s');
		if ($personenart == 's') {
			// Löschen aller bisherigen Verbindungen
			$sql = $dbs->prepare("DELETE FROM schuelereltern WHERE schueler = ?");
		  $sql->bind_param("i", $person);
		  $sql->execute();
		  $sql->close();

			// Eintragen der neuen Verbindugen
			$personen = explode("|", $zuordnung);
			$sql = $dbs->prepare("INSERT INTO schuelereltern (schueler, eltern) VALUES (?, ?);");
			for ($i = 1; $i <count($personen); $i++) {
				// EINSTELLUNGEN DER PERSON EINTRAGEN
			  $sql->bind_param("ii", $person, $personen[$i]);
			  $sql->execute();
			}
			$sql->close();
		}
		else if ($personenart == 'e') {
			// Löschen aller bisherigen Verbindungen
			$sql = $dbs->prepare("DELETE FROM schuelereltern WHERE eltern = ?");
		  $sql->bind_param("i", $person);
		  $sql->execute();
		  $sql->close();

			// Eintragen der neuen Verbindugen
			$personen = explode("|", $zuordnung);
			$sql = $dbs->prepare("INSERT INTO schuelereltern (schueler, eltern) VALUES (?, ?);");
			for ($i = 1; $i <count($personen); $i++) {
				// EINSTELLUNGEN DER PERSON EINTRAGEN
				$sql->bind_param("ii", $personen[$i], $person);
			  $sql->execute();
			}
			$sql->close();
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
