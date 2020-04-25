<?php
function cms_personensuche ($id, $hinweis, $vorhanden, $schueler, $lehrer, $eltern, $verwaltung, $listen, $selbst, $hinzufuegeart = '', $nutzerkonto = 0) {

	$code = "";

	$code .= "<input type=\"hidden\" id=\"cms_$id\" name=\"cms_$id\" value=\"$vorhanden\">";
	$code .= "<span class=\"cms_button_ja\" onclick=\"cms_einblenden('cms_".$id."_suchfeld')\"><span class=\"cms_hinweis\">$hinweis</span>+</span>";

	$code .= "<div class=\"cms_personensuche_feld\" id=\"cms_".$id."_suchfeld\">";
		$code .= "<span class=\"cms_fenster_schliessen cms_button_nein\" onclick=\"cms_ausblenden('cms_".$id."_suchfeld')\"><span class=\"cms_hinweis\">Fenster schließen</span>&times;</span>";

		$code .= "<div class=\"cms_spalte_2\">";
		$code .= "<div class=\"cms_spalte_i\">";
			$code .= "<p>Nachname:</p>";
			$code .= "<p><input type=\"text\" id=\"cms_".$id."_nnamesuche\" name=\"cms_".$id."_nnamesuche\" onkeyup=\"cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"></p>";
			$code .= "<p>";
		$code .= "</div>";
		$code .= "</div>";
		$code .= "<div class=\"cms_spalte_2\">";
		$code .= "<div class=\"cms_spalte_i\">";
			$code .= "<p>Vorname:</p>";
			$code .= "<p><input type=\"text\" id=\"cms_".$id."_vnamesuche\" name=\"cms_".$id."_vnamesuche\" onkeyup=\"cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"></p>";
			$code .= "<p>";
		$code .= "</div>";
		$code .= "</div>";

		$code .= "<div class=\"cms_clear\"></div>";

		$code .= "<div class=\"cms_spalte_i\">";
			$code .= "<p>";
			if ($schueler) {
				$code .= "<span class=\"cms_toggle\" id=\"cms_toggle_".$id."_schueler\" onclick=\"cms_toggle_klasse('cms_toggle_".$id."_schueler', 'cms_toggle_aktiv', 'cms_".$id."_schueler', 'true');cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"> Schüler</span> ";
				$code .= "<input name=\"cms_".$id."_schueler\" id=\"cms_".$id."_schueler\" type=\"hidden\" value=\"0\">";
				$code .= "<input name=\"cms_".$id."_zulaessig_s\" id=\"cms_".$id."_zulaessig_s\" type=\"hidden\" value=\"1\">";
			}
			else {
				$code .= "<input name=\"cms_".$id."_zulaessig_s\" id=\"cms_".$id."_zulaessig_s\" type=\"hidden\" value=\"0\">";
			}
			if ($lehrer) {
				$code .= "<span class=\"cms_toggle\" id=\"cms_toggle_".$id."_lehrer\" onclick=\"cms_toggle_klasse('cms_toggle_".$id."_lehrer', 'cms_toggle_aktiv', 'cms_".$id."_lehrer', 'true');cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"> Lehrer</span> ";
				$code .= "<input name=\"cms_".$id."_lehrer\" id=\"cms_".$id."_lehrer\" type=\"hidden\" value=\"0\">";
				$code .= "<input name=\"cms_".$id."_zulaessig_l\" id=\"cms_".$id."_zulaessig_l\" type=\"hidden\" value=\"1\">";
			}
			else {
				$code .= "<input name=\"cms_".$id."_zulaessig_l\" id=\"cms_".$id."_zulaessig_l\" type=\"hidden\" value=\"0\">";
			}
			if ($eltern) {
				$code .= "<span class=\"cms_toggle\" id=\"cms_toggle_".$id."_eltern\" onclick=\"cms_toggle_klasse('cms_toggle_".$id."_eltern', 'cms_toggle_aktiv', 'cms_".$id."_eltern', 'true');cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"> Eltern</span> ";
				$code .= "<input name=\"cms_".$id."_eltern\" id=\"cms_".$id."_eltern\" type=\"hidden\" value=\"0\">";
				$code .= "<input name=\"cms_".$id."_zulaessig_e\" id=\"cms_".$id."_zulaessig_e\" type=\"hidden\" value=\"1\">";
			}
			else {
				$code .= "<input name=\"cms_".$id."_zulaessig_e\" id=\"cms_".$id."_zulaessig_e\" type=\"hidden\" value=\"0\">";
			}
			if ($verwaltung) {
				$code .= "<span class=\"cms_toggle\" id=\"cms_toggle_".$id."_verwaltung\" onclick=\"cms_toggle_klasse('cms_toggle_".$id."_verwaltung', 'cms_toggle_aktiv', 'cms_".$id."_verwaltung', 'true');cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"> Verwaltung</span> ";
				$code .= "<input name=\"cms_".$id."_verwaltung\" id=\"cms_".$id."_verwaltung\" type=\"hidden\" value=\"0\">";
				$code .= "<input name=\"cms_".$id."_zulaessig_v\" id=\"cms_".$id."_zulaessig_v\" type=\"hidden\" value=\"1\">";
			}
			else {
				$code .= "<input name=\"cms_".$id."_zulaessig_v\" id=\"cms_".$id."_zulaessig_v\" type=\"hidden\" value=\"0\">";
			}
			if ($listen) {
				$code .= "<span class=\"cms_toggle\" id=\"cms_toggle_".$id."_listen\" onclick=\"cms_toggle('".$id."_listen');cms_personensuche('$id', '$selbst', '$hinzufuegeart', '$nutzerkonto');\"> Listen</span> ";
				$code .= "<input name=\"cms_".$id."_listen\" id=\"cms_".$id."_listen\" type=\"hidden\" value=\"0\">";
				$code .= "<input name=\"cms_".$id."_zulaessig_li\" id=\"cms_".$id."_zulaessig_li\" type=\"hidden\" value=\"1\">";
			}
			else {
				$code .= "<input name=\"cms_".$id."_zulaessig_li\" id=\"cms_".$id."_zulaessig_li\" type=\"hidden\" value=\"0\">";
			}
			$code .= "</p>";
			$code .= "<p id=\"cms_".$id."_ergebnisse\"></p>";
		$code .= "</div>";
	$code .= "</div>";

	return $code;
}


function cms_personensuche_personerzeugen ($feldid, $selbst, $id, $art, $vorname, $nachname, $titel, $weg) {
	$ausgabe = "";
	$icon = "";
	if ($art == 'l') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Lehrer</span><img src="res/icons/klein/lehrer.png"></span>';}
	else if ($art == 's') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Schüler</span><img src="res/icons/klein/schueler.png"></span>';}
	else if ($art == 'e') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Eltern</span><img src="res/icons/klein/elter.png"></span>';}
	else if ($art == 'v') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Verwaltung</span><img src="res/icons/klein/verwaltung.png"></span>';}

	$anzeigename = $vorname." ".$nachname;
	if (strlen($titel) > 0) {
		$anzeigename = $titel." ".$anzeigename;
	}

	$ausgabe .= "<span id=\"cms_".$feldid."_person".$id."\" class=\"cms_personenauswahl\">$icon $anzeigename";
	if ($weg) {
		$ausgabe .= "<span class=\"cms_personenauswahl_schliessen cms_button_nein\" onclick=\"cms_personweg('".$feldid."', '".$id."', '".$selbst."', '')\"><span class=\"cms_hinweis\">Person entfernen</span>&times;</span>";
	}
	$ausgabe .= "</span> ";

	return $ausgabe;
}


function cms_personensuche_personrechteerzeugen ($feldid, $selbst, $id, $art, $vorname, $nachname, $titel, $rechte, $weg) {
	$ausgabe = "";
	$icon = "";
	$ausgabe .= "<tr id=\"cms_".$feldid."_person".$id."\">";
	if ($art == 'l') {$ausgabe .= '<td><span class="cms_icon_klein_o"><span class="cms_hinweis">Lehrer</span><img src="res/icons/klein/lehrer.png"></span></td>';}
	else if ($art == 's') {$ausgabe .= '<td><span class="cms_icon_klein_o"><span class="cms_hinweis">Schüler</span><img src="res/icons/klein/schueler.png"></span></td>';}
	else if ($art == 'e') {$ausgabe .= '<td><span class="cms_icon_klein_o"><span class="cms_hinweis">Eltern</span><img src="res/icons/klein/elter.png"></span></td>';}
	else if ($art == 'v') {$ausgabe .= '<td><span class="cms_icon_klein_o"><span class="cms_hinweis">Verwaltung</span><img src="res/icons/klein/verwaltung.png"></span></td>';}

	$anzeigename = $vorname." ".$nachname;
	if (strlen($titel) > 0) {
		$anzeigename = $titel." ".$anzeigename;
	}

	$ausgabe .= "<td>$anzeigename</td>";
	$vorsilbe = "in";
	if ($rechte['vorsitz'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_vorsitz".$id."\" onclick=\"cms_schieber('".$feldid."_vorsitz".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_vorsitz".$id."\" id=\"cms_".$feldid."_vorsitz".$id."\" value=\"".$rechte['vorsitz']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['mv'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_mv".$id."\" onclick=\"cms_schieber('".$feldid."_mv".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_mv".$id."\" id=\"cms_".$feldid."_mv".$id."\" value=\"".$rechte['mv']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['sch'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_schreiben".$id."\" onclick=\"cms_schieber('".$feldid."_schreiben".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_schreiben".$id."\" id=\"cms_".$feldid."_schreiben".$id."\" value=\"".$rechte['sch']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['dho'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_dho".$id."\" onclick=\"cms_schieber('".$feldid."_dho".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_dho".$id."\" id=\"cms_".$feldid."_dho".$id."\" value=\"".$rechte['dho']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['dru'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_dru".$id."\" onclick=\"cms_schieber('".$feldid."_dru".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_dru".$id."\" id=\"cms_".$feldid."_dru".$id."\" value=\"".$rechte['dru']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['dum'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_dum".$id."\" onclick=\"cms_schieber('".$feldid."_dum".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_dum".$id."\" id=\"cms_".$feldid."_dum".$id."\" value=\"".$rechte['dum']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['dlo'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_dlo".$id."\" onclick=\"cms_schieber('".$feldid."_dlo".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_dlo".$id."\" id=\"cms_".$feldid."_dlo".$id."\" value=\"".$rechte['dlo']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['oan'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_oan".$id."\" onclick=\"cms_schieber('".$feldid."_oan".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_oan".$id."\" id=\"cms_".$feldid."_oan".$id."\" value=\"".$rechte['oan']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['oum'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_oum".$id."\" onclick=\"cms_schieber('".$feldid."_oum".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_oum".$id."\" id=\"cms_".$feldid."_oum".$id."\" value=\"".$rechte['oum']."\"></td>";
	$vorsilbe = "in";
	if ($rechte['olo'] == 1) {$vorsilbe = "";}
	$ausgabe .= "<td><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_".$feldid."_olo".$id."\" onclick=\"cms_schieber('".$feldid."_olo".$id."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_".$feldid."_olo".$id."\" id=\"cms_".$feldid."_olo".$id."\" value=\"".$rechte['olo']."\"></td>";

	if ($weg) {
		$ausgabe .= "<td><span class=\"cms_button_nein\" onclick=\"cms_personweg('".$feldid."', '".$id."', '".$selbst."', 'rechtezeile');\"><span class=\"cms_hinweis\">Mitgliedschaft beenden</span>&times;</span></td>";
	}
	$ausgabe .= "</tr>";

	return $ausgabe;
}
?>
