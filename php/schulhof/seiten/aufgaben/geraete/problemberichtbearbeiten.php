<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Problembericht bearbeiten</h1>";

if (isset($_SESSION["GERAETESTANDORT"])) {$id = $_SESSION["GERAETESTANDORT"];} else {$id = '-';}
if (isset($_SESSION["GERAETEART"])) {$art = $_SESSION["GERAETEART"];} else {$art = '-';}

if ((cms_check_ganzzahl($id,0)) && (($art == 'r') || ($art == 'l'))) {
	$zugriff = $CMS_RECHTE['Technik']['Geräte verwalten'];
	$fehler = false;

	if ($fehler) {$zugriff = false;}

	$angemeldet = cms_angemeldet();

	if ($angemeldet && $zugriff) {
		// Gibt es einen externen Sachverständigen
		$EINSTELLUNGEN = cms_einstellungen_laden();

		// Information über den Standort laden
		$standortbezeichnung = "";
		$dbs = cms_verbinden('s');
		if ($art == 'r') {
			$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id = ?");
			$ort = 'Raum';
			$geraetetabelle = "raeumegeraete";
		}
		if ($art == 'l') {
			$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihen WHERE id = ?");
			$ort = 'Leihgeräte';
			$geraetetabelle = "leihengeraete";
		}
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
	    $sql->bind_result($standortbezeichnung);
	    $sql->fetch();
	  }
	  $sql->close();

		// Hausmeister laden
		$jetzt = time();
		$hausmeisterzahl = 0;
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schluesselposition WHERE position = AES_ENCRYPT('Hausmeister', '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE beginn <= ? AND ende >= ?)");
		$sql->bind_param("ii", $jetzt, $jetzt);
		if ($sql->execute()) {
	    $sql->bind_result($hausmeisterzahl);
	    $sql->fetch();
	  }
	  $sql->close();

		$code .= "<h2>$ort » $standortbezeichnung</h2>";

		$geraete = array();
		$anzahl = 0;
		$sqlspalten = "$geraetetabelle.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, statusnr, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS meldung, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS kommentar, zeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt";
		$sql = $dbs->prepare("SELECT * FROM (SELECT $sqlspalten FROM $geraetetabelle LEFT JOIN personen ON $geraetetabelle.absender = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE standort = $id AND statusnr > 0) AS x ORDER BY bezeichnung");
		if ($sql->execute()) {
			$sql->bind_result($gid, $bez, $statusnr, $gmeldung, $gkommentar, $gzeit, $gvorname, $gnachname, $gtitel, $gerstellt);
			while ($sql->fetch()) {
				$zcode = "";
				$zcode .= "<h3>".$bez."</h3>";
				$zcode .= "<table class=\"cms_formular\">";
				$zcode .= "<tr>";
				$zcode .= "<th>Status:</th>";
				$status = cms_status_generieren($statusnr);
				$zcode .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/".$status['icon']."\"></span> ".$status['text']."</td>";
				$zcode .= "</tr>";
				$zcode .= "<tr>";
				$zcode .= "<th>Eingegangen:</th>";
				if ($gerstellt < $gzeit) {$anzeigename = cms_generiere_anzeigename($gvorname, $gnachname, $gtitel);}
				else {$anzeigename = "<i>existiert nicht mehr</i>";}
				$zcode .= "<td>von $anzeigename am ".date('d.m.Y', $gzeit)." um ".date('H:i', $gzeit)."</td>";
				$zcode .= "</tr>";
				$zcode .= "<tr>";
				$zcode .= "<th>Neuer Status:</th>";
				$zcode .= "<td><select id=\"cms_geraete_status_$gid\" name=\"cms_geraete_status_$gid\">";
				if ($statusnr == 1) {$wahl = "selected=\"selected\"";} else {$wahl = "";}
				$zcode .= "<option $wahl value=\"1\">Meldung eingegangen</option>";
				if ($statusnr == 2) {$wahl = "selected=\"selected\"";} else {$wahl = "";}
				$zcode .= "<option $wahl value=\"2\">eingeschränkt nutzbar</option>";
				if ($statusnr == 3) {$wahl = "selected=\"selected\"";} else {$wahl = "";}
				$zcode .= "<option $wahl value=\"3\">defekt - interne Problemlösung</option>";
				if ($statusnr == 4) {$wahl = "selected=\"selected\"";} else {$wahl = "";}
				$zcode .= "<option $wahl value=\"4\">defekt - externe Problemlösung</option>";
				$zcode .= "</select></td>";
				$zcode .= "</tr>";
				$zcode .= "<tr>";
				$zcode .= "<th>Meldung:</th>";
				$zcode .= "<td><textarea id=\"cms_geraete_meldung_$gid\" name=\"cms_geraete_meldung_$gid\" rows=\"3\" cols=\"10\" disabled=\"disabled\">$gmeldung</textarea></td>";
				$zcode .= "</tr>";
				$zcode .= "<tr>";
				$zcode .= "<th>Kommentar:</th>";
				$zcode .= "<td><textarea id=\"cms_geraete_kommentar_$gid\" name=\"cms_geraete_kommentar_$gid\" rows=\"3\" cols=\"10\">$gkommentar</textarea></td>";
				$zcode .= "</tr>";
				$zcode .= "</table>";
				$zcode .= "<p><span class=\"cms_button\" onclick=\"cms_geraete_problembericht_aendern('$gid')\">Änderungen speichern</span> <span class=\"cms_button_ja\" onclick=\"cms_geraete_problembericht_funktioniert('$gid')\">Problem behoben</span></p>";
				if (($EINSTELLUNGEN['Externe Geräteverwaltung1 existiert'] == 1) || ($EINSTELLUNGEN['Externe Geräteverwaltung2 existiert'] == 1)) {$zcode .= "<p>";}
				if ($EINSTELLUNGEN['Externe Geräteverwaltung1 existiert'] == 1) {
					$anzeigename = cms_generiere_anzeigename($EINSTELLUNGEN['Externe Geräteverwaltung1 Vorname'], $EINSTELLUNGEN['Externe Geräteverwaltung1 Nachname'], $EINSTELLUNGEN['Externe Geräteverwaltung1 Titel']);
					$zcode .= "<span class=\"cms_button_wichtig\" onclick=\"cms_geraete_problembericht_extern('$gid', '1')\">Meldung per Mail an $anzeigename schicken (externe Geräteverwaltung)</span>";
				}
				if ($EINSTELLUNGEN['Externe Geräteverwaltung2 existiert'] == 1) {
					$anzeigename = cms_generiere_anzeigename($EINSTELLUNGEN['Externe Geräteverwaltung2 Vorname'], $EINSTELLUNGEN['Externe Geräteverwaltung2 Nachname'], $EINSTELLUNGEN['Externe Geräteverwaltung2 Titel']);
					$zcode .= "<span class=\"cms_button_wichtig\" onclick=\"cms_geraete_problembericht_extern('$gid', '2')\">Meldung per Mail an $anzeigename schicken (externe Geräteverwaltung)</span>";
				}
				if (($hausmeisterzahl > 0) && ($CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen'])) {
					$zcode .= "<span class=\"cms_button_wichtig\" onclick=\"cms_geraete_problembericht_hausmeister('$gid')\">Meldung in Hausmeisterauftrag umwandeln</span>";
				}
				if (($EINSTELLUNGEN['Externe Geräteverwaltung1 existiert'] == 1) || ($EINSTELLUNGEN['Externe Geräteverwaltung2 existiert'] == 1)) {$zcode .= "</p>";}
				$geraete[$anzahl] = $zcode;
				$anzahl ++;
			}
		}
		$sql->close();

		if (count($geraete) == 0) {
			$inhalt = "<h4>Keine Beanstandungen</h4>";
			if ($art == 'raum') {$inhalt .= "<p>Für diesen Raum liegen keine beanstandeten Geräte vor.</p>";}
			else if ($art == 'leihgeraet') {$inhalt .= "<p>Für diese Leihgeräte liegen keine beanstandeten Geräte vor.</p>";}
			$code .= cms_meldung('info', $inhalt);
			$code .= "<p><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Geräte_verwalten\">Zurück zur Geräteverwaltung</a> <a class=\"cms_button\" href=\"Schulhof/Nutzerkonto\">Zurück zum Nutzerkonto</a></p>";
		}

		$code .= "</div>";

		if (count($geraete) > 0) {
			$spalten = 0;
			$elementeprospalte = ceil(count($geraete) / 2);
			$anzahlausgabe = 0;
			for ($spalte = 1; $spalte <= 2; $spalte++) {
				$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
				for ($i = 0; (($i<$elementeprospalte) && ($anzahlausgabe < $anzahl)); $i++) {
					$code .= $geraete[$anzahlausgabe];
					$anzahlausgabe ++;
				}
				$code .= "</div></div>";
			}

			$code .= "<div class=\"cms_clear\"></div>";
		}

		cms_trennen($dbs);
	}
	else {
		$code .= cms_meldung_berechtigung();
		$code .= "</div>";
	}
}
else {
	$code .= cms_meldung_bastler();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
