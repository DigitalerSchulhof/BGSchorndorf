<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {
	// Nachrichten löschen, die Limits überschreiten

	// Speicherfristen berechnen
	$jetzt = time();
	$pdauer = 30;
	$adauer = 360;

	$sql = $dbs->prepare("SELECT AES_DECRYPT(postpapierkorbtage, '$CMS_SCHLUESSEL') AS ptage, AES_DECRYPT(postalletage, '$CMS_SCHLUESSEL') AS atage FROM personen_einstellungen WHERE person = ?;");
	$sql->bind_param("i", $CMS_BENUTZERID);

	if ($sql->execute()) {
	  $sql->bind_result($pdauer, $adauer);
	  if (!$sql->fetch()) {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();

	// Von Tagen zu Sekunden
	$adauer = $adauer*86400;
	$pdauer = $pdauer*86400;

	$dbp = cms_verbinden('p');

	// Mit alten Nachrichten auch alte Ordner löschen
	$sql = $dbp->prepare("SELECT id FROM posteingang_$CMS_BENUTZERID WHERE empfaenger = ? AND ((papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND $jetzt-papierkorbseit > ?) OR ($jetzt-zeit > ?))");
	$sql->bind_param("iii", $CMS_BENUTZERID,$pdauer,$adauer);
	if ($sql->execute()) {
		$sql->bind_result($sid);
	  while ($sql->fetch()) {
			if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/eingang/".$sid)) {cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/eingang/".$sid);}
		}
	}
	$sql->close();

	$sql = $dbp->prepare("SELECT id FROM postentwurf_$CMS_BENUTZERID WHERE absender = ? AND ((papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND $jetzt-papierkorbseit > ?) OR ($jetzt-zeit > ?))");
	$sql->bind_param("iii", $CMS_BENUTZERID,$pdauer,$adauer);
	if ($sql->execute()) {
		$sql->bind_result($sid);
	  while ($sql->fetch()) {
	    if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/entwuerfe/".$sid)) {cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/entwuerfe/".$sid);}
	  }
	}
	$sql->close();

	$sql = $dbp->prepare("SELECT id FROM postausgang_$CMS_BENUTZERID WHERE absender = ? AND ((papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND $jetzt-papierkorbseit > ?) OR ($jetzt-zeit > ?))");
	$sql->bind_param("iii", $CMS_BENUTZERID,$pdauer,$adauer);
	if ($sql->execute()) {
		$sql->bind_result($sid);
	  while ($sql->fetch()) {
	    if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/ausgang/".$sid)) {cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/ausgang/".$sid);}
	  }
	}
	$sql->close();

	// Alte Nachrichten löschen
	$sql = $dbp->prepare("DELETE FROM posteingang_$CMS_BENUTZERID WHERE empfaenger = ? AND ((papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND $jetzt-papierkorbseit > ?) OR ($jetzt-zeit > ?))");
	$sql->bind_param("iii", $CMS_BENUTZERID,$pdauer,$adauer);
	$sql->execute();
	$sql->close();

	$sql = $dbp->prepare("DELETE FROM postentwurf_$CMS_BENUTZERID WHERE absender = ? AND ((papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND $jetzt-papierkorbseit > ?) OR ($jetzt-zeit > ?))");
	$sql->bind_param("iii", $CMS_BENUTZERID,$pdauer,$adauer);
	$sql->execute();
	$sql->close();

	$sql = $dbp->prepare("DELETE FROM postausgang_$CMS_BENUTZERID WHERE absender = ? AND ((papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND $jetzt-papierkorbseit > ?) OR ($jetzt-zeit > ?))");
	$sql->bind_param("iii", $CMS_BENUTZERID,$pdauer,$adauer);
	$sql->execute();
	$sql->close();
	cms_trennen($dbp);

	if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp")) {cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp");}
	if (!file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp")) {mkdir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", 0775);}
}

// Nutzer in der Datenbank abmelden
$jetzt = time();

$sql = $dbs->prepare("UPDATE nutzerkonten SET sessionid = '', sessiontimeout = 0 WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
$sql->execute();
$sql->close();
cms_trennen($dbs);


// SESSION löschen
session_destroy();

session_start();
$_SESSION['DSGVO_EINWILLIGUNG_A'] = true;
$_SESSION['DSGVO_FENSTERWEG'] = true;

echo "ERFOLG";
?>
