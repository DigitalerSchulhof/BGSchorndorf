<?php
$beta = "&beta;-Testphase Version $CMS_VERSION – ";


if ($CMS_ANGEMELDET) {
	$lehrer = "";
	$style = "";
	if ($CMS_IMLN) {
		$lehrer = ", Lehrerzimmer";
		$style = "background-color: #ffd95a";
	}
	echo "<div id=\"cms_netzcheckstatus\" style=\"$style\">".$beta." <span id=\"cms_netzcheckstatus_sh\">Schulhof</span><span id=\"cms_netzcheckstatus_lz\">$lehrer</span></div>";
}
?>

<div id="cms_fusszeile_o">
<div id="cms_fusszeile_m">
	<div id="cms_fusszeile_i">
		<?php
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

		echo "<p class=\"cms_notiz\" id=\"cms_geraetewahl\">";
		if (isset($_SESSION['DSGVO_EINWILLIGUNG_A'])) {
			if ($_SESSION['DSGVO_EINWILLIGUNG_A']) {
				echo "Anzeige optimieren für: <a href=\"javascript:cms_geraet_aendern('P');\">Computer</a>, <a href=\"javascript:cms_geraet_aendern('T');\">Tablets</a> oder <a href=\"javascript:cms_geraet_aendern('H');\">Smartphones</a>.";
			}
		}
		echo "</p>";

		echo "<p class=\"cms_notiz\">Die verwendeten Icons stammen von <a href=\"http://www.fatcow.com/free-icons\" target=\"_blank\">Fatcow</a> und wurden unter der Lizenz <a href=\"http://creativecommons.org/licenses/by/3.0/us/\">Creative Commons Attribution 3.0</a> veröffentlicht. Die verwendete Schriftart Roboto stammt von <a href=\"https://github.com/google/roboto\" target=\"_blank\">Google</a> und wurden unter der Lizenz <a href=\"https://github.com/google/roboto/blob/master/LICENSE\">Apache License 2.0</a> veröffentlicht.</p>";
		?>
	</div>
</div>
</div>


<?php
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
?>
