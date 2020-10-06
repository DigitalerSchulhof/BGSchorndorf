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
  $blogquerycheck    = "IS NULL";
  $terminquerycheck  = "IS NULL";
  $blogquery = "NULL";
  $terminquery = "NULL";
  $CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
  $gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gid);

  // Artikel prüfen
  if($a == "b") {
    $blogquery = "?";
    $blogquerycheck = "= ?";
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
    $terminquery = "?";
    $terminquerycheck = "= ?";
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
    // Existiert schon?
    $sql = $dbs->prepare("SELECT COUNT(*) FROM {$gk}todoartikel WHERE person = ? AND blogeintrag $blogquerycheck AND termin $terminquerycheck");
    $sql->bind_result($anz);
    $sql->execute();
    if(!$sql->fetch()) {
      // Keine Todos
      $sql = $dbs->prepare("INSERT INTO {$gk}todoartikel (person, blogeintrag, termin) VALUES (?, $blogquery, $terminquery)");
      $sql->bind_param("ii", $CMS_BENUTZERID, $aid);
      $sql->execute();
    }
  } else {
    $sql = "DELETE FROM {$gk}todoartikel WHERE person = ? AND blogeintrag $blogquerycheck AND termin $terminquerycheck";
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
