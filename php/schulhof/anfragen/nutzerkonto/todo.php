<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/brotkrumen.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();
// Variablen einlesen, falls übergeben
postLesen("g", "gid", "a", "aid", "status");
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}
if (!cms_check_toggle($status)) {echo "FEHLER";exit;}
if (!in_array($a, array("b", "t"))) {die("FEHLER");}
if (!cms_valide_gruppe($g)) {echo "FEHLER"; exit;}
$gk = cms_textzudb($g);
if (cms_angemeldet()) {
  $dbs = cms_verbinden('s');
  $aktiv;
  $fehler = false;
  $blogquery    = "IS NULL";
  $terminquery  = "IS NULL";
  if($status == '1') {
    $blogquery = "NULL";
    $terminquery = "NULL";
  }
  $CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
  $gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gid);

  // Artikel prüfen
  if($a == "b") {
    if($status == '1') {
      $blogquery = "?";
    } else {
      $blogquery = "= ?";
    }
    $artikeltyp = "blogeintrag";

    $sql = "SELECT aktiv FROM {$gk}blogeintraegeintern WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $aid);
    $sql->bind_result($aktiv);
    $fehler = !($sql->execute() && $sql->fetch());
    $sql->close();

    $gefunden = $gruppenrecht['sichtbar'] && ($aktiv || $gruppenrecht['blogeintraege']);
  }
  if($a == "t") {
    if($status == '1') {
      $terminquery = "?";
    } else {
      $terminquery = "= ?";
    }
    $artikeltyp = "termin";

    $sql = "SELECT aktiv FROM {$gk}termineintern WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $aid);
    $sql->bind_result($aktiv);
    $fehler = !($sql->execute() && $sql->fetch());
    $sql->close();

    $gefunden = $gruppenrecht['sichtbar'] && ($aktiv || $gruppenrecht['termine']);
  }
  $fehler = $fehler || !$gefunden;
  if($fehler) {
    die("FEHLER");
  }

  if ($status == '1') {
    $sql = "INSERT INTO {$gk}todoartikel (person, blogeintrag, termin) SELECT ?, $blogquery, $terminquery FROM {$gk}todoartikel WHERE NOT EXISTS(SELECT $artikeltyp FROM {$gk}todoartikel WHERE person = ? AND $artikeltyp = ?) LIMIT 1";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $CMS_BENUTZERID, $aid, $CMS_BENUTZERID, $aid);
    $sql->execute();
  } else {
    $sql = "DELETE FROM {$gk}todoartikel WHERE person = ? AND blogeintrag $blogquery AND termin $terminquery";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $CMS_BENUTZERID, $aid);
    $sql->execute();
  }

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
