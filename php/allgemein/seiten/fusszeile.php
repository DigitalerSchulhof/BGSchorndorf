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

		echo "<div class=\"cms_auszeichnung\">";
		echo "<a href=\"https://www.unesco.de/bildung/unesco-projektschulen\" target=\"_blank\" class=\"cms_unescotempel\"><p><img src=\"dateien/website/Faecher/UNESCO/Logos/unescotempel.png\"/></p><p>Organisation<br>der Vereinten Nationen<br>für Bildung, Wirtschaft<br>und Kultur</p></a>";
		echo "<a href=\"https://bg.schorndorf.de/Website/Seiten/Aktuell/Startseite/Schulprofil/UNESCO-Projektschule\" class=\"cms_unescoprojektschule\"><p><img src=\"dateien/website/Faecher/UNESCO/Logos/unescoprojektschule.png\"/></p><p>Burg-Gymnasium<br>Schornorf<br>Mitglied des Netzwerks der<br>UNESCO-Projektschulen</p></a>";
		echo "</div>";

		echo "<p class=\"cms_notiz\" id=\"cms_geraetewahl\">";
		if (isset($_SESSION['DSGVO_COOKIESAKZEPTIERT'])) {
			if ($_SESSION['DSGVO_COOKIESAKZEPTIERT']) {
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
if (isset($_SESSION['DSGVO_COOKIESAKZEPTIERT'])) {$CMS_DSGVO_COOKIESAKZEPTIERT = $_SESSION['DSGVO_COOKIESAKZEPTIERT'];}

if (!$CMS_DSGVO_COOKIESAKZEPTIERT) {
	$code = "";

	$code .= "<div id=\"cms_dsgvo_datenschutz\">";
  $meldung = "";
  $meldung .= "<h4>Datenschutzhinweis</h4>";
	$meldung .= "<p>Diese Seite speichert Cookies zu statistischen Zwecken und zur Verkürzung von Ladezeiten. Durch die Nutzung dieser Seite erklären Sie sich damit einverstanden.</p><p><a href=\"javascript:cms_einblenden('cms_dsgvo_datenschutz_ausfuehrlich')\">Mehr Informationen ...</a></p>";
	$meldung .= "<div id=\"cms_dsgvo_datenschutz_ausfuehrlich\" style=\"display: none;\">";
	$meldung .= "<p>Gespeichert werden folgende Informationen:</p><ul>";
	$meldung .= "<li>Es werden die Zugriffe auf das Websiteangebot gezählt. Gespeichert wird die Anzahl der Zugriffe auf einzelne Seiten und Downloads pro Monat. Da der Zeitraum vergleichsweise groß ist und keine IP-Adressen gespeichert werden, sind Rückschlüsse auf Personen nicht möglich.</li>";
	$meldung .= "<li>Es werden Cookies verwendet, um zu prüfen, ob diese Meldung bereits gelesen wurde und um Downloads sicherer zu gestalten. Bei der Verwendung des Schulhofes werden weitere Daten gespeichert, die zur Zugriffskontrolle und damit zur Datensicherheit beitragen. Mehr Informationen unter <a href=\"Website/Datenschutz\">Datenschutz</a>.</li>";
	$meldung .= "</ul>";
	$meldung .= "</div>";
	$meldung .= "<p><span class=\"cms_button_ja\" onclick=\"cms_dsgvo_datenschutz();\">Einverstanden</span> <a class=\"cms_button\" href=\"Website/Datenschutz\">Alle Datenschutzhinweise</a></p>";
  $code .= cms_meldung('warnung', $meldung);
	$code .= "</div>";
	echo $code;
}
?>
