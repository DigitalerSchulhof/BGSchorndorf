<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Registrierungen aufnehmen</h1>";

if (cms_angemeldet() && cms_r("schulhof.verwaltung.nutzerkonten.anlegen")) {
	$dbs = cms_verbinden('s');

	// Offene Nutzerkonten anzeigen
	$OPTIONEN = "";
	$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname FROM personen WHERE id NOT IN (SELECT id FROM nutzerkonten)) AS x ORDER BY nachname, vorname, titel");
	if ($sql->execute()) {
		$sql->bind_result($persid, $titel, $vorname, $nachname);
		while ($sql->fetch()) {
			$OPTIONEN .= "<option value=\"$persid\">".$nachname.", ".$vorname."</option>";
		}
	}

	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Name</th><th>Mail</th><th>Zuordnung</th><th>Benutzername</th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(email, '$CMS_SCHLUESSEL') AS email, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname FROM nutzerregistrierung) AS x ORDER BY nachname, vorname, titel");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($resid, $titel, $email, $vorname, $nachname);
			while ($sql->fetch()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td><img src=\"res/icons/klein/details.png\"></td>";
					$ausgabe .= "<td>".cms_generiere_anzeigename($vorname, $nachname, $titel)."</td>";
					$ausgabe .= "<td>".$email."</td>";
					$ausgabe .= "<td><select name=\"cms_registrierung_per_$resid\" id=\"cms_registrierung_per_$resid\">$OPTIONEN</select></td>";
					$benutzername = substr($nachname, 0, min(8,strlen($nachname))).substr($vorname, 0, min(3,strlen($vorname)))."-bg";
					$ausgabe .= "<td><input name=\"cms_registrierung_ben_$resid\" id=\"cms_registrierung_ben_$resid\" type=\"text\" value=\"$benutzername\"></td>";
					// Aktionen
					$ausgabe .= "<td>";
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_registrieren_uebernehmen($resid);\"><span class=\"cms_hinweis\">Zuordnen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_registrieren_loeschen_anzeigen($resid, '$vorname', '$nachname');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
		}
		$sql->close();

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
		}

		$code .= $ausgabe;
		$code .= "</tbody>";
	$code .= "</table>";
	cms_trennen($dbs);
	$code .= "</div>";
}
else {
	$code .= cms_meldung_berechtigung();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
