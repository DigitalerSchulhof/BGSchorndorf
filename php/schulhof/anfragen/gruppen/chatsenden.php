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

  if(!$nachricht && $nachricht != "0")
    $fehler = true;

	if (!$fehler) {
		$gk = cms_textzudb($g);
		$nachricht = str_replace(chr(29), "", $nachricht);
    $nachricht = htmlentities($nachricht);
		$id = cms_generiere_kleinste_id($gk."chat");
		$sql = "UPDATE $gk"."chat SET gruppe = ?, person = ?, datum = ?, inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), meldestatus = 0, fertig = 1 WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiisi", $gid, $person, $jetzt, $nachricht, $id);
    $sql->execute();
		echo "ERFOLG";
		echo $id;
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
