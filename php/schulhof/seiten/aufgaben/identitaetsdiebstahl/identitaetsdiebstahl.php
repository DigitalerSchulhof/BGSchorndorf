<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Identitätsdiebstähle behandeln</h1>";

if (cms_angemeldet() cms_r("schulhof.verwaltung.nutzerkonten.verstöße.identitätsdiebstahl")) {
	$dbs = cms_verbinden('s');
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Person</th><th>Eingegangen</th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$sql = "SELECT personen.id, zeit, erstellt, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM identitaetsdiebstahl JOIN personen ON identitaetsdiebstahl.id = personen.id JOIN nutzerkonten ON nutzerkonten.id = personen.id";
		$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY zeit DESC");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($pid, $pzeit, $perstellt, $pvor, $pnach, $ptitel);
			while ($sql->fetch()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td><img src=\"res/icons/klein/identitaetsdiebstahl.png\"></td>";
					if ($perstellt < $pzeit) {
						$name = cms_generiere_anzeigename($pvor, $pnach, $ptitel);
					}
					else {
						$name = "<i>existiert nicht mehr</i>";
					}
					$datum = date('d.m.Y', $pzeit)." um ".date('H:i', $pzeit);
					$ausgabe .= "<td>$name</td>";
					$ausgabe .= "<td>$datum</td>";
					// Aktionen
					$ausgabe .= "<td>";
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_identitaetsdiebstahl_loeschen_anzeigen($pid, '$pzeit', '$name', '$datum');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
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
