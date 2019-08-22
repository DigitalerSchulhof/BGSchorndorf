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
if (isset($_SESSION["LETZENACHRICHT_$g"]["$gid"])) {$letzte = $_SESSION["LETZENACHRICHT_$g"]["$gid"];} else {echo "FEHLER"; exit;}
if(!$letzte || !is_array($letzte))
  $fehler = true;

$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);

$zugriff = $CMS_GRUPPENRECHTE['mitglied'];

$namecache = array();

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!$fehler) {
		$gk = cms_textzudb($g);

    $sql = "SELECT id, person, datum, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') as inhalt, meldestatus, gemeldetvon, gemeldetam FROM $gk"."chat WHERE gruppe = ? AND datum >= ? ORDER BY datum ASC;";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $gid, $letzte[1]);
    $sql->bind_result($id, $p, $d, $i, $m, $gv, $ga);
    $sql->execute();
    $f = false;
    $nachrichten = array();
    while($sql->fetch()) {
      if($p == $letzte[0] && !$f)
        $f = true;
      else
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
          array_push($nachrichten, array($id, $name, $d, $i, $m, $gv, $ga));
        }
    }
    $_SESSION["LETZENACHRICHT_$g"]["$gid"] = array($p??-1, $d??0);
    $del = chr(29);
    /*
      Die Antwort wird als ","-getrennter String zurück gegeben.
      Falls Meldestatus 0 ist, werden Melder und Meldezeitpunkt ausgelassen und der nächste Eintrag beginnt.
    */
    foreach($nachrichten as $i => $v) {
      echo $v[0].$del.$v[1].$del.$v[2].$del.$v[3].$del.$v[4].$del;
      if($v[4])
        echo $v[5].$del.$v[6].$del;
    }
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
