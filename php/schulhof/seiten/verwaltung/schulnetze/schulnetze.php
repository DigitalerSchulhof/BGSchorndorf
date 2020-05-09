<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schulnetze verwalten</h1>

<?php
if (cms_r("technik.server.netze")) {
	$warnung = cms_meldung('warnung', '<h4>Hier endet die Spielwiese – Fehler können das System lahmlegen</h4><p>Wenn hier ein Fehler passiert, kann der Zugang zum Schulhof und zur Schulwebsite nachhaltig beschädigt werden, sodass es nur noch durch Änderungen im Programmcode selbst möglich ist, diesen wiederzu starten. <b>Änderungen sollten daher nicht von Laien durchgeführt werden!</b></p><p>Es kann sinnvoll sein, vor dieser Aktion ein Backup durchzuführen.</p><p>Hier werden lediglich die Datenbankzugänge geändert. Datenübertragungen und/oder -löschungen müssen manuell durchgeführt werden.</p><p>Wenn Zweifel an der Richtigkeit der Eingaben bestehen, sollte ein Fachmann hinzugezogen werden!</p>');

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
	$code .= "<tr><th>Basisverzeichnis:</th><td>".cms_generiere_input('cms_netze_basisverzeichnis_sh', $CMS_EINSTELLUNGEN['Netze Basisverzeichnis'])."</td></tr>";
	$code .= "<tr><th>Lehrerserver:</th><td>".cms_generiere_input('cms_netze_lehrerverzeichnis_sh', $CMS_EINSTELLUNGEN['Netze Lehrerserver'])."</td></tr>";
	$code .= "<tr><th>VPN-Anleitung anzeigen:</th><td>".cms_generiere_schieber('netze_vpn', $CMS_EINSTELLUNGEN['Netze VPN-Anleitung'])."</td></tr>";
	$code .= "<tr><th>Hostingpartner Schülernetz:</th><td>".cms_generiere_input('cms_netze_hostingpartner_sh', $CMS_EINSTELLUNGEN['Hosting Schülernetz'])."</td></tr>";
	$code .= "<tr><th>Hostingpartner Lehrernetz:</th><td>".cms_generiere_input('cms_netze_hostingpartner_ls', $CMS_EINSTELLUNGEN['Hosting Lehrernetz'])."</td></tr>";
	$code .= "</table>";

	$code .= "<h3>Chat</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Socket-IP:</th><td>".cms_generiere_input('cms_netze_socketip', $CMS_EINSTELLUNGEN['Netze Socket-IP'])."</td></tr>";
	$code .= "<tr><th>Socket-Port:</th><td>".cms_generiere_input('cms_netze_socketport', $CMS_EINSTELLUNGEN['Netze Socket-Port'])."</td></tr>";
	$code .= "</table>";

	$code .= "<h3>Update</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Offizielle Version nutzen:</th><td>".cms_generiere_schieber('netze_offizielle_version', $CMS_EINSTELLUNGEN['Netze Ofizielle Version'], '$(\'.cms_netze_github\').toggle()')."</td></tr>";
	if($CMS_EINSTELLUNGEN['Netze Ofizielle Version'] == 1) {
		$github_style = "display: none;";
	} else {
		$github_style = "";
	}
	$code .= "<tr class=\"cms_netze_github\" style=\"$github_style\"><td colspan=\"2\"><h4>Eigenen Fork nutzen (GitHub)</h4></td></tr>";
	$code .= "<tr class=\"cms_netze_github\" style=\"$github_style\"><th>Benutzer:</th><td>".cms_generiere_input('cms_netze_github_benutzer', $CMS_EINSTELLUNGEN['Netze GitHub Benutzer'])."</td></tr>";
	$code .= "<tr class=\"cms_netze_github\" style=\"$github_style\"><th>Repository:</th><td>".cms_generiere_input('cms_netze_github_repo', $CMS_EINSTELLUNGEN['Netze GitHub Repository'])."</td></tr>";
	$code .= "<tr class=\"cms_netze_github\" style=\"$github_style\"><th>OAuth:</th><td>".cms_generiere_input('cms_netze_github_oauth', $CMS_EINSTELLUNGEN['Netze GitHub OAuth'])."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Lehrernetz</h2>";
	if (!$CMS_IMLN) {
		$code .= cms_meldung_firewall();
	}
	else {
		$code .= cms_generiere_nachladen('cms_netze_lehrernetz', 'cms_schulnetze_lehrernetz_laden()');
	}
	$code .= "</div></div>";
	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	$code .= $warnung;

	if ($CMS_IMLN) {
		$sessionid = $_SESSION['SESSIONID'];
		$iv = substr($sessionid, 0, 16);

		$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schulnetze_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p></div>";
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
