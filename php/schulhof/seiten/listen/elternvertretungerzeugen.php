<?php
function cms_listen_elternvertreter_ausgeben($dbs) {
	global $CMS_SCHLUESSEL, $CMS_BENUTZERSCHULJAHR;
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$code = "";
	// Elternbeiratsvorsitzende
	$liste = "";
	/*personen.id AS id, titel, vorname, nachname, klasse, stufe*/
	$sql = "SELECT DISTINCT person FROM schluesselposition WHERE schluesselposition.schuljahr = $CMS_BENUTZERSCHULJAHR AND position = AES_ENCRYPT('Elternbeiratsvorsitzende', '$CMS_SCHLUESSEL')";
	$sql = "SELECT y.id AS id, vorname, nachname, titel, nutzerkonten.id AS nutzerkonto FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM ($sql) AS x JOIN personen ON x.person = personen.id) AS y LEFT JOIN nutzerkonten ON y.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC, titel ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
			$vorname = $daten['vorname'];
			$nachname = $daten['nachname'];
			$titel = $daten['titel'];
			$nutzerkonto = $daten['nutzerkonto'];

			$liste .= "<tr>";
			$liste .= "<td><img src=\"res/icons/klein/elter.png\"></td>";
			$liste .= "<td>".$titel."</td>";
			$liste .= "<td>".$vorname."</td>";
			$liste .= "<td>".$nachname."</td>";
			$link = "";
			if (!is_null($nutzerkonto)) {
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
		$liste = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
	}
	$code .= "<h3>Elternbeiratsvorsitzende</h3>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Kontakt</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$code .= $liste;
		$code .= "</tbody>";
	$code .= "</table>";

	return $code;
}
?>
