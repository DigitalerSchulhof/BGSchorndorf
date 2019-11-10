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
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (($art != 'von') && ($art != 'bis')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Ausplanungen durchführen'];

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');
  $heute = mktime(0,0,0,$monat, $tag, $jahr);
  $SCHULSTUNDEN = array();
  $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum IN (SELECT id FROM zeitraeume WHERE beginn <= ? AND ende >= ?)) AS x ORDER BY beginns, beginnm ASC";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $heute, $heute);
  if ($sql->execute()) {
    $sql->bind_result($id, $bez, $beginns, $beginnm, $endes, $endem);
    while ($sql->fetch()) {
      $s = array();
      $s['id'] = $id;
      $s['bez'] = $bez;
      array_push($SCHULSTUNDEN, $s);
    }
  }
  $sql->close();
  cms_trennen($dbs);

  if ($art == 'von') {
    foreach ($SCHULSTUNDEN as $s) {
      echo "<option value=\"".$s['id']."\">".$s['bez']."</option>";
    }
  }
  else {
    if (count($SCHULSTUNDEN) > 0) {
      for ($s=0;$s<count($SCHULSTUNDEN)-1; $s++) {
        echo "<option value=\"".$SCHULSTUNDEN[$s]['id']."\">".$SCHULSTUNDEN[$s]['bez']."</option>";
      }
      echo "<option value=\"".$SCHULSTUNDEN[count($SCHULSTUNDEN)-1]['id']."\" selected=\"selected\">".$SCHULSTUNDEN[count($SCHULSTUNDEN)-1]['bez']."</option>";
    }
  }
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
