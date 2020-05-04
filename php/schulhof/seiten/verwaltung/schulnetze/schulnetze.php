<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schulnetze verwalten</h1>

<?php
if (cms_r("technik.server.netze")) {
	$warnung = cms_meldung('warnung', '<h4>Hier endet die Spielwiese – Fehler können das System lahmlegen</h4><p>Wenn hier ein Fehler passiert, kann der Zugang zum Schulhof und zur Schulwebsite nachhaltig beschädigt werden, sodass es nur noch durch Änderungen im Programmcode selbst wieder gestartet werden kann. Änderungen sollten daher nicht von Laien durchgeführt werden!</p><p>Es kann sinnvoll sein, vor dieser Aktion ein Backup durchzuführen.</p><p>Hier werden lediglich die Datenbankzugänge geändert. Datenübertragungen und/oder -löschungen müssen manuell durchgeführt werden.</p><p>Wenn Zweifel an der Richtigkeit der Eingaben bestehen, sollte ein Fachmann hinzugezogen werden!</p>');

	$code = $warnung;

	if (!$CMS_IMLN) {
		$code .= cms_meldung_eingeschraenkt();
	}

	$code .= "</div>";

	// ÖFFENTLICHES NETZ
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Schülernetz</h2>";
	$code .= "<h3>Datenbanken</h3>";
	$code .= "<h4>Schulhof</h4>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Host:</th><td>".cms_generiere_input('cms_netze_host_sh', $CMS_DBS_HOST)."</td></tr>";
	$code .= "<tr><th>Benutzer:</th><td>".cms_generiere_input('cms_netze_benutzer_sh', $CMS_DBS_USER)."</td></tr>";
	$code .= "<tr><th>Passwort:</th><td>".cms_generiere_input('cms_netze_passwort_sh', $CMS_DBS_PASS, "password")."</td></tr>";
	$code .= "<tr><th>Datenbank:</th><td>".cms_generiere_input('cms_netze_datenbank_sh', $CMS_DBS_DB)."</td></tr>";
	$code .= "</table>";

	$code .= "<h4>Personen</h4>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Host:</th><td>".cms_generiere_input('cms_netze_host_pers', $CMS_DBP_HOST)."</td></tr>";
	$code .= "<tr><th>Benutzer:</th><td>".cms_generiere_input('cms_netze_benutzer_pers', $CMS_DBP_USER)."</td></tr>";
	$code .= "<tr><th>Passwort:</th><td>".cms_generiere_input('cms_netze_passwort_pers', $CMS_DBP_PASS, "password")."</td></tr>";
	$code .= "<tr><th>Datenbank:</th><td>".cms_generiere_input('cms_netze_datenbank_pers', $CMS_DBP_DB)."</td></tr>";
	$code .= "</table>";

	$code .= "<h3>Sonstiges</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Basisverzeichnis:</th><td>".cms_generiere_input('cms_netze_basisverzeichnis_sh', $CMS_BASE)."</td></tr>";
	$code .= "<tr><th>Lehrerserver:</th><td>".cms_generiere_input('cms_netze_lehrerverzeichnis_sh', $CMS_LN_DA)."</td></tr>";
	$code .= "<tr><th>VPN-Anleitung anzeigen:</th><td>".cms_generiere_schieber('cms_netze_vpn', $CMS_LN_ZB_VPN)."</td></tr>";
	$code .= "<tr><th>Hostingpartner:</th><td>".cms_generiere_input('cms_netze_hostingpartner_sh', $CMS_HOSTINGPARTNEREX)."</td></tr>";
	$code .= "</table>";

	$code .= "<h3>Chat</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Socket-IP:</th><td>".cms_generiere_input('cms_netze_socketip', $CMS_SOCKET_IP)."</td></tr>";
	$code .= "<tr><th>Socket-Port:</th><td>".cms_generiere_input('cms_netze_socketport', $CMS_SOCKET_PORT)."</td></tr>";
	$code .= "</table>";

	$code .= "<h3>Update</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>GitHub-Secret:</th><td>".cms_generiere_input('cms_netze_github', $GITHUB_OAUTH)."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	
	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	$code .= $warnung;

	if ($CMS_IMLN) {
		$sessionid = $_SESSION['SESSIONID'];
		$iv = substr($sessionid, 0, 16);

		$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schulhof_verwaltung_schulnetze();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p></div>";
	}
	else {
		$code .= "<p><span class=\"cms_button cms_button_gesichert\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p></div>";
	}

	echo $code;
}
else {
	cms_meldung_berechtigung();
	echo "</div><div class=\"cms_clear\"></div>";
}
?>
