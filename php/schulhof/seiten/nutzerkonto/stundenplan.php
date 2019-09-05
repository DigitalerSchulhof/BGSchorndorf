<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Mein Stundenplan</h1>

<?php
// PROFILDATEN LADEN
if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 's')) {
	$dbs = cms_verbinden();
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
	$schuljahr = "-";
	$sql = "SELECT id FROM schuljahre WHERE beginn < $jetzt AND ende > $jetzt";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$schuljahr = $daten['id'];
		}
		$anfrage->free();
	}

	if ((($CMS_EINSTELLUNGEN['Stundenplan Lehrer extern'] == '1') && ($CMS_BENUTZERART == 'l')) ||
	 		(($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '1') && ($CMS_BENUTZERART == 's'))) {
		$stundenplan = "";
		if ($CMS_BENUTZERART == 'l') {
			$sql = "SELECT AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') AS stundenplan FROM lehrer WHERE id = $CMS_BENUTZERID";
			if ($anfrage = $dbs->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()) {
					$stundenplan = $daten['stundenplan'];
				}
				$anfrage->free();
			}
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generierenausdatei.php');
			$code .= cms_lehrerplan_aus_datei($stundenplan);
		}
		else if ($CMS_BENUTZERART == 's') {
			$sql = "SELECT AES_DECRYPT(stundenplanextern, '$CMS_SCHLUESSEL') AS stundenplan FROM klassen JOIN klassenmitglieder ON klassen.id = klassenmitglieder.gruppe WHERE person = $CMS_BENUTZERID AND schuljahr = $schuljahr";
			if ($anfrage = $dbs->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()) {
					$stundenplan = $daten['stundenplan'];
				}
				$anfrage->free();
			}
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generierenausdatei.php');
			$code .= cms_klassenplan_aus_datei($stundenplan);
		}

	}
	else if ((($CMS_EINSTELLUNGEN['Stundenplan Lehrer extern'] != '1') && ($CMS_BENUTZERART == 'l')) ||
	 				 (($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] != '1') && ($CMS_BENUTZERART == 's'))) {
		include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php');
		if ($zeitraum != '-') {
			$code .= cms_stundenplan_erzeugen($dbs, $zeitraum, $CMS_BENUTZERART, $CMS_BENUTZERID, false);
		}
		else {
			$code .= cms_meldung('info', '<h4>Aktuell unbekannt</h4><p>Zur Zeit ist kein Stundenplan verfügbar.</p>');
		}
	}
	cms_trennen($dbs);
	echo $code;
}
else {
	echo cms_meldung_bastler();
}
?>

</div>


<div class="cms_clear"></div>
