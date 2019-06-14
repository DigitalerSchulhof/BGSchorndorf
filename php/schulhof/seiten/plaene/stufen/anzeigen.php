<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_STUFEN);
$code .= "</p>";
$code .= "<h1>Stundenplan der Stufe ".(str_replace('_', ' ', $CMS_ZUSATZ[0]))."</h1>";

if (isset($_SESSION["STUFENPLANANZEIGEN"])) {$id = $_SESSION["STUFENPLANANZEIGEN"];} else {$id = '-';}

if ($id != "-") {
	$zugriff = $CMS_RECHTE['verwaltung'] || $CMS_RECHTE['lehrer'];
	$fehler = false;

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();

	if ($angemeldet && $zugriff) {
		$dbs = cms_verbinden('s');
		// Aktuellen Zeitraum laden
		$jetzt = time();
		$zeitraum = "-";
		$sql = "SELECT id FROM zeitraeume WHERE beginn < $jetzt AND ende > $jetzt AND aktiv = 1";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$zeitraum = $daten['id'];
			}
			$anfrage->free();
		}

		// Raumplan
		include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php');
		$code .= "<h3>Regulärer Stundenplan</h3>";
		if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == 'extern') {
			$code .= cms_meldung('info', '<h4>Nicht verfügbar</h4><p>Bei externer Stundenplanverwaltung stehen im Moment noch keine Stundenpläne zur Verfügung.</p>');
		}
		else {
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php');
			if ($zeitraum != '-') {
				$code .= cms_stundenplan_erzeugen($dbs, $zeitraum, 'stufe', $id, false);
			}
			else {
				$code .= cms_meldung('info', '<h4>Aktuell unbekannt</h4><p>Zur Zeit ist kein Stundenplan verfügbar.</p>');
			}
		}
		cms_trennen($dbs);

			$code .= "</div>";


	}
	else {
		$code .= cms_meldung_berechtigung();
		$code .= "</div>";
	}
}
else {
	$code .= cms_meldung_bastler();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
