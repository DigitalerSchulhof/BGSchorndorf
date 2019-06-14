<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Website</h1>";

$code .= "<p>";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/seiten.png');\" href=\"Schulhof/Website/Seiten\">Seiten</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/hauptnavigationen.png');\" href=\"Schulhof/Website/Hauptnavigationen\">Hauptnavigationen</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/webdateien.png');\" href=\"Schulhof/Website/Dateien\">Dateien</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/titelbilder.png');\" href=\"Schulhof/Website/Titelbilder\">Titelbilder</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/termine.png');\" href=\"Schulhof/Website/Termine\">Termine</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/blog.png');\" href=\"Schulhof/Website/Blogeinträge\">Blogeinträge</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/galerien.png');\" href=\"Schulhof/Website/Galerien\">Galerien</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken.png');\" href=\"Schulhof/Website/Besucherstatistiken\">Besucherstatistiken</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/feedback.png');\" href=\"Schulhof/Website/Feedback\">Feedback</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/fehlermeldungen.png');\" href=\"Schulhof/Website/Fehlermeldungen\">Fehlermeldungen</a> ";

$code .= "</p>";

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
