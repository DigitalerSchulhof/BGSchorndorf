<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['abo'])) {$abo = $_POST['abo'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($art)) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $art, $id, $person);

$zugriff = $CMS_GRUPPENRECHTE['mitglied'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	if (!cms_check_toggle($abo)) {$fehler = true;}

	if (!$fehler) {
		$gk = cms_textzudb($art);
		if ($abo == '1') {

			// Laden
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk"."notifikationsabo WHERE gruppe = ? AND person = ?");
			$sql->bind_param("ii", $id, $person);

			if ($sql->execute()) {
			  $anzahl = 0;
			  $sql->bind_result($anzahl);
			}
			else {$fehler = true;}
			$sql->close();

			if ($anzahl != 1) {
				$sql = $dbs->prepare("INSERT INTO $gk"."notifikationsabo (gruppe,person) VALUES (?,?)");
				$sql->bind_param("ii", $id, $person);
				$sql->execute();
				$sql->close();
			}
		}
		else if ($abo == '0') {
			$sql = $dbs->prepare("DELETE FROM $gk"."notifikationsabo WHERE gruppe = ? AND person = ?");
			$sql->bind_param("ii", $id, $person);
			$sql->execute();
			$sql->close();
		}
		echo "ERFOLG";
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
