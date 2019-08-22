<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "gruppenid", "nachricht"));
$g = $gruppe;
$gid = $gruppenid;
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($g)) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);
$jetzt = time();

$zugriff = $CMS_GRUPPENRECHTE['mitglied'] && $CMS_GRUPPENRECHTE["chatten"] && $CMS_GRUPPENRECHTE["chattenab"] <= $jetzt;

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

  if(!$nachricht)
    $fehler = true;

	if (!$fehler) {
		$gk = cms_textzudb($g);
    $nachricht = htmlentities($nachricht);

    $sql = "INSERT INTO $gk"."chat (gruppe, person, datum, inhalt, meldestatus, gemeldetvon, gemeldetam) VALUES (?, ?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), 0, '', 0);";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiis", $gid, $person, $jetzt, $nachricht);
    $sql->execute();
		echo "ERFOLG";
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
