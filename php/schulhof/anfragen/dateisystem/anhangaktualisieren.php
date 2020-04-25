<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
  $dateien = "";
  $anhangstyle = "display: table-row;";
  // Bereits hochgeladene Anhänge verwenden
  $pfad = "../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp";
  $vinhalt = scandir($pfad, 0);
  if (file_exists($pfad)) {
    $vinhalt = scandir($pfad, 0);
    for ($i=2; $i<count($vinhalt); $i++) {
      // Falls Datei
      if (is_file($pfad.'/'.$vinhalt[$i])) {
        $dateien .= "<span class=\"cms_postfach_anhang\">";
        $dateien .= "<span class=\"cms_button_nein\" onclick=\"cms_dateisystem_anhang_loeschen('".$vinhalt[$i]."')\"><span class=\"cms_hinweis\">Datei entfernen</span>×</span>";
        $dateiname = explode(".", $vinhalt[$i]);
        $icon = cms_dateisystem_icon($dateiname[count($dateiname)-1]);
        $dateien .= "<img src=\"res/dateiicons/klein/$icon\"> ".$vinhalt[$i];
        $groesse = filesize($pfad.'/'.$vinhalt[$i]);
        $dateien .= " (".cms_groesse_umrechnen($groesse).")";
        $dateien .= "</span>";
      }
    }
  }
  echo $dateien;
}
else {
	echo "BERECHTIGUNG";
}
?>
