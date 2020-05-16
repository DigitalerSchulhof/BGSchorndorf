<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER";exit;}
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {echo "FEHLER";exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER";exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER";exit;}
if (isset($_POST['geschlecht'])) {$geschlecht = $_POST['geschlecht'];} else {echo "FEHLER";exit;}
if (isset($_POST['lehrerkuerzel'])) {$lehrerkuerzel = $_POST['lehrerkuerzel'];} else {echo "FEHLER";exit;}
if (isset($_POST['stundenplan'])) {$stundenplan = $_POST['stundenplan'];} else {echo "FEHLER";exit;}




if (cms_angemeldet() && cms_r("schulhof.verwaltung.personen.anlegen")) {

	// Zusammenbauen der Bedingung
	$sqlwhere = '';
	$fehler = false;

	// Pflichteingaben prüfen
	if (strlen($vorname) == 0) {$fehler = true;}
	if (strlen($nachname) == 0) {$fehler = true;}
	if (!cms_check_name($vorname)) {$fehler = true;}
	if (!cms_check_name($nachname)) {$fehler = true;}
	if (!cms_check_nametitel($titel)) {$fehler = true;}
	if (($geschlecht != 'w') && ($geschlecht != "m") && ($geschlecht != "u")) {
		$fehler = true;
	}
	if (($art != 's') && ($art != "l") && ($art != "e") && ($art != "v") && ($art != "x")) {
		$fehler = true;
	}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('personen');

		// PERSON EINTRAGEN
		$dbs = cms_verbinden('s');
		$titel = cms_texttrafo_e_db($titel);
		$vorname = cms_texttrafo_e_db($vorname);
		$nachname = cms_texttrafo_e_db($nachname);
		$sql = $dbs->prepare("UPDATE personen SET art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geschlecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	  $sql->bind_param("sssssi", $art, $titel, $nachname, $vorname, $geschlecht, $id);
	  $sql->execute();
	  $sql->close();

		// EINSTELLUNGEN DER PERSON EINTRAGEN
		$sql = $dbs->prepare("INSERT INTO personen_einstellungen (person, notifikationsmail, postmail, postalletage, postpapierkorbtage, vertretungsmail, uebersichtsanzahl, oeffentlichertermin, oeffentlicherblog, oeffentlichegalerie, inaktivitaetszeit, wikiknopf, dateiaenderung) VALUES (?, AES_ENCRYPT('0', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1','$CMS_SCHLUESSEL'), AES_ENCRYPT('365','$CMS_SCHLUESSEL'), AES_ENCRYPT('30','$CMS_SCHLUESSEL'), AES_ENCRYPT('1','$CMS_SCHLUESSEL'), AES_ENCRYPT('5','$CMS_SCHLUESSEL'), AES_ENCRYPT('1','$CMS_SCHLUESSEL'), AES_ENCRYPT('1','$CMS_SCHLUESSEL'), AES_ENCRYPT('1','$CMS_SCHLUESSEL'), AES_ENCRYPT('30','$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'));");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		// SIGNATUR DER PERSON EINTRAGEN
		$sql = $dbs->prepare("INSERT INTO personen_signaturen (person, signatur) VALUES (?, AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		if ($art == "l") {
			$lehrerkuerzel = cms_texttrafo_e_db($lehrerkuerzel);
			$sql = $dbs->prepare("INSERT INTO lehrer (id, kuerzel, stundenplan) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
			$sql->bind_param("iss", $id, $lehrerkuerzel, $stundenplan);
			$sql->execute();
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
