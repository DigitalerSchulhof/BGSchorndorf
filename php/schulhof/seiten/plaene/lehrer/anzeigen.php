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
	include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
	// Leher laden
	$stundenplan = "";
	$kuerzel = explode('(', $lehrerbezeichnung);
	$kuerzel = $kuerzel[count($kuerzel)-1];
	$kuerzel = substr($kuerzel, 0, -1);
	$sql = "SELECT id, AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') AS stundenplan FROM lehrer WHERE kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("s", $kuerzel);
	if ($sql->execute()) {
		$sql->bind_result($lid, $stundenplan);
		$sql->fetch();
	} else {$fehler = true;}
	$sql->close();

	if ($CMS_EINSTELLUNGEN['Stundenplan Lehrer extern'] == '1') {
		$code .= "<h3>Regulärer Stundenplan</h3>";

		if (strlen($stundenplan) == 0) {$code .= cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Lehrkraft wurde kein Stundenplan hinterlegt.</p>");}
		else {
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdatei.php');
			$code .= cms_lehrerplan_aus_datei($stundenplan);
		}
	}
	else {
		if (isset($_SESSION['LEHRERSTUNDENPLANZEITRAUM'])) {$zeitraum = $_SESSION['LEHRERSTUNDENPLANZEITRAUM'];}
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
				$zeitraumwahl .= cms_togglebutton_generieren ('cms_zeitraumwahl_'.$zid, $zbez, $wert, "cms_stundenplan_vorbereiten('l', '$lid', '$zid')")." ";
			}
		}
		$sql->close();
		if (strlen($zeitraumwahl) == 0) {"<p class=\"cms_notiz\">Keine Zeiträume gefunden</p>";}
			else {$zeitraumwahl = "<p>".$zeitraumwahl."</p>";}
		$code .= $zeitraumwahl;
		$code .= cms_lehrerregelplan_aus_db($dbs, $lid, $zeitraum);
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
