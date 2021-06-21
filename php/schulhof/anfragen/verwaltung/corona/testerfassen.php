<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['personen'])) {$personen = $_POST['personen'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}

if (cms_angemeldet() && ($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_idliste("($personen)")) {$fehler = true;}

	$anz = 0;

	if (!$fehler) {
		$ps = explode(",", $personen);

		// Eingabewerte prüfen
		foreach ($ps as $p) {
			if (isset($_POST["test_".$p])) {
				if (($_POST["test_".$p] != 'nt') && ($_POST["test_".$p] != 't') && ($_POST["test_".$p] != 'b')) {
					$fehler = true;
				} else if (($_POST["test_".$p] == 't') || ($_POST["test_".$p] == 'b')) {
					$anz++;
				}
			}
			else {
				$fehler = true;
			}
		}
	}

	if ($anz == 0) {$fehler = true;}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$testid = cms_generiere_kleinste_id('coronatest');
		$testzeit = time();
		// TEST EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE coronatest SET tester = ?, zeit = ? WHERE id = ?");
	  $sql->bind_param("iii", $CMS_BENUTZERID, $testzeit, $testid);
	  $sql->execute();
	  $sql->close();

		// TESTUNGEN EINTRAGEN
		$sql = $dbs->prepare("INSERT INTO coronagetestet (person, test, art) VALUES (?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
		foreach ($ps as $p) {
			if ($_POST['test_'.$p] != 'nt') {
				$testergebnis = $_POST['test_'.$p];
				$sql->bind_param("iis", $p, $testid, $testergebnis);
			  $sql->execute();
			}
		}
	  $sql->close();

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
