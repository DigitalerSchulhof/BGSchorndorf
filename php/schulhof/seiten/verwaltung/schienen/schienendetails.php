<?php
function cms_schiene_ausgeben ($id, $SCHULJAHR = false) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$zeitraum = "";
	$kurse = array();
	$kurseids = "";

	if ($id != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(schienen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, zeitraum, schuljahr FROM schienen JOIN zeitraeume ON schienen.zeitraum = zeitraeume.id WHERE schienen.id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($bezeichnung, $zeitraum, $SCHULJAHR);
			$sql->fetch();
		}
		$sql->close();

		// Kurse
		$sql = $dbs->prepare("SELECT id FROM kurse JOIN schienenkurse ON schienenkurse.kurs = kurse.id WHERE schienenkurse.schiene = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($kid);
			while ($sql->fetch()) {
				array_push($kurse, $kid);
				$kurseids .= "|".$kid;
			}
		}
		$sql->close();
	}

	$STUFENOPTIONEN = "<option value=\"-\">stufenübergreifend</option>";
	$FAECHEROPTIONEN = "<option value=\"-\">fächerübergreifend</option>";
	$ZEITRAEUME = "";
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge");
	$sql->bind_param("i", $SCHULJAHR);
	if ($sql->execute()) {
		$sql->bind_result($sid, $bez);
		while ($sql->fetch()) {
			$STUFENOPTIONEN .= "<option value=\"$sid\">$bez</option>";
		}
	}
	$sql->close();
	$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM faecher WHERE schuljahr = ?) AS x ORDER BY bez");
	$sql->bind_param("i", $SCHULJAHR);
	if ($sql->execute()) {
		$sql->bind_result($fid, $bez);
		while ($sql->fetch()) {
			$FAECHEROPTIONEN .= "<option value=\"$fid\">$bez</option>";
		}
	}
	$sql->close();
	$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM zeitraeume WHERE schuljahr = ?) AS x ORDER BY bez");
	$sql->bind_param("i", $SCHULJAHR);
	if ($sql->execute()) {
		$sql->bind_result($zid, $bez);
		while ($sql->fetch()) {
			$ZEITRAEUME .= "<option value=\"$zid\">$bez</option>";
		}
	}
	$sql->close();

	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_schiene_bezeichnung\" id=\"cms_schiene_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
			if ($id != '-') {
				$code .= "<tr><th>Zeitraum:</th><td colspan=\"2\"><select name=\"cms_schiene_zeitraum\" id=\"cms_schiene_zeitraum\" disabled=\"disabled\">";
			}
			else {
				$code .= "<tr><th>Zeitraum:</th><td colspan=\"2\"><select name=\"cms_schiene_zeitraum\" id=\"cms_schiene_zeitraum\">";
			}
			$code .= str_replace("<option value=\"$zeitraum\"", "<option value=\"$zeitraum\" selected=\"selected\"", $ZEITRAEUME)."</select></td></tr>";
		$code .= "<tr><th>Kurse:</th><td><select name=\"cms_schiene_filter_stufe\" id=\"cms_schiene_filter_stufe\" onchange=\"cms_schienen_kurse_laden()\">$STUFENOPTIONEN</select></td><td><select name=\"cms_schiene_filter_faecher\" id=\"cms_schiene_filter_faecher\" onchange=\"cms_schienen_kurse_laden()\">$FAECHEROPTIONEN</select></td></tr>";
		$code .= "<tr><th></th><td colspan=\"2\" id=\"cms_schiene_kurse_feld\">";
		$sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($kid, $kbez, $kreihe);
			while ($sql->fetch()) {
				if (in_array($kid, $kurse)) {$code .= cms_togglebutton_generieren ("cms_schiene_kurs_".$kid, $kbez, 1, 'cms_schienen_fach_auswaehlen(\''.$kid.'\')')." ";}
				else {$code .= cms_togglebutton_generieren ("cms_schiene_kurs_".$kid, $kbez, 0, 'cms_schienen_fach_auswaehlen(\''.$kid.'\')')." ";}
			}
		}
		$sql->close();
		$code .= "<input type=\"hidden\" name=\"cms_schiene_kursegewaehltids\" id=\"cms_schiene_kursegewaehltids\" value=\"$kurseids\">";
		$code .= "</td></tr>";
	$code .= "</table>";

	cms_trennen($dbs);
	return $code;
}
?>
