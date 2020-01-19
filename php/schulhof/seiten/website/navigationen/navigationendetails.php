<?php
function cms_anzahl_navigationsebenen($dbs) {
	$gefunden = false;
	$max = 0;
	$DIESEEBENE = array();
	$NAECHSTEEBENE = array();

	$sql = $dbs->prepare("SELECT id FROM seiten WHERE zuordnung IS NULL");
	if ($sql->execute()) {
		$sql->bind_result($zid);
		while ($sql->fetch()) {
			array_push($NAECHSTEEBENE, $zid);
		}
	}
	$sql->close();

	$sql = $dbs->prepare("SELECT id FROM seiten WHERE zuordnung = ?");
	while ((count($DIESEEBENE) != 0) || (count($NAECHSTEEBENE) != 0))  {
		while ((count($DIESEEBENE) != 0)) {
			$eid = array_pop($DIESEEBENE);
			$sql->bind_param("i", $eid);
			if ($sql->execute()) {
				$sql->bind_result($zid);
				while ($sql->fetch()) {
					array_push($NAECHSTEEBENE, $zid);
				}
			}
		}
		$DIESEEBENE = $NAECHSTEEBENE;
		$NAECHSTEEBENE = array();
		$max++;
	}
	$sql->close();

	return $max-1;
}

function cms_navigation_ausgeben_bearbeiten ($dbs, $id, $ident) {
	$code = "";
	$hauptnavigation = false;
	$fehler = false;
	// Informationen über die Navigatio laden
	if (($ident == 'h') || ($ident == 's') || ($ident == 'f')) {
		$sql = $dbs->prepare("SELECT * FROM navigationen WHERE art = ?");
		$sql->bind_param("s", $ident);
		$hauptnavigation = true;
	}
	else {
		$sql = $dbs->prepare("SELECT * FROM navigationen WHERE id = ?");
		$sql->bind_param("i", $ident);
	}

	$navigation = array();
	if ($sql->execute()) {
		$sql->bind_result($navigation['id'], $navigation['art'], $navigation['ebene'], $navigation['ebenenzusatz'], $navigation['tiefe'], $navigation['spalte'], $navigation['position'], $navigation['anzeige'], $navigation['styles'], $navigation['klassen'], $navigation['idvon'], $navigation['idzeit']);
		if (!$sql->fetch()) {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();

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

			$anzahlebenen = cms_anzahl_navigationsebenen($dbs);
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
				$sql = $dbs->prepare("SELECT bezeichnung FROM seiten WHERE id = ?");
				$sql->bind_param("i", $ebenenzusatzs);
				if ($sql->execute()) {
					$sql->bind_result($swahl);
					$sql->fetch();
				}
				$sql->close();
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
