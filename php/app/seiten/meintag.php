<?php
$code = "";

if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternpersoenlich.php');
	$vplan = cms_vertretungsplan_extern_persoenlich();
	$code .= "<h2>Vertretungsplan</h2>";
	if (strlen($vplan) > 0) {
		$code .= $vplan;
	}
	else {$code .= "<p class=\"cms_notiz\">Aktuell keine Vertretungen.</p>";}
}
else {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
	$vplan = cms_vertretungsplan_persoenlich($dbs, true);

	$code .= "<h2>Mein Tag</h2>";
	if ((strlen($vplan) > 0) || (strlen($vplan) > 0)) {
		$code .= $vplan;
	}
	else {$code .= "<p class=\"cms_notiz\">Aktuell keine Vertretungen.</p>";}
}

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
