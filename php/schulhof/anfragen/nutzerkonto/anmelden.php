<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['benutzername'])) {$benutzername = cms_texttrafo_e_db($_POST['benutzername']);} else {echo "FEHLER"; exit;}
if (isset($_POST['passwort'])) {$passwort = $_POST['passwort'];} else {echo "FEHLER"; exit;}

$fehler = false;

// Pflichteingaben prüfen
if (strlen($benutzername) < 6) {echo "FEHLER"; exit;}
if (strlen($passwort) == 0) {echo "FEHLER"; exit;}
$CMS_EINSTELLUNGEN = cms_einstellungen_laden("allgemeineeinstellungen");

$dbs = cms_verbinden('s');
// Prüfen, ob das Passwort noch gilt, und ob Benutzername und Passwort zusammen passen
$jetzt = time();

$sql = $dbs->prepare("SELECT personen.id AS id, letzteanmeldung, AES_DECRYPT(salt, '$CMS_SCHLUESSEL') AS salt, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, schuljahr, AES_DECRYPT(uebersichtsanzahl, '$CMS_SCHLUESSEL') AS uebersichtsanzahl, AES_DECRYPT(inaktivitaetszeit, '$CMS_SCHLUESSEL') AS inaktivitaetszeit, passworttimeout FROM personen JOIN nutzerkonten ON personen.id = nutzerkonten.id JOIN personen_einstellungen ON personen.id = personen_einstellungen.person WHERE benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND (passworttimeout = 0 OR passworttimeout > ?);");
$sql->bind_param("ss", $benutzername, $jetzt);

if ($sql->execute()) {
  $id = "";
  $sql->bind_result($id, $letzteanmeldung, $salt, $art, $titel, $vorname, $nachname, $schuljahr, $uebersichtsanzahl, $inaktivitaetszeit, $passworttimeout);
  if (!$sql->fetch()) {$fehler = true;}
}
else {$fehler = true;}
$sql->close();

if (!$fehler) {
	$passwortsalted = $passwort.$salt;
	$passwortsalted = cms_texttrafo_e_db($passwortsalted);

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM nutzerkonten WHERE passwort = SHA1(?) AND id = ?");
	$sql->bind_param("si", $passwortsalted, $id);

	if ($sql->execute()) {
	  $anzahl = 0;
	  $sql->bind_result($anzahl);
	  if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		}
		else {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();
}

cms_trennen($dbs);

if (!$fehler) {
	// SESSIONID GENERIEREN
	$sessionid = cms_generiere_sessionid();
	// Inaktivitätszeit von Minuten in Sekunden später wird der Nutzer automatisch abgemeldet
	$jetzt = time();
	$sessiontimeout = $jetzt + $inaktivitaetszeit*60;

	// PERSON UPDATEN - SESSIONID eintragen
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("UPDATE nutzerkonten SET sessionid = ?, sessiontimeout = ?, vorletzteanmeldung = letzteanmeldung, letzteanmeldung = ? WHERE id = ?");
	$sql->bind_param("siii", $sessionid, $sessiontimeout, $jetzt, $id);
	$sql->execute();
	$sql->close();

	if (file_exists("../../../dateien/schulhof/personen/$id/postfach/temp")) {cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$id/postfach/temp");}
	if (!file_exists("../../../dateien/schulhof/personen/$id/postfach/temp")) {mkdir("../../../dateien/schulhof/personen/$id/postfach/temp", 0775);}

	// SESSION SETZEN
	session_start();
	$_SESSION['BENUTZERNAME'] = $benutzername;
	$_SESSION['SESSIONID'] = $sessionid;
	$_SESSION['SESSIONTIMEOUT'] = $sessiontimeout;
	$_SESSION['SESSIONAKTIVITAET'] = $inaktivitaetszeit;
	$_SESSION['BENUTZERTITEL'] = $titel;
	$_SESSION['BENUTZERVORNAME'] = $vorname;
	$_SESSION['BENUTZERNACHNAME'] = $nachname;
	$_SESSION['BENUTZERID'] = $id;
	$_SESSION['BENUTZERART'] = $art;
	$_SESSION['BENUTZERSCHULJAHR'] = $schuljahr;
	$_SESSION['NACHRICHTID'] = "-";
	$_SESSION['NACHRICHTLESENID'] = "-";
	$_SESSION['LETZTENLOGINANZEIGEN'] = $letzteanmeldung;
	$_SESSION['PASSWORTTIMEOUT'] = $passworttimeout;

	$_SESSION['DSGVO_FENSTERWEG'] = true;
	$_SESSION['DSGVO_EINWILLIGUNG_A'] = true;

	$_SESSION['BENUTZERUEBERSICHTANZAHL'] = $uebersichtsanzahl;

	$_SESSION["KALENDERANSICHTTAG"] = '1';
	$_SESSION["KALENDERANSICHTWOCHE"] = '0';
	$_SESSION["KALENDERANSICHTMONAT"] = '0';
	$_SESSION["KALENDERANSICHTJAHR"] = '0';
	$_SESSION["KALENDERANSICHT"] = 'tag';
	$_SESSION["KALENDERTERMINEPERSOENLICH"] = '1';
	$_SESSION["KALENDERTERMINEOEFFENTLICH"] = '1';
	$_SESSION["KALENDERTERMINEFERIEN"] = '1';
	$_SESSION["KALENDERTERMINESICHTBAR"] = '0';
	foreach ($CMS_GRUPPEN as $g) {
		$_SESSION["KALENDERGRUPPEN: ".$g] = '0';
	}

	cms_trennen($dbs);

	echo "ERFOLG".strtoupper($art).$CMS_EINSTELLUNGEN['Netze Lehrerserver'];
}
else {
	echo "FEHLER";
}
?>
