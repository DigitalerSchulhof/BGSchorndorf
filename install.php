<?php
$dateienplaetten = false;
$rechteplaetten = false;
$internediensteplaetten = false;
$einstellungenplaetten = true;
$zulaessigedateienplaetten = false;
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
	$sql = $dbs->prepare("SELECT id FROM personen");
	if ($dbs->execute()) {
		$dbs->bind_result($pid);
		while ($sql->fetch()) {
			if (!file_exists('dateien/schulhof/personen/'.$pid)) {mkdir('dateien/schulhof/personen/'.$pid, 0775); echo $pid." - Personenordner";}
			if (!file_exists('dateien/schulhof/personen/'.$pid.'/postfach')) {mkdir('dateien/schulhof/personen/'.$pid.'/postfach', 0775); echo $pid." - Postfachordner";}
			if (!file_exists('dateien/schulhof/personen/'.$pid.'/postfach/eingang')) {mkdir('dateien/schulhof/personen/'.$pid.'/postfach/eingang', 0775); echo $pid." - Eingang";}
			if (!file_exists('dateien/schulhof/personen/'.$pid.'/postfach/ausgang')) {mkdir('dateien/schulhof/personen/'.$pid.'/postfach/ausgang', 0775); echo $pid." - Ausgang";}
			if (!file_exists('dateien/schulhof/personen/'.$pid.'/postfach/entwuerfe')) {mkdir('dateien/schulhof/personen/'.$pid.'/postfach/entwuerfe', 0775); echo $pid." - Entwürfe";}
			if (!file_exists('dateien/schulhof/personen/'.$pid.'/postfach/temp')) {mkdir('dateien/schulhof/personen/'.$pid.'/postfach/temp', 0775); echo $pid." - Zwischenspeicher";}
		}
	}
	$sql->close();
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
		$sql = $dbs->prepare("SELECT id FROM $o");
		if ($sql->execute()) {
			$sql->bind_result($oid);
			while ($sql->fetch()) {
				if (!file_exists($pfad.'/'.$oid)) {mkdir($pfad.'/'.$oid, 0775);}
				echo $pfad.'/'.$oid."<br>";
			}
		}
		$sql->close();
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
	$sql = $dbs->prepare("DELETE FROM zulaessigedateien");
	$sql->execute();

	if ($install) {
		// PERSONEN
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('7z', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ai', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('bmp', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('cdr', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('divx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('doc', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('docx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('eps', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('fla', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Flash', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('flv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Flash', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('gif', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('gz', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('htm', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('html', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('indd', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('iso', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('jpeg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('jpg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('m4a', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('m4b', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mpeg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mpg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('odt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ods', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('odp', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ogg', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pdf', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('png', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ppt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pptx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ps', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('psd', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Grafikprogramme', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('pubx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ram', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('rm', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('rar', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('swf', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Flash', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('tgz', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('tif', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('torrent', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('ttf', '$CMS_SCHLUESSEL'), AES_ENCRYPT('XSonstige', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('txt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wav', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wma', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wmv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('wps', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('xls', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('xlsx', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dokumente', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('zip', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Archive', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('gpn', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mp4', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO zulaessigedateien (id, endung, kategorie, zulaessig) VALUES ($id, AES_ENCRYPT('mp3', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
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

	$sql = $dbs->prepare("DELETE FROM rechte");
	$sql->execute();
	$sql = $dbs->prepare("DELETE FROM rollen");
	$sql->execute();
	$sql = $dbs->prepare("DELETE FROM rollenrechte");
	$sql->execute();


	// Rechte anlegen
	$id = 0;

	// SOLANGE DIE TABELLE LEER IST
	$install = true;
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM rechte");
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {
			if ($anzahl > 0) {
				$install = false;
			}
		}
	}
	$sql->close();

	if ($install) {
		// ADMINISTRATION
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Adressen des Schulhofs verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Allgemeine Einstellungen vornehmen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Identitätsdiebstähle behandeln', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Mailadresse des Schulhofs verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulnetze verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('VPN verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Zulässige Dateien verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		/*
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldungshinweise anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldungshinweise bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldungshinweise löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Speicherplatzstatistik einsehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Updates einspielen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Backup erstellen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Verschlüsselung ändern', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Häufige Fragen anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Häufige Fragen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Administration', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Häufige Fragen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		*/

		// GRUPPEN
		foreach ($gruppen as $g) {
			$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g anlegen', '$CMS_SCHLUESSEL'))");
			$sql->execute(); $id++;
			$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g bearbeiten', '$CMS_SCHLUESSEL'))");
			$sql->execute(); $id++;
			$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g löschen', '$CMS_SCHLUESSEL'))");
			$sql->execute(); $id++;
			$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g Listen sehen', '$CMS_SCHLUESSEL'))");
			$sql->execute(); $id++;
			$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$g Listen sehen wenn Mitglied', '$CMS_SCHLUESSEL'))");
			$sql->execute(); $id++;
		}
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Chatmeldungen sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Chatmeldungen verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;

		// ORGANISATION
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge genehmigen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ferien anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ferien bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ferien löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien genehmigen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppenblogeinträge genehmigen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppenblogeinträge bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppentermine genehmigen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Gruppentermine bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldung vorbereiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen akzeptieren', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen erfassen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen exportieren', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schulanmeldungen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahre anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahre bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahre löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine genehmigen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dauerbrenner anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dauerbrenner bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dauerbrenner löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwände anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwände bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwände löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwandanschläge anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwandanschläge bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Organisation', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pinnwandanschläge löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;


		// PERSONEN
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anmeldedetails sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ansprechpartner sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrerkürzel ändern', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrerliste sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schülerliste sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Elternliste sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Verwaltungsliste sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Externenliste sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrern Stundenpläne zuweisen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Nutzerkonten anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Nutzerkonten bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Nutzerkonten löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personenids importieren', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personenids bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Personen den Kursen zuordnen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Persönliche Daten sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Persönliche Einstellungen ändern', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rechte und Rollen zuordnen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rollen anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rollen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Rollen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schüler und Eltern verknüpfen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Elternvertreter sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Personen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schülervertreter sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;


		// PLANUNG
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen anonymisiert sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Buchungen vornehmen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Klassenstundenpläne sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrerstundenpläne sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrervertretungsplan sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Leihgeräte sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Räume sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Raumpläne sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Verantwortlichkeiten festlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schülervertretungsplan sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stufenstundenpläne sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanzeiträume rythmisieren', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Profile anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Profile bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Profile löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schienen anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schienen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schienen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stundenplanung durchführen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Stunden und Tagebücher erzeugen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Vertretungsplanung durchführen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Ausplanungen durchführen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fächer anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fächer bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fächer löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Schuljahrfabrik', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;

		/*
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anträge sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Anträge genehmigen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Planung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Antragsgenehmigungen ändern', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		*/

		// TECHNIK
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Geräte verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Geräte-Probleme melden', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge erteilen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge markieren', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hausmeisteraufträge löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Technik', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Haustechnikausgabe verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;

		// WEBSITE
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Besucherstatistiken - Schulhof sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Besucherstatistiken - Website sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Blogeinträge löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dateien hochladen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dateien löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Dateien umbenennen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Feedback sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Feedback verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fehlermeldungen sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Fehlermeldungen verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Galerien löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Hauptnavigationen festlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte freigeben', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Inhalte löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Startseite festlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Termine löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Titelbilder hochladen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Titelbilder umbenennen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Titelbilder löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auffälliges sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auffälliges verwalten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auszeichnungen anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auszeichnungen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Auszeichnungen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		/*$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Sprachen anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Sprachen bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Sprachen löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Seiten verschiedener Sprachen verknüpfen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;*/
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter schreiben', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Empfängerliste sehen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Empfänger anlegen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Empfänger bearbeiten', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Website', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Newsletter Empfänger löschen', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;

		// ZUGRIFFE
		$sql = $dbs->prepare("INSERT INTO rechte (id, kategorie, bezeichnung) VALUES ($id, AES_ENCRYPT('Zugriffe', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Lehrernetz', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;

		// ADMINISTRATOR ANLEGEN
		$sql = $dbs->prepare("INSERT INTO rollen (id, bezeichnung, personenart) VALUES (0, AES_ENCRYPT('Administrator', '$CMS_SCHLUESSEL'), AES_ENCRYPT('l', '$CMS_SCHLUESSEL'))");
		$sql->execute();
		for ($i=0; $i<$id; $i++) {
			$sql = $dbs->prepare("INSERT INTO rollenrechte (rolle, recht) VALUES (0, $i)");
			$sql->execute();
		}

		$sql = $dbs->prepare("INSERT INTO rollenzuordnung (rolle, person) VALUES (0, 2)");
		$sql->execute();

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

	$sql = $dbs->prepare("DELETE FROM schulanmeldung");
	$sql->execute();
	$sql = $dbs->prepare("INSERT INTO schulanmeldung (id, inhalt, wert) VALUES (0, AES_ENCRYPT('Anmeldung Eintrittsalter', '$CMS_SCHLUESSEL'), AES_ENCRYPT('10', '$CMS_SCHLUESSEL'))");
	$sql->execute();
	$sql = $dbs->prepare("INSERT INTO schulanmeldung (id, inhalt, wert) VALUES (1, AES_ENCRYPT('Anmeldung Einschulungsalter', '$CMS_SCHLUESSEL'), AES_ENCRYPT('6', '$CMS_SCHLUESSEL'))");
	$sql->execute();
	$sql = $dbs->prepare("INSERT INTO schulanmeldung (id, inhalt, wert) VALUES (2, AES_ENCRYPT('Anmeldung Klassenstufe', '$CMS_SCHLUESSEL'), AES_ENCRYPT('4', '$CMS_SCHLUESSEL'))");
	$sql->execute();
	$sql = $dbs->prepare("INSERT INTO schulanmeldung (id, inhalt, wert) VALUES (3, AES_ENCRYPT('Anmeldung Einleitung', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	$sql->execute();
	$sql = $dbs->prepare("INSERT INTO schulanmeldung (id, inhalt, wert) VALUES (4, AES_ENCRYPT('Anmeldung aktiv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute();

	$id = 0;
	$sql = $dbs->prepare("DELETE FROM allgemeineeinstellungen");
	$sql->execute();
	for ($i=1; $i<=2; $i++) {
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i existiert', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Geschlecht', '$CMS_SCHLUESSEL'), AES_ENCRYPT('m', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Vorname', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Pavel', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Nachname', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Kaplin', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Titel', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Externe Geräteverwaltung$i Mail', '$CMS_SCHLUESSEL'), AES_ENCRYPT('pavel.kaplin@schorndorf.de', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
	}

	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Download aus sichtbaren Gruppen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Menüseiten weiterleiten', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Klassen extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Schüler aktuell', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Schüler Folgetag', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Lehrer aktuell', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Vertretungsplan Lehrer Folgetag', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Persönlicher Vertretungsplan nach ...', '$CMS_SCHLUESSEL'), AES_ENCRYPT('Kursen', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Lehrer extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Raum extern', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsbeginn Stunde', '$CMS_SCHLUESSEL'), AES_ENCRYPT('07', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsbeginn Minute', '$CMS_SCHLUESSEL'), AES_ENCRYPT('00', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsende Stunde', '$CMS_SCHLUESSEL'), AES_ENCRYPT('22', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Stundenplan Buchungsende Minute', '$CMS_SCHLUESSEL'), AES_ENCRYPT('00', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung aktiv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung Anmeldung notwendig', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung an GitHub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Feedback aktiv', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Feedback Anmeldung notwendig', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung an GitHub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Reaktionen auf Blogeinträge', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Reaktionen auf Termine', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Reaktionen auf Galerien', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Chat Nachrichten löschen nach', '$CMS_SCHLUESSEL'), AES_ENCRYPT('21', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Tagebuch Frist Abewsenheit', '$CMS_SCHLUESSEL'), AES_ENCRYPT('s', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Tagebuch Frist Inhalt', '$CMS_SCHLUESSEL'), AES_ENCRYPT('2', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Tagebuch Frist Lob und Tadel', '$CMS_SCHLUESSEL'), AES_ENCRYPT('s', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Tagebuch Frist Hausaufgaben', '$CMS_SCHLUESSEL'), AES_ENCRYPT('2', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Tagebuch Frist Entschuldigungen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('7', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Tagebuch Mindestabwesenheit', '$CMS_SCHLUESSEL'), AES_ENCRYPT('10', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	// Postfach
	foreach ($personen as $p) {
		if ($p == 'Lehrer') {$genehmigung = '1';} else {$genehmigung = '0';}
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen persönliche Termine anlegen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen persönliche Notizen anlegen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen intern Termine vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen intern Blogeinträge vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen Termine vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen Blogeinträge vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$p dürfen Galerien vorschlagen', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
	}
	foreach ($personen as $a) {
		foreach ($personen as $e) {
			$genehmigung = '0';
			if ($a == 'Lehrer') {
				if (($e == 'Lehrer') || ($e == 'Verwaltungsangestellte') || ($e == 'Externe')) {$genehmigung = '1';}
			}
			if ($a == 'Verwaltungsangestellte') {
				if (($e == 'Lehrer') || ($e == 'Verwaltungsangestellte') || ($e == 'Externe')) {$genehmigung = '1';}
			}
			if ($a == 'Externe') {
				if (($e == 'Lehrer') || ($e == 'Verwaltungsangestellte') || ($e == 'Externe')) {$genehmigung = '1';}
			}
			$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Postfach - $a dürfen $e schreiben', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
			$sql->execute(); $id++;
		}
	}
	foreach ($personen as $p) {
		if ($p == 'Lehrer') {$genehmigung = '1';} else {$genehmigung = '0';}
		foreach ($gruppen as $g) {
			foreach ($raenge as $r) {
				$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Postfach - $p dürfen $g $r schreiben', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
				$sql->execute(); $id++;
			}
		}
	}
	foreach ($gruppen as $g) {
		foreach ($personen as $p) {
			foreach ($raenge as $r) {
				$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('$r $g $p', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))");
				$sql->execute(); $id++;
			}
		}
	}
	$genehmigung = '1';
	foreach ($gruppen as $g) {
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Genehmigungen $g Termine', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		$sql = $dbs->prepare("INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Genehmigungen $g Blogeinträge', '$CMS_SCHLUESSEL'), AES_ENCRYPT('$genehmigung', '$CMS_SCHLUESSEL'))");
		$sql->execute(); $id++;
		if ($g == 'Fachschaften') {$genehmigung = '0';}
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
	$sql = $dbs->prepare("CREATE TABLE `dauerbrenner` (
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
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
	$sql->execute();
	*/
	cms_trennen($dbs);
}


if ((!$rechteplaetten) && (!$internediensteplaetten) && (!$dateienplaetten) && (!$einstellungenplaetten) && (!$zulaessigedateienplaetten) && (!$gremienklassen) && (!$postfachordner) && (!$update)) {
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

if ($internediensteplaetten) {
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/schulhof/funktionen/config.php");

	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("DELETE FROM internedienste");
	$sql->execute();
	$id = 0;

	$sql = $dbs->prepare("INSERT INTO internedienste (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('VPlanS', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0815', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	$sql = $dbs->prepare("INSERT INTO internedienste (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('VPlanL', '$CMS_SCHLUESSEL'), AES_ENCRYPT('0815', '$CMS_SCHLUESSEL'))");
	$sql->execute(); $id++;
	cms_trennen($dbs);
	echo "INTERNE DIENSTE ERNEUERT<br>";
}


echo "<br><br>";
cms_dateisystem_ds_loeschen(".");
echo "AUFGERÄUMT";


?>
