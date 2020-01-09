<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$klassenbezeichnung = $CMS_URL[3];
$code .= "<h1>Stundenplan der Klasse ".(str_replace('_', ' ', $klassenbezeichnung))."</h1>";

if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.klassen")) {
	$dbs = cms_verbinden('s');

	// Klassenplan
	include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
	// Klasse laden
	$stundenplan = "";
	$sql = "SELECT id, AES_DECRYPT(stundenplanextern, '$CMS_SCHLUESSEL') AS stundenplan FROM klassen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("si", $klassenbezeichnung, $CMS_BENUTZERSCHULJAHR);
	if ($sql->execute()) {
		$sql->bind_result($kid, $stundenplan);
		$sql->fetch();
	}
	$sql->close();

	if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '1') {
		$code .= "<h3>Regulärer Stundenplan</h3>";
		if (strlen($stundenplan) == 0) {$code .= cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Klasse wurde kein Stundenplan hinterlegt.</p>");}
		else {
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdatei.php');
			$code .= cms_klassenplan_aus_datei($stundenplan);
		}
	}
	else {
		include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
		if (isset($_SESSION['KLASSENSTUNDENPLANZEITRAUM'])) {$zeitraum = $_SESSION['KLASSENSTUNDENPLANZEITRAUM'];}
		else {$zeitraum = '-';}

		$zeitraumwahl = "";
		// Alle aktiven Zeiträume dieses Schuljahres laden
		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM zeitraeume WHERE schuljahr = ? AND aktiv = 1 ORDER BY beginn DESC";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $CMS_BENUTZERSCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($zid, $zbez);
			while ($sql->fetch()) {
				if ($zeitraum == '-') {$zeitraum = $zid;}
				if ($zeitraum == $zid) {$wert = 1;} else {$wert = 0;}
				$zeitraumwahl .= cms_togglebutton_generieren ('cms_zeitraumwahl_'.$zid, $zbez, $wert, "cms_stundenplan_vorbereiten('k', '$kid', '$zid')")." ";
			}
		}
		$sql->close();

		if (strlen($zeitraumwahl) == 0) {"<p class=\"cms_notiz\">Keine Zeiträume gefunden</p>";}
			else {$zeitraumwahl = "<p>".$zeitraumwahl."</p>";}
		$code .= $zeitraumwahl;
		$code .= cms_klassenregelplan_aus_db($dbs, $kid, $zeitraum);
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
