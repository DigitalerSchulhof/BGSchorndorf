<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$lehrerbezeichnung = $CMS_URL[3];
$code .= "<h1>Stundenplan von ".(str_replace('_', ' ', $lehrerbezeichnung))."</h1>";

$zugriff = $CMS_RECHTE['Planung']['Lehrerstundenpläne sehen'];
$fehler = false;

if ($fehler) {$zugriff = false;}
$angemeldet = cms_angemeldet();

if ($angemeldet && $zugriff) {
	$dbs = cms_verbinden('s');

	// Klassenplan
	include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php');
	$code .= "<h3>Regulärer Stundenplan</h3>";
	if ($CMS_EINSTELLUNGEN['Stundenplan Lehrer extern'] == '1') {
		// Raum laden
		$stundenplan = "";
		$kuerzel = explode('(', $lehrerbezeichnung);
		$kuerzel = $kuerzel[count($kuerzel)-1];
		$kuerzel = substr($kuerzel, 0, -1);
		$sql = "SELECT AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') AS stundenplan FROM lehrer WHERE kuerzel = AES_ENCRYPT('$kuerzel', '$CMS_SCHLUESSEL')";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {$stundenplan = $daten['stundenplan'];}
			$anfrage->free();
		}

		if (strlen($stundenplan) == 0) {$code .= cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Lehrkraft wurde kein Stundenplan hinterlegt.</p>");}
		else {
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generierenausdatei.php');
			$code .= cms_lehrerplan_aus_datei($stundenplan);
		}
	}
	cms_trennen($dbs);

	$code .= "</div>";
}
else {
	$code .= cms_meldung_berechtigung();
	$code .= "</div>";
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
