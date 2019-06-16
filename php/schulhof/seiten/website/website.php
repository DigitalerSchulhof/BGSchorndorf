<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Website</h1>";

$website = "";

if($CMS_RECHTE['Website']['Seiten anlegen'] || $CMS_RECHTE['Organisation']['Website bearbeiten'] || $CMS_RECHTE['Website']['Seiten löschen'] || $CMS_RECHTE['Website']['Startseite festlegen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/seiten.png');\" href=\"Schulhof/Website/Seiten\">Seiten</a> ";
if($CMS_RECHTE['Website']['Hauptnavigationen festlegen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/hauptnavigationen.png');\" href=\"Schulhof/Website/Hauptnavigationen\">Hauptnavigationen</a> ";
if($CMS_RECHTE['Website']['Dateien hochladen'] || $CMS_RECHTE['Website']['Dateien umbenennen'] || $CMS_RECHTE['Website']['Dateien löschen'] ||  $CMS_RECHTE['Website']['Ordner anlegen'] ||  $CMS_RECHTE['Website']['Ordner umbenennen'] ||  $CMS_RECHTE['Website']['Ordner löschen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/webdateien.png');\" href=\"Schulhof/Website/Dateien\">Dateien</a> ";
if($CMS_RECHTE['Website']['Titelbilder hochladen'] || $CMS_RECHTE['Website']['Titelbilder umbenennen'] || $CMS_RECHTE['Website']['Titelbilder löschen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/titelbilder.png');\" href=\"Schulhof/Website/Titelbilder\">Titelbilder</a> ";
if($CMS_RECHTE['Website']['Termine bearbeiten'] || $CMS_RECHTE['Website']['Termine löschen'] || $CMS_RECHTE['Website']['Termine anlegen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/termine.png');\" href=\"Schulhof/Website/Termine\">Termine</a> ";
if($CMS_RECHTE['Website']['Blogeinträge bearbeiten'] || $CMS_RECHTE['Website']['Blogeinträge löschen'] || $CMS_RECHTE['Website']['Blogeinträge anlegen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/blog.png');\" href=\"Schulhof/Website/Blogeinträge\">Blogeinträge</a> ";
if($CMS_RECHTE['Website']['Galerien bearbeiten'] || $CMS_RECHTE['Website']['Galerien löschen'] || $CMS_RECHTE['Website']['Galerien anlegen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/galerien.png');\" href=\"Schulhof/Website/Galerien\">Galerien</a> ";
if($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'] || $CMS_RECHTE['Website']['Besucherstatistiken - Schulhof sehen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken.png');\" href=\"Schulhof/Website/Besucherstatistiken\">Besucherstatistiken</a> ";
if($CMS_RECHTE['Website']['Feedback sehen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/feedback.png');\" href=\"Schulhof/Website/Feedback\">Feedback</a> ";
if($CMS_RECHTE['Website']['Fehlermeldungen sehen'])
  $website .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/fehlermeldungen.png');\" href=\"Schulhof/Website/Fehlermeldungen\">Fehlermeldungen</a> ";

if (strlen($website) > 0)
	$code .= "<p>".$website."</p>";
else
	$code .= cms_meldung_berechtigung();

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
