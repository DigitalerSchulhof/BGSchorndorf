<?php
$v = "<span><a href=\"https://github.com/DigitalerSchulhof\" style=\"color: inherit;\" target=\"_blank\">Digitaler Schulhof</a> – Version $CMS_VERSION –</span>";


if ($CMS_ANGEMELDET) {
	$lehrer = "";
	$class = "";
	if ($CMS_IMLN) {
		$lehrer = ", Lehrerzimmer";
		$class = "cms_netzcheckstatus_lehrer";
	}
	echo "<div id=\"cms_netzcheckstatus\" class=\"$class\">".$v." <span id=\"cms_netzcheckstatus_sh\">Schulhof</span><span id=\"cms_netzcheckstatus_lz\">$lehrer</span></div>";
}

if ($CMS_URL[0] != 'App') {
	echo "<div id=\"cms_fusszeile_o\">";
	echo "<div id=\"cms_fusszeile_m\">";
		echo "<div id=\"cms_fusszeile_i\">";
		echo cms_navigation_ausgeben('f');

		$auszeichnungen = "";
		$sql = $dbs->prepare("SELECT bild, bezeichnung, link, ziel FROM auszeichnungen WHERE aktiv = 1 ORDER BY reihenfolge");
		if ($sql->execute()) {
			$sql->bind_result($abild, $abez, $alink, $aziel);
			while ($sql->fetch()) {
				$auszeichnungen .= "<li><a href=\"$alink\" target=\"$aziel\"><p><img src=\"$abild\"/></p><p>".cms_textaustextfeld_anzeigen($abez)."</p></a></li>";
			}
		}
		$sql->close();

		if (strlen($auszeichnungen) > 0) {
			echo "<ul class=\"cms_auszeichnung\">".$auszeichnungen."</ul>";
		}

		echo "<p id=\"cms_geraetewahl\">";
		if (isset($_SESSION['DSGVO_EINWILLIGUNG_A'])) {
			if ($_SESSION['DSGVO_EINWILLIGUNG_A']) {
				echo "Anzeige optimieren für: <a href=\"javascript:cms_geraet_aendern('P');\">Computer</a>, <a href=\"javascript:cms_geraet_aendern('T');\">Tablets</a> oder <a href=\"javascript:cms_geraet_aendern('H');\">Smartphones</a>.";
			}
		}
		echo "</p>";

		$fusszeile = "";
		$sql = $dbs->prepare("SELECT wert FROM master WHERE inhalt = 'Fußzeile'");
		if ($sql->execute()) {
			$sql->bind_result($fusszeile);
			$sql->fetch();
		}
		$sql->close();
		if (strlen($fusszeile) > 0) {
			echo $fusszeile;
		}
		echo "</div>";
	echo "</div>";
	echo "</div>";

	// DSGVO-Cookies
	$CMS_DSGVO_COOKIESAKZEPTIERT = false;
	if (isset($_SESSION['DSGVO_FENSTERWEG'])) {$CMS_DSGVO_COOKIESAKZEPTIERT = $_SESSION['DSGVO_FENSTERWEG'];}

	if (!$CMS_DSGVO_COOKIESAKZEPTIERT) {

		$code = "";
		$code .= "<div id=\"cms_dsgvo_datenschutz\">";
	  $meldung = "";
	  $meldung .= "<h4>Datenschutzeinstellungen</h4>";
		$meldung .= "<p>Bitte wählen, welche Daten diese Seite verarbeiten darf:</p>";
		$meldung .= "<ul>";
		$meldung .= "<li><b>Einwilligung A:</b> Ich gestatte dieser Website meine personenbezogenen Daten durch die Nutzung von Kontaktformularen an den gewählten Empfänger zu übermitteln. Ferner gestatte ich der Website die Art meines Gerätes in einem Cookie zu speichern, um die Ladezeit zu verbessern. Bei Smartphones wird darüberhinaus auch die geladene Navigation gespeichert.</li>";
		$meldung .= "<li><b>Einwilligung B:</b> Ich gestatte dieser Website Inhalte Dritter anzuzeigen und erkläre mich mit den Datenschutzvereinbarungen dieser dritten Seiten einverstanden.</li>";
		$meldung .= "</ul>";
		$meldung .= "<p>Unter <a href=\"Website/Datenschutz\">Datenschutz</a> können diese Einstellungen jederzeit geändert werden und weitere Datenschutzinformationen ingesehen werden.</p>";
		$meldung .= "<p><span class=\"cms_button_ja\" onclick=\"cms_dsgvo_datenschutz('j', 'j', 'n');\">Einwilligung A erteilen</span> <span class=\"cms_button_ja\" onclick=\"cms_dsgvo_datenschutz('j', 'n', 'j');\">Einwilligung B erteilen</span> <span class=\"cms_button_ja\" onclick=\"cms_dsgvo_datenschutz('j', 'j', 'j');\">Einwilligungen A und B erteilen</span> <span class=\"cms_button_nein\" onclick=\"cms_dsgvo_datenschutz('j', 'n', 'n');\">Keine Einwilligung erteilen</span> <a class=\"cms_button\" href=\"Website/Datenschutz\">Alle Datenschutzhinweise</a></p>";
	  $code .= cms_meldung('warnung', $meldung);
		$code .= "</div>";
		echo $code;
	}
}
?>
