<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['unterricht'])) 	{$unterricht = $_POST['unterricht'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag'])) 	 {$t = $_POST['tag'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat']))   {$m = $_POST['monat'];} 		      else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr'])) 	  {$j = $_POST['jahr'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['klasse'])) 	{$k = $_POST['klasse'];} 		      else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

// Daten übertragen

$dbs = cms_verbinden('s');
$dbl = cms_verbinden('l');

include_once("../../lehrerzimmer/anfragen/tagebuch/uebertragen.php");

// Benutzerart laden
$CMS_BENUTZERART = "";
$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
  $sql->bind_result($CMS_BENUTZERART);
  $sql->fetch();
}
$sql->close();

if ($angemeldet && $CMS_BENUTZERART == 'l') {

  include_once("../../lehrerzimmer/anfragen/tagebuch/tagebuchladen.php");

  $code = cms_tagebucheintrag_tag($dbs, $dbl, $k, $t, $m, $j);

	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}

cms_trennen($dbl);
cms_trennen($dbs);
?>
