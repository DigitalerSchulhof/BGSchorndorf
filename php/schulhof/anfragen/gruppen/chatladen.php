<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "gruppenid"));
$g = $gruppe;
$gid = $gruppenid;
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($g)) {echo "FEHLER"; exit;}
if (isset($_SESSION["LETZTENACHRICHT_$g"]["$gid"])) {$letzte = $_SESSION["LETZTENACHRICHT_$g"]["$gid"];} else {echo "FEHLER"; exit;}
$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);

$zugriff = $CMS_GRUPPENRECHTE['mitglied'];

$namecache = array();

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!$fehler) {
		$gk = cms_textzudb($g);

    $sql = "SELECT id, person, datum, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') as inhalt, meldestatus FROM $gk"."chat WHERE gruppe = ? AND id > ? ORDER BY id ASC;";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $gid, $letzte);
    $sql->bind_result($id, $p, $d, $i, $m);
    $sql->execute();
    $nachrichten = array();
    while($sql->fetch()) {
        if($p != $person) {
          if(array_key_exists($p, $namecache))
            return $namecache[$p];
          $sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') as vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') as nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') as titel FROM personen WHERE id = ?";

          $sql = $dbs->prepare($sql);
          $sql->bind_param("i", $p);
          $sql->bind_result($vorname, $nachname, $titel);
          $sql->execute();
          $sql->fetch();
          $name = cms_generiere_anzeigename($vorname, $nachname, $titel);
          $namecache[$p] = $name;
          array_push($nachrichten, array($id, $name, $d, $i, $m));
        }
        $_SESSION["LETZTENACHRICHT_$g"]["$gid"] = $id??-1;
    }
    $del = chr(29);
    /*
      Die Antwort wird als ","-getrennter String zurück gegeben.
    */
    foreach($nachrichten as $i => $v)
      echo $v[0].$del.$v[1].$del.$v[2].$del.$v[3].$del;
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
