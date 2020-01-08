<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Schülervertretungsplan</h1>";

if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.vertretungen.schüler"))) {

	if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
		include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
		$code .= cms_vertretungsplan_komplettansicht_aus_datei('s', $CMS_EINSTELLUNGEN['Vertretungsplan Schüler aktuell']);
		$code .= cms_vertretungsplan_komplettansicht_aus_datei('s', $CMS_EINSTELLUNGEN['Vertretungsplan Schüler Folgetag']);
	}
	else {
		include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
		$code .= cms_vertretungsplan_komplettansicht_heute($dbs, 's');
		$code .= cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, 's');
	}

}
else {
	$code .= cms_meldung_berechtigung();
}



$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
