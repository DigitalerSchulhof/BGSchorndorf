<?php
$dateienplaetten = false;
$rechteplaetten = true;
$einstellungenplaetten = true;
$zulaessigedateienplaetten = true;
$gremienklassen = false;
$postfachordner = false;
$update = false;

$personen = array("Lehrer", "Verwaltungsangestellte", "Schüler", "Eltern", "Externe");
$gruppen = array("Gremien", "Fachschaften", "Klassen", "Kurse", "Stufen", "Arbeitsgemeinschaften", "Arbeitskreise", "Fahrten", "Wettbewerbe", "Ereignisse", "Sonstige Gruppen");
$raenge = array("Vorsitzende", "Aufsicht", "Mitglieder");

if ($dateienplaetten) {
	include_once("php/schulhof/funktionen/dateisystem.php");
	cms_dateisystem_ordner_loeschen('dateien/schulhof');

	chmod($pfad, 0777);
	$ordner = array("fachschaften", "gremien", "klassen", "kurse", "stundenplaene", "vpn");
	foreach ($ordner as $o) {
		if (!file_exists($pfad.$id)) {mkdir('dateien/schulhof/'.$o, 0775);}
	}
	chmod($pfad, 0775);
	echo "DATEIEN GELÖSCHT<br>";
}


if ($postfachordner) {
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/config.php");
	$dbs = cms_verbinden('s');
	$sql = "SELECT id FROM personen";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			if (!file_exists('dateien/schulhof/personen/'.$daten['id'])) {mkdir('dateien/schulhof/personen/'.$daten['id'], 0775); echo $daten['id']." - Personenordner";}
			if (!file_exists('dateien/schulhof/personen/'.$daten['id'].'/postfach')) {mkdir('dateien/schulhof/personen/'.$daten['id'].'/postfach', 0775); echo $daten['id']." - Postfachordner";}
			if (!file_exists('dateien/schulhof/personen/'.$daten['id'].'/postfach/eingang')) {mkdir('dateien/schulhof/personen/'.$daten['id'].'/postfach/eingang', 0775); echo $daten['id']." - Eingang";}
			if (!file_exists('dateien/schulhof/personen/'.$daten['id'].'/postfach/ausgang')) {mkdir('dateien/schulhof/personen/'.$daten['id'].'/postfach/ausgang', 0775); echo $daten['id']." - Ausgang";}
			if (!file_exists('dateien/schulhof/personen/'.$daten['id'].'/postfach/entwuerfe')) {mkdir('dateien/schulhof/personen/'.$daten['id'].'/postfach/entwuerfe', 0775); echo $daten['id']." - Entwürfe";}
			if (!file_exists('dateien/schulhof/personen/'.$daten['id'].'/postfach/temp')) {mkdir('dateien/schulhof/personen/'.$daten['id'].'/postfach/temp', 0775); echo $daten['id']." - Zwischenspeicher";}
		}
		$anfrage->free();
	}
	echo "POSTFACHORDNER ANGELEGT<br>";
}


if ($gremienklassen) {
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/config.php");
	$ordner = array("fachschaften", "gremien", "klassen", "kurse");
	$dbs = cms_verbinden('s');
	foreach ($ordner as $o) {
		$pfad = 'dateien/schulhof/'.$o;
		chmod($pfad, 0777);
		$sql = "SELECT id FROM $o";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				if (!file_exists($pfad.'/'.$daten['id'])) {mkdir($pfad.'/'.$daten['id'], 0775);}
				echo $pfad.'/'.$daten['id']."<br>";
			}
			$anfrage->free();
		}
		echo "<br>";
		chmod($pfad, 0775);
	}
	cms_trennen($dbs);
	echo "GRUPPENORDNER ANGELEGT<br>";
}



if ($zulaessigedateienplaetten) {
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/schulhof/funktionen/config.php");

	$dbs = cms_verbinden('s');

	// SOLANGE DIE TABELLE LEER IST
	$install = true;
	$id = 0;
	$sql = "DELETE FROM zulaessigedateien";
	$dbs->query($sql);

	if ($install) {
		// PERSONEN
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('7z', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ai', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('bmp', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('cdr', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('deb', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anwendungen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('divx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('dmg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anwendungen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('doc', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('docx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('eps', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('exe', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anwendungen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('fla', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Flash', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('flv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Flash', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('gif', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('gz', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('htm', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('html', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('indd', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('iso', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('jar', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anwendungen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('jpeg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('jpg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('m4a', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('m4b', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mpeg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mpg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('odt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ods', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('odp', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ogg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pdf', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pkg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anwendungen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('png', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ppt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pptx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ps', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('psd', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pubx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ram', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('rm', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('rar', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('swf', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Flash', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('tgz', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('tif', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('torrent', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ttf', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('txt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wav', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wma', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wmv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wps', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('xls', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('xlsx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('zip', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('gpn', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mp4', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mp3', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
	}
	cms_trennen($dbs);
	echo "ZULÄSSIGE DATEIEN ERNEUERT<br>";
}




if ($rechteplaetten) {
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/schulhof/funktionen/config.php");

	$dbs = cms_verbinden('s');

	$sql = "DELETE FROM rechte";
	$dbs->query($sql);
	$sql = "DELETE FROM rollen";
	$dbs->query($sql);
	$sql = "DELETE FROM rollenrechte";
	$dbs->query($sql);


	// Rechte anlegen
	$id = 0;

	// SOLANGE DIE TABELLE LEER IST
	$install = true;
	$sql = "SELECT COUNT(*) AS anzahl FROM rechte";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$anzahl = $daten['anzahl'];
			if ($anzahl > 0) {
				$install = false;
			}
		}
		$anfrage->free();
	}

	if ($install) {
		// ADMINISTRATION
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Adressen des Schulhofs verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Allgemeine Einstellungen vornehmen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Identitätsdiebstähle behandeln', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Mailadresse des Schulhofs verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulnetze verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('VPN verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Zulässige Dateien verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		/*
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldungshinweise anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldungshinweise bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldungshinweise löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Speicherplatzstatistik einsehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Updates einspielen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Backup erstellen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Verschlüsselung ändern', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Häufige Fragen anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Häufige Fragen bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Häufige Fragen löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		*/

		// GRUPPEN
		foreach ($gruppen as $g) {
			$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g anlegen', '$CMS_SCHLUESSEL'))";
			$dbs->query($sql); $id++;
			$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g bearbeiten', '$CMS_SCHLUESSEL'))";
			$dbs->query($sql); $id++;
			$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g löschen', '$CMS_SCHLUESSEL'))";
			$dbs->query($sql); $id++;
			$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g Listen sehen', '$CMS_SCHLUESSEL'))";
			$dbs->query($sql); $id++;
			$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g Listen sehen wenn Mitglied', '$CMS_SCHLUESSEL'))";
			$dbs->query($sql); $id++;
		}
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Chatmeldungen sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Chatmeldungen verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;

		// ORGANISATION
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge genehmigen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ferien anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ferien bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ferien löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien genehmigen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppenblogeinträge genehmigen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppenblogeinträge bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppentermine genehmigen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppentermine bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldung vorbereiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen akzeptieren', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen erfassen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen exportieren', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahre anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahre bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahre löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine genehmigen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dauerbrenner anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dauerbrenner bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dauerbrenner löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwände anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwände bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwände löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwandanschläge anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwandanschläge bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwandanschläge löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;


		// PERSONEN
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldedetails sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ansprechpartner sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrerkürzel ändern', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrerliste sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schülerliste sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Elternliste sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Verwaltungsliste sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Externenliste sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrern Stundenpläne zuweisen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Nutzerkonten anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Nutzerkonten bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Nutzerkonten löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Persönliche Daten sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Persönliche Einstellungen ändern', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rechte und Rollen zuordnen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rollen anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rollen bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rollen löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schüler und Eltern verknüpfen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Elternvertreter sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schülervertreter sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;


		// PLANUNG
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen anonymisiert sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen vornehmen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Klassenstundenpläne sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrerstundenpläne sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrervertretungsplan sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Raumpläne sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schülervertretungsplan sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stufenstundenpläne sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume rythmisieren', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Profile anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Profile bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Profile löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanung durchführen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stunden und Tagebücher erzeugen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Vertretungsplanung durchführen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ausplanungen durchführen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fächer anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fächer bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fächer löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahrfabrik', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;

		/*
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anträge sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anträge genehmigen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Antragsgenehmigungen ändern', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		*/

		// TECHNIK
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Geräte verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Geräte-Probleme melden', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge erteilen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge markieren', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Haustechnikausgabe verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;

		// WEBSITE
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Besucherstatistiken - Schulhof sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Besucherstatistiken - Website sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dateien hochladen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dateien löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dateien umbenennen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Feedback sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Feedback verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fehlermeldungen sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fehlermeldungen verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hauptnavigationen festlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte freigeben', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Startseite festlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Titelbilder hochladen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Titelbilder umbenennen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Titelbilder löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auffälliges sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auffälliges verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Emoticons verwalten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		/*$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Sprachen anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Sprachen bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Sprachen löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten verschiedener Sprachen verknüpfen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;*/
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter erstellen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter schreiben', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Mailingliste sehen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Kontakt anlegen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Kontakt bearbeiten', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Kontakt löschen', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;

		// ZUGRIFFE
		$sql = "INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Zugriffe', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrernetz', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;

		// ADMINISTRATOR ANLEGEN
		$sql = "INSERT INTO rollen (id, bezeichnung, personenart) VALUES (0, AES_ENCRYPT('Administrator', '$CMS_SCHLUESSEL'), AES_ENCRYPT('l', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql);
		for ($i=0; $i<$id; $i++) {
			$sql = "INSERT INTO rollenrechte (rolle, recht) VALUES (0, $i)";
			$dbs->query($sql);
		}

		$sql = "INSERT INTO rollenzuordnung (rolle, person) VALUES (0, 2)";
		$dbs->query($sql);

	}
	cms_trennen($dbs);
	echo "RECHTE ERNEUERT<br>";
}

if ($einstellungenplaetten) {
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/schulhof/funktionen/config.php");

	$dbs = cms_verbinden('s');
	$id = 0;
	$sql = "DELETE FROM allgemeineeinstellungen";
	$dbs->query($sql);
	for ($i=1; $i<=2; $i++) {
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i existiert', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Geschlecht', '$CMS_SCHLUESSEL'), AES_ENCRYPT('m', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Vorname', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pavel', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Nachname', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Kaplin', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Titel', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Mail', '$CMS_SCHLUESSEL'), AES_ENCRYPT('pavel.kaplin@schorndorf.de', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
	}

	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Download aus sichtbaren Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Menüseiten weiterleiten', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Klassen extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Schüler aktuell', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Schüler Folgetag', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Lehrer aktuell', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Lehrer Folgetag', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Lehrer extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Raum extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsbeginn Stunde', '$CMS_SCHLUESSEL'), AES_ENCRYPT('07', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsbeginn Minute', '$CMS_SCHLUESSEL'), AES_ENCRYPT('00', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsende Stunde', '$CMS_SCHLUESSEL'), AES_ENCRYPT('22', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsende Minute', '$CMS_SCHLUESSEL'), AES_ENCRYPT('00', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung aktiv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung Anmeldung notwendig', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung an GitHub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Feedback aktiv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Feedback Anmeldung notwendig', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung an GitHub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Reaktionen auf Blogeinträge', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Reaktionen auf Termine', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Reaktionen auf Galerien', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Chat Nachrichten löschen nach', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))";
	$dbs->query($sql); $id++;
	// Postfach
	foreach ($personen as $p) {
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen persönliche Termine anlegen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen persönliche Notizen anlegen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen intern Termine vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen intern Blogeinträge vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen Termine vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen Blogeinträge vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen Galerien vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
	}
	foreach ($personen as $a) {
		foreach ($personen as $e) {
			$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Postfach - $a dürfen $e schreiben', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
			$dbs->query($sql); $id++;
		}
	}
	foreach ($personen as $p) {
		foreach ($gruppen as $g) {
			foreach ($raenge as $r) {
				$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Postfach - $p dürfen $g $r schreiben', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
				$dbs->query($sql); $id++;
			}
		}
	}
	foreach ($gruppen as $g) {
		foreach ($personen as $p) {
			foreach ($raenge as $r) {
				$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$r $g $p', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
				$dbs->query($sql); $id++;
			}
		}
	}
	foreach ($gruppen as $g) {
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Genehmigungen $g Termine', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
		$sql = "INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Genehmigungen $g Blogeinträge', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))";
		$dbs->query($sql); $id++;
	}



	cms_trennen($dbs);
	echo "ALLGEMEINE EINSTELLUNGEN ERNEUERT";
}

if ($update) {
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/schulhof/funktionen/config.php");

	$dbs = cms_verbinden('s');
	/*
	dauerbrenner
	pinnwände
	hausemisterauftraege
	zeitraeume
	schulstunden
	faecher
	profile
	profilfaecher
	auffaelliges
	galerien
	regelunterricht
	zeitraeume
	stufen
	rythmisierung
	$sql = "CREATE TABLE `dauerbrenner` (
	  `id` bigint(255) UNSIGNED NOT NULL,
	  `bezeichnung` varbinary(5000) NOT NULL,
	  `sichtbars` int(1) UNSIGNED NOT NULL DEFAULT '0',
	  `sichtbarl` int(1) UNSIGNED NOT NULL DEFAULT '0',
	  `sichtbare` int(1) UNSIGNED NOT NULL DEFAULT '0',
	  `sichtbarv` int(1) UNSIGNED NOT NULL DEFAULT '0',
	  `sichtbarx` int(1) UNSIGNED NOT NULL DEFAULT '0',
	  `inhalt` longblob NOT NULL,
	  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
	  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	$dbs->query($sql);
	*/
	cms_trennen($dbs);
}


if ((!$rechteplaetten) && (!$dateienplaetten) && (!$einstellungenplaetten) && (!$zulaessigedateienplaetten) && (!$gremienklassen) && (!$postfachordner) && (!$update)) {
	echo "INAKTIV";
}

// .DSStore löschen
function cms_dateisystem_ds_loeschen($pfad) {
  $dateien = "";
  $ordner = "";
  $groesse = 0;
  if (is_dir ($pfad)) {
		if (is_file($pfad."/.DS_Store")) {
			unlink($pfad."/.DS_Store");
			echo $pfad."/.DS_Store<br>";
		}
    $verzeichnis = scandir($pfad);
    // einlesen der Verzeichnisses
		foreach ($verzeichnis as $v) {
			if (($v != "..") && ($v != ".")) {
				if (is_dir($pfad."/".$v)) {
          cms_dateisystem_ds_loeschen($pfad."/".$v);
        }
			}
		}
		return true;
  }
  return false;
}


echo "<br><br>";
cms_dateisystem_ds_loeschen(".");
echo "AUFGERÄUMT";


?>
