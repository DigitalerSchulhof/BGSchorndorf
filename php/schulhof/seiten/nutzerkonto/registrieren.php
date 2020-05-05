<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Willkommen auf dem Schulhof des <?php echo $CMS_WICHTIG['Schulname Genitiv'];?>!</h1>

<h2>Registrieren</h2>

<?php
$CMS_EINWILLIGUNG_A = false;

echo cms_meldung("warnung", "<h4>Mehrfache Registrierungen</h4><p>Bitte registrieren Sie sich nicht mehrmals!! Eine Registrierung ist eingegangen, wenn Sie die entsprechende Meldung erhalten. Die Verknüpfung mit einer Person im Schulhof erfolgt händisch innerhalb 24 Stunden. Sie erhalten eine eMail, wenn die Registrierung abgeschlossen ist.</p>");

if (isset($_SESSION["DSGVO_EINWILLIGUNG_A"])) {$CMS_EINWILLIGUNG_A = $_SESSION["DSGVO_EINWILLIGUNG_A"];}
if (!$CMS_EINWILLIGUNG_A) {echo cms_meldung_einwilligungA();}
else {
echo "<table class=\"cms_formular\">";
	echo "<tr><th>Titel:</th><td colspan=\"2\"><input name=\"cms_registrierung_titel\" id=\"cms_registrierung_titel\" type=\"text\"></td></tr>";
	echo "<tr><th>Vorname:</th><td colspan=\"2\"><input name=\"cms_registrierung_vorname\" id=\"cms_registrierung_vorname\" type=\"text\"></td></tr>";
	echo "<tr><th>Nachname:</th><td colspan=\"2\"><input name=\"cms_registrierung_nachname\" id=\"cms_registrierung_nachname\" type=\"text\"></td></tr>";
	echo "<tr><th>Klasse:</th><td colspan=\"2\"><input name=\"cms_registrierung_klasse\" id=\"cms_registrierung_klasse\" type=\"text\"></td></tr>";
	echo "<tr><th>Passwort:</th><td><input name=\"cms_schulhof_registrierung_passwort\" id=\"cms_schulhof_registrierung_passwort\" type=\"password\" onkeyup=\"cms_check_passwort_staerke('registrierung_passwort')\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_registrierung_passwort_staerke_icon\"><img src=\"res/icons/klein/falsch.png\"></span></td></tr>";
	echo "<tr><th>Passwort wiederholen:</th><td><input name=\"cms_schulhof_registrierung_passwort_wiederholen\" id=\"cms_schulhof_registrierung_passwort_wiederholen\" type=\"password\" onkeyup=\"cms_check_passwort_gleich('registrierung_passwort')\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_registrierung_passwort_gleich_icon\"></span></td></tr>";
	echo "<tr><th>eMailadresse:</th><td><input name=\"cms_schulhof_registrierung_mail\" id=\"cms_schulhof_registrierung_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('cms_schulhof_registrierung_mail');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_registrierung_mail_icon\"></span></td></tr>";
	echo "<tr><th>Datenschutz:</th><td><p>".cms_generiere_schieber('registrierung_datenschutz', 0)." Ich bin mit den Datenschutzvorkehrungen des Digitalen Schulhofs einverstanden und erteile meine Erlaubnis zur Datenverarbeitung.</p><p><a class=\"cms_button cms_button_wichtig\" target=\"_blank\" href=\"Website/Datenschutz\">Datenschutzvereinbarung lesen</a></p></td></tr>";
	echo "<tr><th>Entscheidungsberechtigung:</th><td>".cms_generiere_schieber('registrierung_volljaehrig', 0)." Ich bin 18 Jahre alt oder älter, oder ein Erziehungsberechtigter hat mir erlaubt, diese Registrierung durchzuführen.</td></tr>";
	echo "<tr><th>Korrektheit:</th><td>".cms_generiere_schieber('registrierung_korrekt', 0)." Meine Angaben sind nach bestem Wissen und Gewissen korrekt.</td></tr>";
	echo "<tr><th>Sicherheitsabfrage zur Spamverhinderung:</th><td colspan=\"2\">".cms_captcha_generieren("cms_registrierung_spamschutz")." Bitte übertragen Sie die Buchstaben und Zahlen aus dem Bild in der korrekten Reihenfolge in das nachstehende Feld.</td></tr>";
	echo "<tr><th></th><td colspan=\"2\"><input type=\"text\" name=\"cms_spamverhinderung\" id=\"cms_spamverhinderung\"></td></tr>";
	echo "<tr><td></td><td colspan=\"2\"><span class=\"cms_button_ja\" onclick=\"cms_registrieren(this);\">Registrieren</span></td></tr>";
echo "</table>";
}
?>

<p><a class="cms_button" href="Schulhof/Anmeldung">zur Anmeldung</a></p>

</div>
<div class="cms_clear"></div>
