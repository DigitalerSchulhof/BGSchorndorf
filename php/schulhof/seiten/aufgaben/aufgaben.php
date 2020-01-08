<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Aufgaben</h1>";

if (cms_angemeldet() && cms_r("schulhof.verwaltung.nutzerkonten.verstöße.identitätsdiebstahl"))) {
	$sonderrollen = cms_sonderrollen_generieren();
	if (strlen($sonderrollen) != 0) {
		$code .= "<ul>".$sonderrollen."</ul>";
	}
	else {$code .= "<p class=\"cms_notiz\">Keine Aufgaben verfügbar!</p>";}
	$code .= "</div>";
}
else {
	$code .= cms_meldung_berechtigung();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
