<?php
function cms_rolle_ausgeben ($rolle) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";
	$hiddencode = "";
	$altekategorie = "";

	$bezeichnung = "";
	$art = "s";
	if ($rolle != "") {
		$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(personenart, '$CMS_SCHLUESSEL') AS personenart FROM rollen WHERE id = $rolle";
		if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen
			if ($daten = $anfrage->fetch_assoc()) {
				$bezeichnung = $daten['bezeichnung'];
				$art = $daten['personenart'];
			}
			$anfrage->free();
		}
	}

	$anfang = "<h3>Rollendetails</h3>";
	$anfang .= "<table class=\"cms_formular\">";
		$anfang .= "<tr><th>Personengruppe:</th>";
			$anfang .= "<td><select name=\"cms_schulhof_rolle_art\" id=\"cms_schulhof_rolle_art\">";
				if ($art == "s") {$anfang .= "<option value=\"s\" selected=\"selected\">Schüler</option>";} else {$anfang .= "<option value=\"s\">Schüler</option>";}
				if ($art == "l") {$anfang .= "<option value=\"l\" selected=\"selected\">Lehrer</option>";} else {$anfang .= "<option value=\"l\">Lehrer</option>";}
				if ($art == "e") {$anfang .= "<option value=\"e\" selected=\"selected\">Eltern</option>";} else {$anfang .= "<option value=\"e\">Eltern</option>";}
				if ($art == "v") {$anfang .= "<option value=\"v\" selected=\"selected\">Verwaltung</option>";} else {$anfang .= "<option value=\"v\">Verwaltung</option>";}
			$anfang .= "</select></td>";
		$anfang .= "</tr>";
		$anfang .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_schulhof_rolle_bezeichnung\" id=\"cms_schulhof_rolle_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
	$anfang .= "</table>";


	if ($rolle == '') {
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie FROM rechte) AS rechte ORDER BY kategorie ASC, bezeichnung ASC;";
	}
	else {
		$sql = "SELECT * FROM (SELECT id, rolle, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie FROM rechte LEFT JOIN (SELECT rolle, recht FROM rollenrechte WHERE rolle = $rolle) AS rollenrechte ON rechte.id = rollenrechte.recht) AS rechte ORDER BY kategorie ASC, bezeichnung ASC;";
	}
	if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID	- TODO: Check oben
		if ($rolle == '') {
			while ($daten = $anfrage->fetch_assoc()) {
				if ($altekategorie != $daten['kategorie']) {
					$altekategorie = $daten['kategorie'];
					$code .= "</p><h3>".$daten['kategorie']."</h3><p>";
				}
				$code .= "<span class=\"cms_toggle\" id=\"cms_toggle_schulhof_rolle_recht".$daten['id']."\" onclick=\"cms_toggle_klasse('cms_toggle_schulhof_rolle_recht".$daten['id']."', 'cms_toggle_aktiv', 'cms_schulhof_rolle_recht".$daten['id']."', 'true');\">".$daten['bezeichnung']."</span> ";
				$hiddencode .= "<input name=\"cms_schulhof_rolle_recht".$daten['id']."\" id=\"cms_schulhof_rolle_recht".$daten['id']."\" type=\"hidden\" value=\"0\">";
			}
		}
		else {
			while ($daten = $anfrage->fetch_assoc()) {
				if ($altekategorie != $daten['kategorie']) {
					$altekategorie = $daten['kategorie'];
					$code .= "</p><h3>".$daten['kategorie']."</h3><p>";
				}
				$anzeigeklasse = "cms_toggle";
				$wert = '0';
				if ($daten['rolle'] == $rolle) {
					$anzeigeklasse = "cms_toggle cms_toggle_aktiv";
					$wert = '1';
				}
				$code .= "<span class=\"$anzeigeklasse\" id=\"cms_toggle_schulhof_rolle_recht".$daten['id']."\" onclick=\"cms_toggle_klasse('cms_toggle_schulhof_rolle_recht".$daten['id']."', 'cms_toggle_aktiv', 'cms_schulhof_rolle_recht".$daten['id']."', 'true');\">".$daten['bezeichnung']."</span> ";
				$hiddencode .= "<input name=\"cms_schulhof_rolle_recht".$daten['id']."\" id=\"cms_schulhof_rolle_recht".$daten['id']."\" type=\"hidden\" value=\"$wert\">";
			}
		}
		$anfrage->free();
	}

	// Höchste ID ermitteln
	$hoechsteid = '-';
	$sql = "SELECT MAX(id) AS max FROM rechte";
	$sql = $dbs->prepare($sql);
	if ($sql->execute()) {
		$sql->bind_result($hoechsteid);
		$sql->fetch();
		$sql->close();
	}
	cms_trennen($dbs);
	$code.= "<input name=\"cms_schulhof_rolle_rechtmax\" id=\"cms_schulhof_rolle_rechtmax\" type=\"hidden\" value=\"$hoechsteid\">";

	return $anfang.(substr($code, 4)).$hiddencode."</p>";
}
?>
