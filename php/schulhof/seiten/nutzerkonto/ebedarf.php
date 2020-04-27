<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php

echo "<h1>Bedarf für ein Notebook oder Tablet</h1>";
$ausgefuellt = false;
$sql = $dbs->prepare("SELECT COUNT(*) FROM ebedarf WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($anzahl);
  if ($sql->fetch()) {
    if ($anzahl != 0) {$ausgefuellt = true;}
  }
}
$sql->close();

if ($ausgefuellt) {
	$meldung = "<h4>Bedarfsabfrage für Notebooks oder Tablets</h4>";
	$meldung .= "<p>Es wurde bereits an der Bedarfsabfrage teilgenommen.</p>";
	echo cms_meldung("warnung", $meldung);
}
else {
  $meldung = "<h4>Bedarfsabfrage für Notebooks oder Tablets</h4>";
	$meldung .= "<p>Je höher die Stückzahlen, desto höher wird auch der Mengenrabatt sein. Wir bemühen uns um qualitätiv hochwertige Geräte, müssen uns aber an den von Ihnen vorgegebenen Preisrahmen halten. Diese Erhebung ist kein Kaufvertrag, sollte aber nur dann eingereicht werden, wenn auch wirklich Interesse an einem Gerät besteht.</p><p>Vor der endgültigen Anschaffung wird über das Gerät informiert und eine telefonische Zusage eingeholt.</p><p>Alle gemachten Angaben sind freiwillig, werden verschlüsselt gespeichert und vertraulich behandelt! Die Daten werden am 04. Mai 2020 gelöscht.</p>";
	echo cms_meldung("info", $meldung);
  $code = "<h2>Diese Umfrage endet am Dienstag, den 28. April 2020 um 23:59:59 Uhr</h2>";
  if (time() < mktime (23, 59, 59, 4, 28, 2020)) {
    $code .= "<table class=\"cms_formular\">";
      $code .= "<tr><th>Bedarf:</th><td><select id=\"cms_ebedarf_bestehend\" name=\"cms_ebedarf_bestehend\" onchange=\"cms_ebestand_wechsel()\" onkeyup=\"cms_ebestand_wechsel()\"><option value=\"0\">Es besteht kein Bedarf.</option><option value=\"1\">Es besteht Bedarf, und ich kann bis zu X € dafür bezahlen.</option><option value=\"2\">Es besteht Bedarf, aber im Moment ist aus finanziellen Gründen keine Anschaffung möglich.</option></select></td><td></td></tr>";
      $code .= "<tr id=\"cms_eBedarf0\" style=\"display:none\"><th>Förderprogramme:</th><td>Bitte beachten Sie, dass ein 150,- € Zuschuss der Bundesregierung für bedürftige Familien angekündigt wurde.<br>Leider ist noch nichts näheres zum Beantragen dieser Förderung bekannt.</td><td></td></tr>";
      $code .= "<tr id=\"cms_eBedarf1\" style=\"display:none\"><th>Maximalpreis:</th><td><input class=\"cms_gross\"id=\"cms_ebedarf_preis\" name=\"cms_ebedarf_preis\" type=\"number\" min=\"1\" value=\"200\"> €</td><td></td></tr>";
      $code .= "<tr id=\"cms_eBedarf2\" style=\"display:none\"><th>Telefonnummer:</th><td><input name=\"cms_schulhof_ebedarf_telefon\" id=\"cms_schulhof_ebedarf_telefon\" type=\"text\"></td><td></td></tr>";
      $code .= "<tr id=\"cms_eBedarf3\" style=\"display:none\"><th>Telefonnummer wiederholen:</th><td><input name=\"cms_schulhof_ebedarf_telefon_wiederholen\" id=\"cms_schulhof_ebedarf_telefon_wiederholen\" type=\"text\" onkeyup=\"cms_check_passwort_gleich('ebedarf_telefon')\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebedarf_telefon_gleich_icon\"></span></td></tr>";

      $code .= "<tr id=\"cms_eBedarf4\" style=\"display:none\"><th>eMailadresse:</th><td><input name=\"cms_schulhof_ebedarf_mail\" id=\"cms_schulhof_ebedarf_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('ebedarf_mail');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebedarf_mail_icon\"></span></td></tr>";
      $code .= "<tr id=\"cms_eBedarf5\" style=\"display:none\"><th>eMailadresse wiederholen:</th><td><input name=\"cms_schulhof_ebedarf_mail_wiederholen\" id=\"cms_schulhof_ebedarf_mail_wiederholen\" type=\"text\" onkeyup=\"cms_check_passwort_gleich('ebedarf_mail')\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebedarf_mail_gleich_icon\"></span></td></tr>";
    $code .="</table>";
    $code .= "<p><span class=\"cms_button\" onclick=\"cms_ebedarf_speichern()\">Meinen Bedarf übermitteln</span> <a class=\"cms_button cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></p>";
  }
  else {
    $meldung  = "<h4>Zu spät...</h4>";
  	$meldung .= "<p>Die Umfrage ist bereits beendet.</p>";
  	$code .= cms_meldung("info", $meldung);
  }

  echo $code;
}

?>
</div>
