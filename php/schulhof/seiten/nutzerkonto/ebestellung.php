<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$bestellende = mktime (23, 59, 59, 5, 31, 2020);

echo "<h1>Bestellung / Leihe für Notebooks oder Tablets</h1>";
$teilgenommen = false;
$sql = $dbs->prepare("SELECT COUNT(*), bedarf, AES_DECRYPT(anrede, '$CMS_SCHLUESSEL'), AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(hausnr, '$CMS_SCHLUESSEL'), AES_DECRYPT(plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(telefon, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL'), bedingungen, eingegangen FROM ebestellung WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($anzahl, $bedarf, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort,  $telefon, $mail, $bedingungen, $eingegangen);
  $sql->fetch();
}
$sql->close();

$sql = $dbs->prepare("SELECT AES_DECRYPT(token, '$CMS_SCHLUESSEL') FROM etoken WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($token);
  $sql->fetch();
}
$sql->close();

if ($token === NULL) {
	$token = "<i>Der Shop hat noch keinen Code bereitgestellt!</i>";
}

if (time() <= $bestellende) {
	$code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Bedarf:</th><td><select id=\"cms_ebestellung_bedarf\" name=\"cms_ebestellung_bedarf\" onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\">";

		$optionen = "<option value=\"0\">Es besteht kein Bedarf.</option><option value=\"1\">Ich möchte Geräte bestellen.</option><option value=\"2\">Es besteht Bedarf, aber im Moment ist aus finanziellen Gründen keine Anschaffung möglich. Ich will ein Gerät leihen!</option>";
		$code .= str_replace("value=\"$bedarf\"", "value=\"$bedarf\" selected=\"selected\"", $optionen);
		$code .= "</select></td><td></td></tr>";
	$code  .= "</table>";


	$code .= "<div id=\"cms_ebestellung_geraete\" class=\"cms_bestellen_box\" style=\"display:none\">";
	$code .= "<h2>Geräteauswahl</h2>";
	$meldung = cms_meldung("warnung", "<p>Hier wird in den nächsten 24 Stunden ein Link zu einem Online-Shop erscheinen. Dort können Sie unter Verwendung eines Codes Geräte mit den Schul-Rabatten erwerben. Bitte beachten Sie, dass ein Code <b>nur einmal</b> benutzt werden kann. <b>Es werden keine weiteren Codes ausgegeben!</b></p><p>Ihr Code lautet: <b>".$token."</b></p>");
	$code .= $meldung;
	$code .= "</div>";

	$code .= "<div id=\"cms_ebestellung_kontakt\" class=\"cms_bestellen_box\" style=\"display:none\">";
	$code .= "<h2>Kontaktdaten</h2>";
	$code .= "<p>Hier müssen die Kontaktdaten einer geschäftsfähigen Person (volljährig) eingegeben werden, die für den Kauf oder die Leihe verantwortlich ist.</p>";
	$code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Anrede:</th><td colspan=\"2\"><select id=\"cms_ebestellung_anrede\" name=\"cms_ebestellung_anrede\">";
		$optionen = "<option value=\"-\"></option><option value=\"Frau\">Frau</option><option value=\"Herr\">Herr</option>";
		$code .= str_replace("value=\"$anrede\"", "value=\"$anrede\" selected=\"selected\"", $optionen);
		$code .= "</select></td><td></td></tr>";

		$code .= "<tr><th>Vorname:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_vorname", $vorname)."</td></tr>";
		$code .= "<tr><th>Nachname:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_nachname", $nachname)."</td></tr>";
		$code .= "<tr><th>Straße:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_strasse", $strasse)."</td></tr>";
		$code .= "<tr><th>Hausnummer:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_hausnr", $hausnr)."</td></tr>";
		$code .= "<tr><th>Postleitzahl:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_plz", $plz)."</td></tr>";
		$code .= "<tr><th>Ort:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_ort", $ort)."</td></tr>";
		$code .= "<tr><th>Telefonnummer:</th><td><input name=\"cms_schulhof_ebestellung_telefon\" id=\"cms_schulhof_ebestellung_telefon\" type=\"text\" value=\"$telefon\"></td><td></td></tr>";
		$code .= "<tr><th>Telefonnummer wiederholen:</th><td><input name=\"cms_schulhof_ebestellung_telefon_wiederholen\" id=\"cms_schulhof_ebestellung_telefon_wiederholen\" type=\"text\" onkeyup=\"cms_check_passwort_gleich('ebestellung_telefon')\" value=\"$telefon\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebestellung_telefon_gleich_icon\"></span></td></tr>";
		$code .= "<tr><th>eMailadresse:</th><td><input name=\"cms_schulhof_ebestellung_mail\" id=\"cms_schulhof_ebestellung_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('cms_schulhof_ebestellung_mail');\" value=\"$mail\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebestellung_mail_icon\"></span></td></tr>";
		$code .= "<tr><th>eMailadresse wiederholen:</th><td><input name=\"cms_schulhof_ebestellung_mail_wiederholen\" id=\"cms_schulhof_ebestellung_mail_wiederholen\" type=\"text\" onkeyup=\"cms_check_passwort_gleich('ebestellung_mail')\" value=\"$mail\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebestellung_mail_gleich_icon\"></span></td></tr>";

		$meldung = cms_meldung("info", "<p>Die Schule behält sich vor ein passendes Gerät für den anfallenden Bedarf auszuwählen. Das Gerät wird in neuem Zustand übergeben und ist pfleglich zu behandeln. Dieses Gerät wird mit quelloffenem Betriebssystem und quelloffener Software von der Schule so eingerichtet sein, dass eine Teilnahme am Unterricht in jeder notwendigen Form möglich ist. Gleichzeitig wird dem Benutzer das Administratorrecht auf dem Gerät eingeräumt.</p><p>Die Schule setzt das Gerät nach der Rückgabe vollständig zurück. Für Schäden oder Fehler, die während der Leihe durch den Entleiher verursacht wurden, ist allein der Entleiher verantwortlich und hat sie zu Ersetzen, wenn das Gerät zurückgegeben wird.</p><p>Diese Leihbedingungen können weiter ergänzt werden und müssen bei Erhalt des Gerätes signiert werden.</p>");
		$code .= "<tr id=\"cms_ebestellung_bedingung\"><th>Leihbedingungen:</th><td colspan=\"2\">$meldung</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_bedingung_akzept\"><th>Bedingungen akezptiert:</th><td colspan=\"2\">".cms_generiere_schieber("bedingungen", 0)." Bedingungen gelesen, verstanden und akzeptiert</td></tr>";
	$code  .= "</table>";
	$code .= "</div>";

	$code .= "<p><span class=\"cms_button\" onclick=\"cms_ebestellung_speichern()\" id=\"cms_ebestellung_speichern\">Zahlungspflichtig bestellen</span> <a class=\"cms_button cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></p>";

	$code .= "<script>cms_ebestellung_aktualisieren();</script>";
}
else {
	$code .= cms_meldung("info", "<p><b>Die Bestell- und Leihfrist ist abgelaufen.</b></p>");
}

echo $code;

?>
</div>
