<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['gruppenid'])) 	{$gruppenid = $_POST['gruppenid'];} 		  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['gruppenart'])) 	 {$gruppenart = $_POST['gruppenart'];} 		else {cms_anfrage_beenden(); exit;}
if ($gruppenart == 'klasse') {
  if (isset($_POST['beginn']))   {$beginn = $_POST['beginn'];} 		          else {cms_anfrage_beenden(); exit;}
  if (isset($_POST['ansicht']))  {$ansicht = $_POST['ansicht'];} 		        else {cms_anfrage_beenden(); exit;}
}


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

$fehler = false;
if ($gruppenart != 'klasse' && $gruppenart != 'kurs') {$fehler = true;}
if ($gruppenart == 'klasse') {
  if (!cms_check_ganzzahl($beginn)) {$fehler = true;}
  if ($ansicht != 'w' && $ansicht != 'v') {$fehler = true;}
}

if ($angemeldet && $CMS_BENUTZERART == 'l') {

  if (!$fehler) {
    include_once("../../lehrerzimmer/anfragen/tagebuch/tagebuchladen.php");

    $code = "";
    if ($gruppenart == "klasse") {
      if ($ansicht == 'w') {
        $t = date("d", $beginn);
        $m = date("m", $beginn);
        $j = date("Y", $beginn);
        for ($i=0; $i<7; $i++) {
          $jetzt = mktime(0,0,0,$m,$t,$j);
          $t = date("d", $jetzt);
          $m = date("m", $jetzt);
          $j = date("Y", $jetzt);
          $code .= "<h2>".cms_tagnamekomplett(date("N", $jetzt)).", den $t. ".cms_monatsnamekomplett($m)." $j</h2>";
          $code .= cms_tagebucheintrag_tag($dbs, $dbl, $gruppenid, $t, $m, $j);
          $t++;
        }
      } else {
        $min = 0;
        $max = 0;
        $sql = $dbs->prepare("SELECT min(tbeginn), max(tende) FROM unterricht WHERE tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?)");
        $sql->bind_param("i", $gruppenid);
        if ($sql->execute()) {
          $sql->bind_result($min, $max);
          $sql->fetch();
        }
        $sql->close();

        $t = date("d", $min);
        $m = date("m", $min);
        $j = date("Y", $min);
        $jetzt = mktime(0,0,0,$m,$t,$j);
        while ($jetzt < $max) {
          $jetzt = mktime(0,0,0,$m,$t,$j);
          $t = date("d", $jetzt);
          $m = date("m", $jetzt);
          $j = date("Y", $jetzt);
          $code .= "<h2>".cms_tagnamekomplett(date("N", $jetzt)).", den $t. ".cms_monatsnamekomplett($m)." $j</h2>";
          $code .= cms_tagebucheintrag_tag($dbs, $dbl, $gruppenid, $t, $m, $j);
          $t++;
        }
      }
    } else {
      $jetzt = time();
      $t = date("d", $jetzt);
      $m = date("m", $jetzt);
      $j = date("Y", $jetzt);
      $code .= cms_tagebucheintrag_kurs($dbs, $dbl, $gruppenid, $t, $m, $j);
    }

  	cms_lehrerdb_header(true);
    echo $code;
  }
  else {
    cms_lehrerdb_header(false);
  	echo cms_meldung_fehler();
  }

}
else {
  cms_lehrerdb_header(false);
	echo cms_meldung_berechtigung();
}

cms_trennen($dbl);
cms_trennen($dbs);
?>
