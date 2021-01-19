<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Wünsche für den Vertretungsplan äußern</h1>";

if (cms_angemeldet() && cms_r("lehrerzimmer.vertretungsplan.wünsche")) {
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Betroffenes Datum:</th><td>".cms_datum_eingabe('cms_vplanwunsch_datum')."</td></tr>";
		$code .= "<tr><th>Anliegen:</th><td><textarea class=\"cms_textarea\" id=\"cms_vplanwunsch_anliegen\" name=\"cms_vplanwunsch_anliegen\"></textarea></td></tr>";
		$code .= "<tr><th></th><td><span class=\"cms_button\" onclick=\"cms_vplanwunsch_einreichen()\">Anliegen einreichen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></td></tr>";
	$code .= "</table>";
	$code .= "</div>";
}
else {
	$code .= cms_meldung_berechtigung();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
