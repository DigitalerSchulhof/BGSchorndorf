<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/seiten/verwaltung/gruppen/zuordnungen.php");
session_start();

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
cms_rechte_laden();
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
$CMS_BENUTZERID = $_SESSION['BENUTZERID'];

if (isset($_POST['zugeordnet'])) {$zugeordnet = $_POST['zugeordnet'];} else {echo "FEHLER";exit;}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER";exit;}

// Zugriffssteuerung je nach Gruppe
$zugriff = false;

if ($CMS_RECHTE['Website']['Termine anlegen'] || $CMS_RECHTE['Website']['Blogeinträge anlegen'] || $CMS_RECHTE['Website']['Galerien anlegen']) {$zugriff = true;}
if (!$zugriff) {
  if (($CMS_BENUTZERART == 'l') && ($CMS_EINSTELLUNGEN['Lehrer dürfen Termine vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Lehrer dürfen Blogeinträge vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Lehrer dürfen Galerien vorschlagen'])) {$zugriff = true;}
  if (($CMS_BENUTZERART == 'e') && ($CMS_EINSTELLUNGEN['Eltern dürfen Termine vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Eltern dürfen Blogeinträge vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Eltern dürfen Galerien vorschlagen'])) {$zugriff = true;}
  if (($CMS_BENUTZERART == 'v') && ($CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen Termine vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen Blogeinträge vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen Galerien vorschlagen'])) {$zugriff = true;}
  if (($CMS_BENUTZERART == 's') && ($CMS_EINSTELLUNGEN['Schüler dürfen Termine vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Schüler dürfen Blogeinträge vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Schüler dürfen Galerien vorschlagen'])) {$zugriff = true;}
  if (($CMS_BENUTZERART == 'x') && ($CMS_EINSTELLUNGEN['Externe dürfen Termine vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Externe dürfen Blogeinträge vorschlagen'] ||
                                    $CMS_EINSTELLUNGEN['Externe dürfen Galerien vorschlagen'])) {$zugriff = true;}
}

if (cms_angemeldet() && $zugriff) {

  echo cms_zuordnungselemente($zugeordnet, $gruppe, $schuljahr);
}
else {
	echo "BERECHTIGUNG";
}
?>
