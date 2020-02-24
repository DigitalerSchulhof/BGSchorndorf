<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['notfall'])) 	  {$notfall = $_POST['notfall'];} 		            else {cms_anfrage_beenden(); exit;}

if (!cms_check_toggle($notfall)) {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

//$zugriff = $CMS_RECHTE['Tagebücher']['Notfallzustand'];
$zugriff = true;

if ($angemeldet && $zugriff) {
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');


	$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Tagebuch Notfallzustand', '$CMS_SCHLUESSEL')");
  $sql->bind_param("s", $notfall);
	$sql->execute();
  $sql->close();

  if ($notfall == 0) {
    $sql = $dbs->prepare("DELETE FROM notfallzustand");
    $sql->execute();
    $sql->close();
  }
  else {
    $jetzt = time();
    $ABWESEND = array();
    $sql = $dbl->prepare("SELECT person FROM fehlzeiten WHERE von <= ? AND bis >= ?");
    $sql->bind_param("ii", $jetzt, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($p);
      while ($sql->fetch()) {
        array_push($ABWESEND);
      }
    }
    $sql->close();

    if (count($ABWESEND) > 0) {
      $ab = implode(",", $ABWESEND);
      $sql = $dbs->prepare("INSERT INTO notfallzustand (lehrer, schueler) SELECT DISTINCT tlehrer, person FROM unterricht JOIN kursemitglieder ON tkurs = gruppe JOIN personen ON person = personen.id WHERE tbeginn <= ? AND tende >= ? AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND personen.id NOT IN ($ab)");
      $sql->bind_param("ii", $jetzt, $jetzt);
      $sql->execute();
      $sql->close();
    }
    else {
      $sql = $dbs->prepare("INSERT INTO notfallzustand (lehrer, schueler) SELECT DISTINCT tlehrer, person FROM unterricht JOIN kursemitglieder ON tkurs = gruppe JOIN personen ON person = personen.id WHERE tbeginn <= ? AND tende >= ? AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
      $sql->bind_param("ii", $jetzt, $jetzt);
      $sql->execute();
      $sql->close();
    }


  }

  cms_trennen($dbl);
  cms_trennen($dbs);
	cms_lehrerdb_header(true);
  echo "ERFOLG";
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
