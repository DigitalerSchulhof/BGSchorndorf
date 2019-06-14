<?php
function cms_listen_stufen_ausgeben($dbs, $stufegewaehlt) {
	global $CMS_SCHLUESSEL;
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$code = "";
	// Schüler der Stufe ausgeben
	$liste = "";

	$sql = "SELECT x.id AS id, vorname, nachname, titel, nutzerkonten.id AS nutzerkonto FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN klassenmitglieder ON personen.id = klassenmitglieder.person JOIN klassen ON klassenmitglieder.gruppe = klassen.id WHERE klassen.stufe = $stufegewaehlt) AS x LEFT JOIN nutzerkonten ON x.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC, titel ASC";
	$anzahl = 0;
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
			$vorname = $daten['vorname'];
			$nachname = $daten['nachname'];
			$titel = $daten['titel'];
			$nutzerkonto = $daten['nutzerkonto'];
			$anzahl ++;

			$liste .= "<tr>";
			$liste .= "<td><img src=\"res/icons/klein/schueler.png\"></td>";
			$liste .= "<td>".$anzahl."</td>";
			$liste .= "<td>".$titel."</td>";
			$liste .= "<td>".$vorname."</td>";
			$liste .= "<td>".$nachname."</td>";
			$link = "";
			if (!is_null($nutzerkonto) && (in_array($id, $POSTEMPFAENGERPOOL))) {
				$link = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $id)\">Nachricht schreiben</span>";
			}
			else {
				$anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
				$link = "<span class=\"cms_button_passiv\">Nachricht schreiben</span>";
			}
			$liste .= "<td>$link</td>";
			$liste .= "</tr>";

		}
		$anfrage->free();
	}
	if (strlen($liste) == 0) {
		$liste = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
	}
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Kontakt</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$code .= $liste;
		$code .= "</tbody>";
	$code .= "</table>";
	return $code;
}
?>
