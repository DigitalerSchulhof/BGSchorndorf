<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
$gruppen[0] = 'gremien';
$gruppen[1] = 'fachschaften';
$gruppen[2] = 'klassen';
$gruppen[3] = 'kurse';
$gruppen[4] = 'stufen';
$gruppen[5] = 'arbeitsgemeinschaften';
$gruppen[6] = 'arbeitskreise';
$gruppen[7] = 'fahrten';
$gruppen[8] = 'wettbewerbe';
$gruppen[9] = 'ereignisse';
$gruppen[10] = 'sonstigegruppen';

$optionen[0] = 'mitglieder';
$optionen[1] = 'aufsicht';

$personen[0] = 'lehrer';
$personen[1] = 'verwaltungsangestellte';
$personen[2] = 'schueler';
$personen[3] = 'eltern';
$personen[4] = 'externe';

foreach ($optionen as $o) {
	foreach ($personen as $p) {
		foreach ($gruppen as $g) {
			if (!isset($_POST[$o.$p.$g])) {echo "FEHLER";exit;}
		}
	}
}

$objekte[0] = 'termine';
$objekte[1] = 'blog';

foreach ($personen as $p) {
	foreach ($objekte as $o) {
		if (!isset($_POST[$p.$o.'internvorschlagen'])) {echo "FEHLER";exit;}
	}
}

foreach ($gruppen as $g) {
	if (!isset($_POST['genehmigung'.$g.'termine'])) {echo "FEHLER";exit;}
	if (!isset($_POST['genehmigung'.$g.'blogeintraege'])) {echo "FEHLER";exit;}
}

if (!isset($_POST['sichtbardownload'])) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Administration']['Allgemeine Einstellungen vornehmen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	foreach ($optionen as $o) {
		foreach ($personen as $p) {
			foreach ($gruppen as $g) {
				if (!cms_check_toggle($_POST[$o.$p.$g])) {$fehler = true;}
			}
		}
	}

	foreach ($gruppen as $g) {
		if (!cms_check_toggle($_POST['genehmigung'.$g.'termine'])) {$fehler = true;}
		if (!cms_check_toggle($_POST['genehmigung'.$g.'blogeintraege'])) {$fehler = true;}
	}

	foreach ($personen as $p) {
		foreach ($objekte as $o) {
			if (!cms_check_toggle($_POST[$p.$o.'internvorschlagen'])) {$fehler = true;}
		}
	}

	if (!cms_check_toggle($_POST['sichtbardownload'])) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		foreach ($optionen as $o) {
			$og = cms_vornegross($o);
			foreach ($personen as $p) {
				if ($p == "schueler") {$pg = "Schüler";}
				else {$pg = cms_vornegross($p);}
				foreach ($gruppen as $g) {
					if ($g == "sonstigegruppen") {$gg = "Sonstige Gruppen";}
					else {$gg = cms_vornegross($g);}
					$eigenschaftsname = "$og $gg $pg";
				  $sql->bind_param("ss", $_POST[$o.$p.$g], $eigenschaftsname);
				  $sql->execute();
				}
			}
		}
		$sql->close();

		$sql1 = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$sql2 = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		foreach ($gruppen as $g) {
			if ($g == "sonstigegruppen") {$gg = "Sonstige Gruppen";}
			else {$gg = cms_vornegross($g);}
			$eigenschaftsname = "Genehmigungen $gg Termine";
			$sql1->bind_param("ss", $_POST['genehmigung'.$g.'termine'], $eigenschaftsname);
			$sql1->execute();
			$eigenschaftsname = "Genehmigungen $gg Blogeinträge";
			$sql2->bind_param("ss", $_POST['genehmigung'.$g.'blogeintraege'], $eigenschaftsname);
			$sql2->execute();
		}
		$sql1->close();
		$sql2->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		foreach ($personen as $p) {
			if ($p == "schueler") {$pg = "Schüler";}
			else {$pg = cms_vornegross($p);}
			foreach ($objekte as $o) {
				if ($o == "blog") {$og = "Blogeinträge";}
				else {$og = cms_vornegross($o);}
				$eigenschaftsname = "$pg dürfen intern $og vorschlagen";
				$sql->bind_param("ss", $_POST[$p.$o.'internvorschlagen'], $eigenschaftsname);
				$sql->execute();
			}
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Download aus sichtbaren Gruppen', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $_POST['sichtbardownload']);
	  $sql->execute();
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
