<?php
include_once('php/allgemein/funktionen/sql.php');
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/generieren.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
$dbs = cms_verbinden('s');
$dbp = cms_verbinden('p');

$PERSONEN = array();

$sql = $dbs->prepare("SELECT id FROM nutzerkonten WHERE id IN (SELECT id FROM personen WHERE art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL'))");
if ($sql->execute()) {
  $sql->bind_result($nid);
  while ($sql->fetch()) {
    array_push($PERSONEN, $nid);
  }
}
$sql->close();

$anzahl = 0;

foreach ($PERSONEN as $P) {
  $eingang = array();
  $sql = $dbp->prepare("SELECT id, alle FROM posteingang_$P");
  if ($sql->execute()) {
    $sql->bind_result($peid, $pealle);
    while ($sql->fetch()) {
      if (strlen($pealle) > 180) {
        array_push($eingang, $peid);
      }
    }
  }
  $sql->close();

  if (count($eingang) > 0) {
    $sql = $dbp->prepare("UPDATE posteingang_$P SET alle = ? WHERE id = ?");
    $alleneu = "|".$P;
    foreach ($eingang as $e) {
      $sql->bind_param("si", $alleneu, $e);
      $sql->execute();
      echo "Empfänger aus Nachricht $e für ".$P." entfernt<br>";
      $anzahl ++;
    }
    $sql->close();

    /*$anzahl ++;
    echo "Nachrichten ".$P." mit vielen Empfängern<br>";
    print_r($eingang);
    echo "<br><br>";*/
  }

}


echo "TOTAL:".$anzahl;

cms_trennen($dbp);
cms_trennen($dbs);
// $sql = $dbs->prepare("SELECT id FROM unterricht WHERE tkurs IN (SELECT id FROM kurse WHERE stufe IN (SELECT id FROM stufen WHERE tagebuch = 1))");
// $tagebuch = array();
// if ($sql->execute()) {
//   $sql->bind_result($tid);
//   while ($sql->fetch()) {
//     echo "INSERT INTO tagebuch (id) VALUES ($tid);<br>";
//   }
// }
// $sql->close();
// cms_trennen($dbs);
//
// foreach ($CMS_GRUPPEN AS $g) {
//   $gk = cms_textzudb($g);
//   echo "ALTER TABLE `$gk"."termineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
//   echo "ALTER TABLE `$gk"."termineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
// }

// $dbs->query("ALTER TABLE `rollen` DROP `personenart`");
// $dbs->query("DELETE FROM `rollen` WHERE id > 0");
// $dbs->query("INSERT INTO `rollen` (`id`, `bezeichnung`, `idvon`, `idzeit`) VALUES (1, AES_ENCRYPT('Lehrer', '$CMS_SCHLUESSEL'), NULL, NULL), (2, AES_ENCRYPT('Schüler', '$CMS_SCHLUESSEL'), NULL, NULL), (3, AES_ENCRYPT('Verwaltung', '$CMS_SCHLUESSEL'), NULL, NULL), (4, AES_ENCRYPT('Eltern', '$CMS_SCHLUESSEL'), NULL, NULL), (5, AES_ENCRYPT('Externe', '$CMS_SCHLUESSEL'), NULL, NULL);");
// $dbs->query("INSERT INTO `bedingterollen` (`rolle`, `bedingung`) VALUES (1, AES_ENCRYPT('nutzer.art==\"l\"', '$CMS_SCHLUESSEL')), (2, AES_ENCRYPT('nutzer.art==\"s\"', '$CMS_SCHLUESSEL')), (3, AES_ENCRYPT('nutzer.art==\"v\"', '$CMS_SCHLUESSEL')), (4, AES_ENCRYPT('nutzer.art==\"e\"', '$CMS_SCHLUESSEL')), (5, AES_ENCRYPT('nutzer.art==\"x\"', '$CMS_SCHLUESSEL'));");

?>
