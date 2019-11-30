<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if ($CMS_RECHTE['Personen']['Personen löschen']) {
	$SCHULJAHRE = "";
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

	$SCHUELER = "";
	$SCHUELERIDS = "";
	if ($SCHULJAHR !== null) {
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND id NOT IN (SELECT person FROM kursemitglieder JOIN kurse ON kursemitglieder.gruppe = kurse.id WHERE schuljahr = ?) AND id NOT IN (SELECT person FROM klassenmitglieder JOIN klassen ON klassenmitglieder.gruppe = klassen.id WHERE schuljahr = ?) AND id NOT IN (SELECT person FROM stufenmitglieder JOIN stufen ON stufenmitglieder.gruppe = stufen.id WHERE schuljahr = ?)) AS x ORDER BY nachname, vorname, titel");
		$sql->bind_param("iii", $SCHULJAHR, $SCHULJAHR, $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($sid, $svor, $snach, $stitel);
			while ($sql->fetch()) {
				$SCHUELER .= "<li>".cms_generiere_anzeigename($svor, $snach, $stitel)."</li>";
				$SCHUELERIDS .= "|".$sid;
			}
		}
		$sql->close();
	}

	$code .= "<h1>Nicht zugeordnete Schüler löschen</h1>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Schuljahr</th><td><select id=\"cms_nicht_zugeordnet_schuljahr\" name=\"cms_nicht_zugeordnet_schuljahr\" onchange=\"cms_nicht_zugeordnet_schueler_laden();\">$SCHULJAHRE</select></td></tr>";
	$code .= "<tr><th>Betroffene Schüler:</th><td id=\"cms_nicht_zugeordnet_schueler_feld\">";
	if (strlen($SCHUELER) > 0) {$code .= "<ul>".$SCHUELER."</ul>";}
	else {$code .= "<p class=\"cms_notiz\">-- Keine Schüler ohne Zuordnung in diesem Schuljahr gefunden --</p>";}
	$code .= "<input type=\"hidden\" id=\"cms_nicht_zugeordnet_schueler\" name=\"cms_nicht_zugeordnet_schueler\" value=\"$SCHUELERIDS\"></td></tr>";
	$code .= "</table>";
	$code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_nicht_zugeordnet_loeschen_anzeigen();\">Nicht zugeordnete Schüler löschen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Abbrechen</a></p>";
}
else {$code .= cms_meldung_berechtigung();}
echo $code;
?>

</div>
