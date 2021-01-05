<?php
$code = "";

if (strpos($_SERVER['HTTP_USER_AGENT'], "dshApp") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
	$code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden. Neuerungen umfassen vereinfachte Navigation, Darkmode, direktes Öffnen von Seiten, erhöhte Stabilität und mehr!<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternpersoenlich.php');
	$vplan = cms_vertretungsplan_extern_persoenlich();
	$code .= "<h2>Vertretungsplan</h2>";
	if (strlen($vplan) > 0) {
		$code .= $vplan;
	}
	else {$code .= "<p class=\"cms_notiz\">Aktuell Keine Vertretungen</p>";}
}
else {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
	$vplan = cms_vertretungsplan_persoenlich($dbs, true);

	$code .= "<h2>Mein Tag</h2>";
	if ((strlen($vplan) > 0) || (strlen($vplan) > 0)) {
		$code .= $vplan;
	}
	else {$code .= "<p class=\"cms_notiz\">Aktuell Keine Vertretungen</p>";}
}

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
