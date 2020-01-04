<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Lehrervertretungsplan</h1>";

if (cms_angemeldet() && r("schulhof.information.pläne.stundenpläne.vertretungen.lehrer")) {

	if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
		include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
		$code .= cms_vertretungsplan_komplettansicht_aus_datei('l', $CMS_EINSTELLUNGEN['Vertretungsplan Lehrer aktuell']);
		$code .= cms_vertretungsplan_komplettansicht_aus_datei('l', $CMS_EINSTELLUNGEN['Vertretungsplan Lehrer Folgetag']);
	}
	else {
		include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
		$code .= cms_vertretungsplan_komplettansicht_heute($dbs, 'l', 'a');
		$code .= cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, 'l', 'a');
	}
}
else {
	$code .= cms_meldung_berechtigung();
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
