<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Stundenplan von Personen</h1>

<?php
// PROFILDATEN LADEN
if (!isset($_SESSION['PERSONENDETAILS'])) {
	echo cms_meldung_bastler();
}
else {
	$personid = $_SESSION['PERSONENDETAILS'];
	if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 's')) {
		$dbs = cms_verbinden();

		// PERSON LADEN
		$personart = "-";
		$sql = "SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $personid);
		if ($sql->execute()) {
			$sql->bind_result($personart);
			$sql->fetch();
			$sql->close();
		}

		if ($personart != '-') {
			// Aktuellen Zeitraum laden
			$jetzt = time();
			$zeitraum = "-";
			$sql = "SELECT id FROM zeitraeume WHERE beginn < $jetzt AND ende > $jetzt AND aktiv = 1";
			$sql->prepare($sql);
			$sql->bind_param("ii", $jetzt, $jetzt);
			if ($sql->execute()) {
				$sql->bind_result($zeitraum);
				$sql->fetch();
				$sql->close();
			}

			include_once("php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php");
			if ($zeitraum != '-') {
				echo cms_stundenplan_erzeugen($dbs, $zeitraum, $personart, $personid, false);
			}
			else {
				echo cms_meldung('info', '<h4>Aktuell unbekannt</h4><p>Zur Zeit ist kein Stundenplan verfügbar.</p>');
			}
		}
		else {
			echo cms_meldung_fehler();
		}
		cms_trennen($dbs);
	}
	else {
		echo cms_meldung_bastler();
	}
}

?>

</div>

<div class="cms_clear"></div>
