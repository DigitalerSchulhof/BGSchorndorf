<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Personen']['Personen den Kursen zuordnen'];
$code = "";
if ($zugriff) {
  $SCHULJAHRE = "";
  $STUFEN = "";
  $SCHULJAHR = null;
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
	if ($sql->execute()) {
		$sql->bind_result($sid, $sbez);
		while ($sql->fetch()) {
      if ($SCHULJAHR === null) {$SCHULJAHR = $sid;}
			$SCHULJAHRE .= "<option value=\"$sid\">$sbez</option>";
		}
	}
	$sql->close();

  $code .= "<h1>Kurszuordnungen durch Klassen und Regelunterricht</h1>";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Schuljahr:</th><td>";
    $code .= "<select name=\"cms_kurszuordnungls_schuljahr\" id=\"cms_kurszuordnungls_schuljahr\">$SCHULJAHRE</select></td></tr>";
  $code .= "</table>";

  $code .= cms_meldung('info','<h4>Zu viele Zuordnungen</h4><p>Mit dieser Funktion werden allen Kursen die Schüler zugeordnet, die sich in den zugeordneten Klassen befinden. Das wird bei Klappklassen zu zu vielen Schülern führen. Es muss nachbearbeitet werden!</p><p>Als Lehrer wird den Kursen der Lehrer zugewiesen, der im Regelstundenplan für diesen Kurs hinterlegt ist. Gibt es mehrere Lehrer, die ein Fach unterrichten, so werden mehrere Lehrer hinzugefügt.</p>');

	$code .= "<p><span class=\"cms_button\" onclick=\"cms_kurszuordnungklassenregelunterricht_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Abbrechen</a></p>";
}
else {
	$code .= "<h1>Personen-IDs importieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
