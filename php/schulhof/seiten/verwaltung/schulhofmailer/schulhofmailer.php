<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schulhofmailer verwalten</h1>

<?php
if (cms_r("schulhof.verwaltung.schule.mail")) {
	include_once('php/schulhof/seiten/website/editor/editor.php');

	$CMS_MAIL = cms_einstellungen_laden('maileinstellungen');
	$code  = "<h2>Zugangsdaten</h2>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Adresse des Absenders:</th><td>".cms_generiere_mailinput('cms_mailer_absender', $CMS_MAIL['Absender'])."</td></tr>";
	$code .= "<tr><th>Host der eMailadresse (SMTP-Server):</th><td colspan=\"2\">".cms_generiere_input('cms_mailer_smtphost', $CMS_MAIL['SMTP-Host'])."</td></tr>";
	$code .= "<tr><th>Benutzername der eMailadresse:</th><td colspan=\"2\">".cms_generiere_input('cms_mailer_benutzer', $CMS_MAIL['Benutzername'])."</td></tr>";
	$code .= "<tr><th>Passwort der eMailadresse:</th><td colspan=\"2\">".cms_generiere_input('cms_mailer_passwort', $CMS_MAIL['Passwort'], "password")."</td></tr>";
	if ($CMS_MAIL['SMTP-Authentifizierung'] == 'true') {$CMS_MAIL['SMTP-Authentifizierung'] = 1;} else {$CMS_MAIL['SMTP-Authentifizierung'] = 0;}
	$code .= "<tr><th>SMTP-Authentifizierung:</th><td colspan=\"2\">".cms_generiere_schieber('mailer_authentifizierung', $CMS_MAIL['SMTP-Authentifizierung'])."</td></tr>";
	$code .= "</table>";

	$code .= "<h2>Signaturen</h2>";

	$code .= "<h3>HTML</h3>";
	$code .= cms_webeditor('cms_mailer_signatur_html', $CMS_MAIL['Signatur HTML']);
	$code .= "<h3>Nur Text</h3>";
	$code .= "<p><textarea class=\"cms_textarea\" id=\"cms_mailer_signatur_text\" name=\"cms_mailer_signatur_text\">".$CMS_MAIL['Signatur Text']."</textarea></p>";

	$warnung  = '<h4>Folgen falscher Zugangsdaten</h4><p>Wenn hier falsche Zugangsdaten eingegeben werden, sind folgende Funktionen nicht mehr möglich:</p>';
	$warnung .= '<ul><li>Es werden keine Passwörter zugestellt, wenn das Passwort vergessen wurde.</li>';
	$warnung .= '<li>Es werden keine Zugangsdaten an neue Personen verschickt.</li>';
	$warnung .= '<li>Personen erhalten keine Benachrichtigung mehr, wenn ihr Konto gelöscht wurde.</li>';
	$warnung .= '<li>Informationen über neue Nachrichten im Postfach können systemweit nicht verschickt werden.</li>';
	$warnung .= '<li>Informationen über neue Vertretungen können systemweit nicht verschickt werden.</li>';
	$warnung .= '<li>Informationen über Neuigkeiten können systemweit nicht verschickt werden.</li></ul>';
	$warnung .= '<p>Vor der Speicherung der neuen Daten sollten die neuen Zugangsdaten daher getestet werden.</p>';
	$code .= cms_meldung('warnung', $warnung);
	$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schulhof_verwaltung_testmail();\">Testmail mit neuen Zugangdaten an mich</span></p>";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_schulmailer_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";

	echo $code;
}
else {
	cms_meldung_berechtigung();
	echo "</div><div class=\"cms_clear\"></div>";
}

?>
