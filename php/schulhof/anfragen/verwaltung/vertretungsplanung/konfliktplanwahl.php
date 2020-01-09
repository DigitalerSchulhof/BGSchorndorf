<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['planart'])) {$planart = $_POST['planart'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (($planart != 'l') && ($planart != 'r') && ($planart != 'k') && ($planart != 's')) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.planung.vertretungsplan.vertretungsplanung")) {
  $dbs = cms_verbinden('s');
  $AUSGABE = array();
  $fehler = false;

  if ($planart == 'l') {
    $sql = "SELECT * FROM (SELECT personen.id, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN lehrer on personen.id = lehrer.id) AS x ORDER BY kuerzel, nachname, vorname, titel";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($lid, $lkurz, $lvor, $lnach, $ltitel);
      while ($sql->fetch()) {
        $bez = "";
        if (strlen($lkurz) > 0) {$bez = $lkurz.' - ';}
        $bez .= cms_generiere_anzeigename($lvor, $lnach, $ltitel);
        $a = array();
        $a['id'] = $lid;
        $a['bez'] = $bez;
        array_push($AUSGABE, $a);
      }
    }
    $sql->close();
  }
  else if ($planart == 'r') {
    // Räume laden
    $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT raeume.id AS id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS zusatzk, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS zusatzs FROM raeume LEFT JOIN raeumeklassen ON raeume.id = raeumeklassen.raum LEFT JOIN klassen ON raeumeklassen.klasse = klassen.id LEFT JOIN raeumestufen ON raeume.id = raeumestufen.raum LEFT JOIN stufen ON raeumestufen.stufe = stufen.id)) AS x GROUP BY id ORDER BY bezeichnung ASC");
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $zusatzk, $zusatzs);
      while ($sql->fetch()) {
        $a = array();
        $rbez = $bez;
        $zusatz = "";
        if ($zusatzk !== null) {$zusatz .= ", ".$zusatzk;}
        if ($zusatzs !== null) {$zusatz .= ", ".$zusatzs;}
        if (strlen($zusatz) > 0) {$rbez .= " » ".substr($zusatz, 2);}
        $a['id'] = $id;
        $a['bez'] = $rbez;
        array_push($AUSGABE, $a);
      }
    }
    $sql->close();
  }
  else {
    $hb = mktime(0,0,0,$monat, $tag, $jahr);
    // Schuljahr laden
    $sql = $dbs->prepare("SELECT COUNT(*), id FROM schuljahre WHERE ? BETWEEN beginn AND ende");
    $sql->bind_param("i", $hb);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $SCHULJAHR);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();

    if (!$fehler) {
      if ($planart == 'k') {
        $sql = "SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, bez";

        $sql = $dbs->prepare($sql);
        $sql->bind_param("i", $SCHULJAHR);
        if ($sql->execute()) {
          $sql->bind_result($kid, $kbez, $kreihenfolge);
          while ($sql->fetch()) {
            $a = array();
            $a['id'] = $kid;
            $a['bez'] = $kbez;
            array_push($AUSGABE, $a);
          }
        }
        $sql->close();
      }
      if ($planart == 's') {
        $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge";
        $sql = $dbs->prepare($sql);
        $sql->bind_param("i", $SCHULJAHR);
        if ($sql->execute()) {
          $sql->bind_result($sid, $sbez);
          while ($sql->fetch()) {
            $a = array();
            $a['id'] = $sid;
            $a['bez'] = $sbez;
            array_push($AUSGABE, $a);
          }
        }
        $sql->close();
      }

    }
  }


  cms_trennen($dbs);

  if ($fehler) {
    echo "FEHLER";
  }
  else {
    $code = "";
    foreach ($AUSGABE AS $a) {
      $code .= "<option value=\"".$a['id']."\">".$a['bez']."</option>";
    }
    echo $code;
  }
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
