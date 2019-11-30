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
if (isset($_POST['lehrer'])) {$lehrer = cms_texttrafo_e_db($_POST['lehrer']);} else {echo "FEHLER"; exit;}
if (isset($_POST['schueler'])) {$schueler = cms_texttrafo_e_db($_POST['schueler']);} else {echo "FEHLER"; exit;}

$lehrer = str_replace('<br></p>', '</p>', $lehrer);
$lehrer = str_replace('<p></p>', '', $lehrer);
$lehrer = cms_texttrafo_e_db($lehrer);

$schueler = str_replace('<br></p>', '</p>', $schueler);
$schueler = str_replace('<p></p>', '', $schueler);
$schueler = cms_texttrafo_e_db($schueler);

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
  $fehler = false;
  $dbs = cms_verbinden('s');
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $VPLANLEHRER = 0;
  $VPLANSCHUELER = 0;

  // Vertretungstext laden
  $sql = $dbs->prepare("SELECT COUNT(*) FROM vplantext WHERE zeit = ? AND art = 'l'");
  $sql->bind_param("i", $hb);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {
      $VPLANLEHRER = $anzahl;
    } else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  $sql = $dbs->prepare("SELECT COUNT(*) FROM vplantext WHERE zeit = ? AND art = 's'");
  $sql->bind_param("i", $hb);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {
      $VPLANSCHUELER = $anzahl;
    } else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  if (!$fehler) {
    if ($VPLANLEHRER + $VPLANSCHUELER < 2) {
      $sql = $dbs->prepare("INSERT INTO vplantext SET zeit = ?, art = ?, inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
      if ($VPLANLEHRER == 0) {
        $art = 'l';
        $sql->bind_param("iss", $hb, $art, $lehrer);
        $sql->execute();
      }
      if ($VPLANSCHUELER == 0) {
        $art = 's';
        $sql->bind_param("iss", $hb, $art, $schueler);
        $sql->execute();
      }
      $sql->close();
    }
    else if ($VPLANLEHRER + $VPLANSCHUELER > 0) {
      $sql = $dbs->prepare("UPDATE vplantext SET inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE zeit = ? AND art = ?");
      if ($VPLANLEHRER == 1) {
        $art = 'l';
        $sql->bind_param("sis", $lehrer, $hb, $art);
        $sql->execute();
      }
      if ($VPLANSCHUELER == 1) {
        $art = 's';
        $sql->bind_param("sis", $schueler, $hb, $art);
        $sql->execute();
      }
      $sql->close();
    }
    echo "ERFOLG";
  }
  else {echo "FEHLER";}
  cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
