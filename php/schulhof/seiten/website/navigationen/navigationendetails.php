<?php
function cms_anzahl_navigationsebenen($dbs, $ausgang, $max = 0) {
	$gefunden = false;
	if ($ausgang == '-') {$sql = "SELECT id FROM seiten WHERE zuordnung IS NULL";}
	else {$sql = "SELECT id FROM seiten WHERE zuordnung = '$ausgang'";}
	$neuesmax = $max;
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$maxgebot = cms_anzahl_navigationsebenen($dbs, $daten['id'], $max+1);
			if ($maxgebot > $neuesmax) {$neuesmax = $maxgebot;}
		}
		$anfrage->free();
	}

	return $neuesmax;
}

function cms_navigation_ausgeben_bearbeiten ($dbs, $id, $ident) {
	$code = "";
	$hauptnavigation = false;
	$fehler = false;
	// Informationen über die Navigatio laden
	if (($ident == 'h') || ($ident == 's') || ($ident == 'f')) {
		$sql = "SELECT * FROM navigationen WHERE art = '$ident'";
		$hauptnavigation = true;
	}
	else {
		$sql = "SELECT * FROM navigationen WHERE id = $ident";
	}

	if ($anfrage = $dbs->query($sql)) {
		if (!($navigation = $anfrage->fetch_assoc())) {$fehler = true;}
		$anfrage->free();
	}
	else {$fehler = true;}

	// Information über die Navigation ausgeben
	if (!$fehler) {
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Ebene:<span class=\"cms_hinweis\">Wie soll die erste Ebene der Navigation ermittelt werden?</span></th><td>";
				$code .= "<select id=\"$id"."_ebene\" name=\"$id"."_ebene\" onchange=\"cms_navigation_ebenenwechsel('$id');\">";
				if ($navigation['ebene'] == 'd') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"d\"$selected>Ebene der geöffneten Seite und Unterseiten</option>";
				if ($navigation['ebene'] == 'u') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"u\"$selected>Ebene unter der geöffneten Seite</option>";
				if ($navigation['ebene'] == 's') {
					$selected = " selected=\"selected\"";
					$sstyle = " style=\"display: table-row;\"";
					$ebenenzusatzs = $navigation['ebenenzusatz'];
				}
				else {
					$selected = "";
					$sstyle = " style=\"display: none;\"";
					$ebenenzusatzs = '-';
				}
				$code .= "<option value=\"s\"$selected>Ebene unter der folgenden Seite ...</option>";
				if ($navigation['ebene'] == 'e') {
					$selected = " selected=\"selected\"";
					$estyle = " style=\"display: table-row;\"";
					$ebenenzusatze = $navigation['ebenenzusatz'];
				}
				else {
					$selected = "";
					$estyle = " style=\"display: none;\"";
					$ebenenzusatze = 0;
				}
				$code .= "<option value=\"e\"$selected>Dieser Pfad ab Ebene ...</option>";
				$code .= "</select>";
			$code .= "</td></tr>";

			$anzahlebenen = cms_anzahl_navigationsebenen($dbs, '-');
			$code .= "<tr id=\"$id"."_ebenenzusatz_eF\"$estyle>";
				$code .= "<th><span class=\"cms_hinweis_aussen\">Ebenennummer:<span class=\"cms_hinweis\">Ab welcher Ebene in diesem Pfad soll die Navigation angezeigt werden?</span></th><td>";
				$code .= "<select id=\"$id"."_ebenenzusatz_e\" name=\"$id"."_ebenenzusatz_e\">";
					for ($i = 0; $i < $anzahlebenen; $i++) {
						if ($ebenenzusatze == $i) {$selected = " selected=\"selected\"";} else {$selected = "";}
						$code .= "<option value=\"$i\"$selected>$i</option>";
					}
				$code .= "</select>";
				$code .= "</td>";
			$code .= "</tr>";

			$swahl = "<i>Keine gewählt</i>";
			if ($ebenenzusatzs != '-') {
				$sql = "SELECT * FROM seiten WHERE id = $ebenenzusatzs";
				if ($anfrage = $dbs->query($sql)) {
					if ($daten = $anfrage->fetch_assoc()) {
						$swahl = $daten['bezeichnung'];
					}
					$anfrage->free();
				}
			}

			$code .= "<tr id=\"$id"."_ebenenzusatz_sF\"$sstyle>";
				$code .= "<th><span class=\"cms_hinweis_aussen\">Unterseiten von:<span class=\"cms_hinweis\">Die Unterseiten der gewählten Seite sind die Ausgangsebene für diese Navigation.</span></th><td class=\"cms_seitenwahlzeile\">";
				$code .= "<span class=\"cms_wahl\" id=\"$id"."_ebenenzusatz_s_wahlF\">$swahl</span> ";
				$code .= cms_seitenwahl_generieren($dbs, $id."_ebenenzusatz_s");
				$code .= "<input type=\"hidden\" id=\"$id"."_ebenenzusatz_s\" name=\"$id"."_ebenenzusatz_s\" value=\"$ebenenzusatzs\">";
				$code .= "</td>";
			$code .= "</tr>";

			if (($navigation['ebene'] == 's') || ($navigation['ebene'] == 'e')) {
				$tstyle = " style=\"display: table-row;\"";
			}
			else {
				$tstyle = " style=\"display: none;\"";
			}

			$code .= "<tr id=\"$id"."_tiefeF\"$tstyle><th><span class=\"cms_hinweis_aussen\">Maximale Tiefe:<span class=\"cms_hinweis\">Wie viele Ebenen tiefer sollen maximal angezeigt werden?</span></th><td>";
				$code .= "<select id=\"$id"."_tiefe\" name=\"$id"."_tiefe\">";
				if ($navigation['tiefe'] == '0') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"0\"$selected>nur diese Ebene</option>";
				if ($navigation['tiefe'] == '1') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"1\"$selected>diese Ebene und eine weitere</option>";
				if ($navigation['tiefe'] == '2') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"2\"$selected>diese Ebene und zwei weitere</option>";
				if ($navigation['tiefe'] == '3') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"3\"$selected>diese Ebene und drei weitere</option>";
				if ($navigation['tiefe'] == '4') {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"4\"$selected>alle Ebenen (ggf. lange Ladezeit)</option>";
				$code .= "</select>";
			$code .= "</td></tr>";

			if (!$hauptnavigation) {
				$code .= "<tr><th>Anzeige:</th><td>";
					$code .= "<select id=\"$id"."_anzeige\" name=\"$id"."_anzeige\">";
					if ($navigation['anzeige'] == 'n') {$selected = " selected=\"selected\"";} else {$selected = "";}
					$code .= "<option value=\"n\"$selected>Buttons nebeneinander</option>";
					if ($navigation['tiefe'] == 'u') {$selected = " selected=\"selected\"";} else {$selected = "";}
					$code .= "<option value=\"u\"$selected>Buttons untereinander</option>";
					$code .= "</select>";
				$code .= "</td></tr>";

				$code .= "<tr class=\"$id"."_mehrF cms_versteckt\"><th>Styles:</th><td><input type=\"text\" name=\"$id"."_styles\" id=\"$id"."_styles\" value=\"".$navigation['styles']."\"></td></tr>";
				$code .= "<tr class=\"$id"."_mehrF cms_versteckt\"><th>Klassen:</th><td><input type=\"text\" name=\"$id"."_klassen\" id=\"$id"."_klassen\" value=\"".$navigation['klassen']."\"></td></tr>";
			}
		$code .= "</table>";

		if (!$hauptnavigation) {
			$code .= "<p><span id=\"$id"."_mehr\" class=\"cms_button\" onclick=\"cms_schulhof_mehr('$id')\">Mehr</span> <span id=\"$id"."_weniger\" class=\"cms_button\" style=\"display: none;\" onclick=\"cms_schulhof_weniger('$id')\">Weniger</span> </p>";
		}
	}
	else {
		$code .= cms_meldung_fehler();
	}

	return $code;
}
?>
