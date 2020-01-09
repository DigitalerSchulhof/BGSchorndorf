<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Website</h1>";

$website = "";

if(cms_r("website.seiten.*"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_seiten\" href=\"Schulhof/Website/Seiten\">Seiten</a> ";
if(cms_r("website.navigation"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_hauptnavigationen\" href=\"Schulhof/Website/Hauptnavigationen\">Hauptnavigationen</a> ";
if(cms_r("website.dateien.*"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_dateien\" href=\"Schulhof/Website/Dateien\">Dateien</a> ";
if(cms_r("website.titelbilder.*"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_titelbilder\" href=\"Schulhof/Website/Titelbilder\">Titelbilder</a> ";
if($CMS_RECHTE['Website']['Termine bearbeiten'] || $CMS_RECHTE['Website']['Termine löschen'] || $CMS_RECHTE['Website']['Termine anlegen'])
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_termine\" href=\"Schulhof/Website/Termine\">Termine</a> ";
if($CMS_RECHTE['Website']['Blogeinträge bearbeiten'] || $CMS_RECHTE['Website']['Blogeinträge löschen'] || $CMS_RECHTE['Website']['Blogeinträge anlegen'])
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_blog\" href=\"Schulhof/Website/Blogeinträge\">Blogeinträge</a> ";
if($CMS_RECHTE['Website']['Galerien bearbeiten'] || $CMS_RECHTE['Website']['Galerien löschen'] || $CMS_RECHTE['Website']['Galerien anlegen'])
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_galerien\" href=\"Schulhof/Website/Galerien\">Galerien</a> ";
if(cms_r("statistik.besucher.*"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_besucherstatistik\" href=\"Schulhof/Website/Besucherstatistiken\">Besucherstatistiken</a> ";
if(cms_r("technik.feedback"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_feedback\" href=\"Schulhof/Website/Feedback\">Feedback</a> ";
if(cms_r("technik.fehlermeldungen"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_fehlermeldungen\" href=\"Schulhof/Website/Fehlermeldungen\">Fehlermeldungen</a> ";
if($CMS_RECHTE['Website']['Emoticons verwalten'])
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_emoticons\" href=\"Schulhof/Website/Emoticons\">Emoticons</a> ";
if(cms_r("schulhof.information.newsletter.*"))
  $website .= "<a class=\"cms_iconbutton cms_uebersicht_verwaltung_website_newsletter\" href=\"Schulhof/Website/Newsletter\">Newsletter</a> ";

if (strlen($website) > 0)
	$code .= "<p>".$website."</p>";
else
	$code .= cms_meldung_berechtigung();

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
