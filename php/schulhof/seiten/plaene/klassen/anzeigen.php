<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$klassenbezeichnung = $CMS_URL[3];
$code .= "<h1>Stundenplan der Klasse ".(str_replace('_', ' ', $klassenbezeichnung))."</h1>";

$zugriff = $CMS_RECHTE['Planung']['Klassenstundenpläne sehen'];
$fehler = false;

if ($fehler) {$zugriff = false;}
$angemeldet = cms_angemeldet();

if ($angemeldet && $zugriff) {
	$dbs = cms_verbinden('s');

	// Klassenplan
	include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
	$code .= "<h3>Regulärer Stundenplan</h3>";
	if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '1') {
		// Raum laden
		$stundenplan = "";
		$sql = "SELECT AES_DECRYPT(stundenplanextern, '$CMS_SCHLUESSEL') AS stundenplan FROM klassen WHERE bezeichnung = AES_ENCRYPT('$klassenbezeichnung', '$CMS_SCHLUESSEL') AND schuljahr = $CMS_BENUTZERSCHULJAHR";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {$stundenplan = $daten['stundenplan'];}
			$anfrage->free();
		}

		if (strlen($stundenplan) == 0) {$code .= cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Klasse wurde kein Stundenplan hinterlegt.</p>");}
		else {
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdatei.php');
			$code .= cms_klassenplan_aus_datei($stundenplan);
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
?>
