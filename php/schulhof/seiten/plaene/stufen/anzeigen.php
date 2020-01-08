<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$stufenbezeichnung = $CMS_URL[3];
$code .= "<h1>Stundenplan der Stufe ".(str_replace('_', ' ', $stufenbezeichnung))."</h1>";


if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.stufen"))) {
	$dbs = cms_verbinden('s');

	// Stufenplan
	include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
	// Stufe laden
	$sql = "SELECT id FROM stufen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("si", $stufenbezeichnung, $CMS_BENUTZERSCHULJAHR);
	if ($sql->execute()) {
		$sql->bind_result($sid);
		$sql->fetch();
	}
	$sql->close();

	if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '1') {
		$code .= "<h3>Regulärer Stundenplan</h3>";
		$code .= cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Stufe wurde kein Stundenplan hinterlegt.</p>");
	}
	else {
		include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
		if (isset($_SESSION['STUFENSTUNDENPLANZEITRAUM'])) {$zeitraum = $_SESSION['STUFENSTUNDENPLANZEITRAUM'];}
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
				$zeitraumwahl .= cms_togglebutton_generieren ('cms_zeitraumwahl_'.$zid, $zbez, $wert, "cms_stundenplan_vorbereiten('t', '$sid', '$zid')")." ";
			}
		}
		$sql->close();

		if (strlen($zeitraumwahl) == 0) {"<p class=\"cms_notiz\">Keine Zeiträume gefunden</p>";}
			else {$zeitraumwahl = "<p>".$zeitraumwahl."</p>";}
		$code .= $zeitraumwahl;
		$code .= cms_stufenregelplan_aus_db($dbs, $sid, $zeitraum);
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
