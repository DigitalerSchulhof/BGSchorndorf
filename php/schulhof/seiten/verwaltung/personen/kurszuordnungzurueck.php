<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.verwaltung.personen.zuordnen.kurse")) {
	$SCHULJAHRE = "";
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
	if ($sql->execute()) {
		$sql->bind_result($sid, $sbez);
		while ($sql->fetch()) {
			$SCHULJAHRE .= "<option value=\"$sid\">$sbez</option>";
		}
	}
	$sql->close();
	$code .= "<h1>Kurszuordnung zurücksetzen</h1>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Schuljahr</th><td><select id=\"cms_kurszuordnung_zurueck_schuljahr\" name=\"cms_kurszuordnung_zurueck_schuljahr\">$SCHULJAHRE</select></td></tr>";
	$code .= "</table>";
	$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_kurszuordnung_zuruecksetzen_anzeigen();\">Kurszuordnung zurücksetzen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Abbrechen</a></p>";
}
else {$code .= cms_meldung_berechtigung();}
echo $code;
?>

</div>
