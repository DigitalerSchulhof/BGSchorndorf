<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schulnetze verwalten</h1>

<?php
if (r("technik.server.netze")) {
	$warnung = cms_meldung('warnung', '<h4>Hier endet die Spielwiese – Fehler können das System lahmlegen</h4><p>Wenn hier ein Fehler passiert, kann der Zugang zum Schulhof und zur Schulwebsite nachhaltig beschädigt werden, sodass es nur noch durch Änderungen im Programmcode selbst wieder gestartet werden kann. Änderungen sollten daher nicht von Laien durchgeführt werden!</p><p>Es kann sinnvoll sein, vor dieser Aktion ein Backup durchzuführen.</p><p>Hier werden lediglich die Datenbankzugänge geändert. Datenübertragungen und/oder -löschungen müssen manuell durchgeführt werden.</p><p>Wenn Zweifel an der Richtigkeit der Eingaben bestehen, sollte ein Fachmann hinzugezogen werden!</p>');

	$code = $warnung;

	if (!$CMS_IMLN) {
		$code .= cms_meldung_eingeschraenkt();
	}

	$code .= "</div>";

	// ÖFFENTLICHES NETZ
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\"><h2>Schulhof</h2>";
	$code .= "<h4>Datenbank</h4>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Host:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_shost\" name=\"cms_schulhof_verwaltung_schulnetz_shost\" value=\"$CMS_DBS_HOST\"></td></tr>";
	$code .= "<tr><th>Benutzer:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_sbenutzer\" name=\"cms_schulhof_verwaltung_schulnetz_sbenutzer\" value=\"$CMS_DBS_USER\"></td></tr>";
	$code .= "<tr><th>Passwort:</th><td><input type=\"password\" id=\"cms_schulhof_verwaltung_schulnetz_spass\" name=\"cms_schulhof_verwaltung_schulnetz_spass\" value=\"$CMS_DBS_PASS\"></td></tr>";
	$code .= "<tr><th>Datenbank:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_sdb\" name=\"cms_schulhof_verwaltung_schulnetz_sdb\" value=\"$CMS_DBS_DB\"></td></tr>";
	$code .= "</table>";

	$code .= "<h4>Verzeichnisse</h4>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Basisverzeichnis:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_base\" name=\"cms_schulhof_verwaltung_schulnetz_base\" value=\"$CMS_BASE\"></td></tr>";
	$code .= "</table>";


	$code .= "<h2>Lehrerzimmer</h2>";

	$code .= "<h4>Datenbanken</h4>";
	$code .= cms_gesicherteinhalte("cms_schulhof_verwaltung_lehrerdatenbankdaten", "l", "lehrerdatenbankdaten");

	$code .= "<h4>Zugangseinschränkung auf bestimmtes Netz</h4>";
	$code .= "<table class=\"cms_formular\">";
	$vorsilbe = "in";
	if ($CMS_LN_ZB_VPN == 1) {$vorsilbe = "";}
	$code .= "<tr><th>Zugriff per VPN erlauben:</th><td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_schulhof_schulnetz_lnzb_vpn\" onclick=\"cms_schieber('schulhof_schulnetz_lnzb_vpn')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_schulhof_schulnetz_lnzb_vpn\" id=\"cms_schulhof_schulnetz_lnzb_vpn\" value=\"$CMS_LN_ZB_VPN\"></td></tr>";
	$code .= "<tr><th>Absolutpfad zum Lehrerdatenstammverzeichnis:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_ldaten\" name=\"cms_schulhof_verwaltung_schulnetz_ldaten\" value=\"$CMS_LN_DA\"></td></tr>";
	$code .= "</table>";


	$code .= "</div></div>";
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\"><h2>Verwaltung</h2><p class=\"cms_notiz\">In Planung</p>";
	$code .= "<h2>Notenbuch</h2><p class=\"cms_notiz\">In Planung</p></div></div>";
	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	$code .= $warnung;

	if ($CMS_IMLN) {
		$sessionid = $_SESSION['SESSIONID'];
		$iv = substr($sessionid, 0, 16);

		$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schulhof_verwaltung_schulnetze(true);\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p></div>";
	}
	else {
		$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schulhof_verwaltung_schulnetze();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p></div>";
	}

	echo $code;
}
else {
	cms_meldung_berechtigung();
	echo "</div><div class=\"cms_clear\"></div>";
}

?>
