<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "gruppenid", "id"));
$g = $gruppe;
$gid = $gruppenid;
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($g)) {echo "FEHLER"; exit;}
$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);
$jetzt = time();

$zugriff = $CMS_GRUPPENRECHTE['mitglied'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

  if(!cms_check_ganzzahl($id, 0))
    $fehler = true;
	if (!$fehler) {
		$gk = cms_textzudb($g);
		$sql = "UPDATE $gk"."chat SET meldestatus = 1 WHERE id = ? AND gruppe = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $id, $gid);
    $sql->execute();
    $sql = "INSERT INTO $gk"."chatmeldungen (nachricht, melder, meldezeitpunkt) VALUES (?, ?, ?);";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iii", $id, $person, $jetzt);
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
