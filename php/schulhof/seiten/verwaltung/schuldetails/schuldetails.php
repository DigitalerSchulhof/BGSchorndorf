<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schuldetails</h1>

<?php
if (cms_r("schulhof.verwaltung.schule.[|adressen,mail]")) {
	$code = "</div>";

	$adressen = "";
	$mail = "";

	$adressen = "";
	if (cms_r("schulhof.verwaltung.schule.adressen")) {
		$adressen  = "<table class=\"cms_formular\">";
		$adressen .= "<tr><th>Name der Schule:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_schule\" id=\"cms_schulhof_adressen_schule\" value=\"$CMS_SCHULE\"></td></tr>";
		$adressen .= "<tr><th>Genitiv des Namens der Schule:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_schulegenitiv\" id=\"cms_schulhof_adressen_schulegenitiv\" value=\"$CMS_SCHULE_GENITIV\"></td></tr>";
		$adressen .= "<tr><th>Ort der Schule:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_ort\" id=\"cms_schulhof_adressen_ort\" value=\"$CMS_ORT\"></td></tr>";
		$adressen .= "<tr><th>Straße und Hausnummer der Schule:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_strasse\" id=\"cms_schulhof_adressen_strasse\" value=\"$CMS_STRASSE\"></td></tr>";
		$adressen .= "<tr><th>Postleitzahl und Ort der Schule:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_plzort\" id=\"cms_schulhof_adressen_plzort\" value=\"$CMS_PLZORT\"></td></tr>";
		$adressen .= "<tr><th>Mailadresse des Webmasters:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_webmaster\" id=\"cms_schulhof_adressen_webmaster\" value=\"$CMS_WEBMASTER\"></td></tr>";
		$adressen .= "<tr><th>Domain der Schule:</th><td><input type=\"text\" name=\"cms_schulhof_adressen_domain\" id=\"cms_schulhof_adressen_domain\" value=\"$CMS_DOMAIN\"></td></tr>";
		$adressen .= "</table>";
		$adressen .= "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_adressen();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
	}
	if (cms_r("schulhof.verwaltung.schule.mail")) {
		$mail .= "<table class=\"cms_formular\">";
		$mail .= "<tr><th>Adresse des Absenders:</th><td><input type=\"text\" name=\"cms_schulhof_schulmail_absender\" id=\"cms_schulhof_schulmail_absender\" value=\"$CMS_MAILABSENDER\"></td></tr>";
		$mail .= "<tr><th>Host der eMailadresse:</th><td><input type=\"text\" name=\"cms_schulhof_schulmail_host\" id=\"cms_schulhof_schulmail_host\" value=\"$CMS_MAILHOST\"></td></tr>";
		$mail .= "<tr><th>Benutzername der eMailadresse:</th><td><input type=\"text\" name=\"cms_schulhof_schulmail_benutzer\" id=\"cms_schulhof_schulmail_benutzer\" value=\"$CMS_MAILUSERNAME\"></td></tr>";
		$mail .= "<tr><th>Passwort der eMailadresse:</th><td><input type=\"password\" name=\"cms_schulhof_schulmail_passwort\" id=\"cms_schulhof_schulmail_passwort\" value=\"$CMS_MAILPASSWORT\"></td></tr>";
		$mail .= "<tr><th>SMTP-Authentifizierung:</th><td>";
		$vorsilbe = "in";
		$wert = 0;
		if ($CMS_MAILSMTPAUTH) {
			$vorsilbe = "";
			$wert = 1;
		}
		$mail .= "<span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_schulhof_schulmail_smtpauth\" onclick=\"cms_schieber('schulhof_schulmail_smtpauth')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_schulhof_schulmail_smtpauth\" id=\"cms_schulhof_schulmail_smtpauth\" value=\"".$wert."\">";
		$mail .= "</td></tr>";
		$mail .= "</table>";
		$warnung  = '<h4>Folgen falscher Zugangsdaten</h4><p>Wenn hier falsche Zugangsdaten eingegeben werden, sind folgende Funktionen nicht mehr möglich:</p>';
		$warnung .= '<ul><li>Es werden keine Passwörter zugestellt, wenn das Passwort vergessen wurde.</li>';
		$warnung .= '<li>Es werden keine Zugangsdaten an neue Personen verschickt.</li>';
		$warnung .= '<li>Personen erhalten keine Benachrichtigung mehr, wenn ihr Konto gelöscht wurde.</li>';
		$warnung .= '<li>Informationen über neue Nachrichten im Postfach können systemweit nicht verschickt werden.</li>';
		$warnung .= '<li>Informationen über neue Vertretungen können systemweit nicht verschickt werden.</li>';
		$warnung .= '<li>Informationen über Neuigkeiten können systemweit nicht verschickt werden.</li></ul>';
		$warnung .= '<p>Vor der Speicherung der neuen Daten sollten die neuen Zugangsdaten daher getestet werden.</p>';
		$mail .= cms_meldung('warnung', $warnung);
		$mail .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schulhof_verwaltung_testmail();\">Testmail mit neuen Zugangdaten an mich</span></p>";
		$mail .= "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_schulmail();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
	}

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Adressen</h2>";
	if (cms_r("schulhof.verwaltung.schule.adressen")) {$code .= $adressen;}
	else {$code .= cms_meldung('info', '<h4>Keine Berechtigung</h4><p>Keine Berechtigung zur Änderung der Adressen der Schule.</p>');}
	$code .= "</div></div>";
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Mailadresse des Schulhofs</h2>";
	if (cms_r("schulhof.verwaltung.schule.mail")) {$code .= $mail;}
	else {$code .= cms_meldung('info', '<h4>Keine Berechtigung</h4><p>Keine Berechtigung zur Änderung der eMailadresse des Schulhofs.</p>');}
	$code .= "</div></div>";
	$code .= "<div class=\"cms_clear\"></div>";

	echo $code;
}
else {
	cms_meldung_berechtigung();
	echo "</div>";
}
?>
