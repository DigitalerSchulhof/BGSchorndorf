<?php
function cms_website_seiten_ausgeben ($id, $zuordnung) {
	$code = "";
	$dbs = cms_verbinden('s');

	$bezeichnung = "";
	$beschreibung = "";
	$sidebar = 1;
	$status = 'i';
	$position = 0;
	$maxpos = 0;
	$art = "s";
	$styles = "";
	$klassen = "";

	if (cms_r("website.freigeben")) {$status = "a";}

	if ($id != "-") {
		$sql = $dbs->prepare("SELECT bezeichnung, beschreibung, sidebar, status, position, art, styles, klassen FROM seiten WHERE id = ?");
		$sql->bind_param("i", $id);
		$sql->bind_result($bezeichnung, $beschreibung, $sidebar, $status, $position, $art, $styles, $klassen);
	  if ($sql->execute()) {
	    $sql->fetch();
	  }
		$sql->close();
	}

	if (cms_check_ganzzahl($zuordnung)) {$sqlzuordnung = "zuordnung = $zuordnung";}
	else if (is_null($zuordnung) || ($zuordnung == '-')) {$sqlzuordnung = "zuordnung IS NULL";}
	else {$zuordnung = "";}

	$sql = "SELECT MAX(position) AS max FROM seiten WHERE $sqlzuordnung";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen
		if ($daten = $anfrage->fetch_assoc()) {
			$maxpos = $daten['max'];
		}
		$anfrage->free();
	}

	if ($id == '-') {
		$maxpos = $maxpos + 1;
		$position = $maxpos;
	}

	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_website_seiten_bezeichnung\" id=\"cms_website_seiten_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Beschreibung:<span class=\"cms_hinweis\">Wenn eine Seite ein Menüpunkt ist, werden ihre Unterseiten mit Beschreibung ausgegeben.</span></span></th><td><textarea name=\"cms_website_seiten_beschreibung\" id=\"cms_website_seiten_beschreibung\">$beschreibung</textarea></td></tr>";

		$code .= "<tr><th>Art:</th><td><select name=\"cms_website_seiten_art\" id=\"cms_website_seiten_art\">";
		$selected = "";
		if ($art == 's') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"s\"$selected>Normale Seite</option>";
		$selected = "";
		if ($art == 'm') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"m\"$selected>Menüpunkt ohne Inhalt</option>";
		$selected = "";
		if ($art == 'b') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"b\"$selected>Weiterleitung zum Blog</option>";
		$selected = "";
		if ($art == 'g') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"g\"$selected>Weiterleitung zur Galerie</option>";
		$selected = "";
		if ($art == 't') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"t\"$selected>Weiterleitung zu den Terminen</option>";
		$code .= "</select></td></tr>";


		$code .= "<tr><th>Position:</th><td><select name=\"cms_website_seiten_position\" id=\"cms_website_seiten_position\">";
		for ($i = 1; $i <= $maxpos; $i++) {
			$selected = "";
			if ($position == $i) {$selected = " selected=\"selected\"";}
			$code .= "<option value=\"$i\"$selected>$i</option>";
		}
		$code .= "</select></td></tr>";

		$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Sidebarnavigation:<span class=\"cms_hinweis\">Soll an der linken Seite eine Seitennavigation angezeigt werden?</span></span></th><td>".cms_schieber_generieren('website_seiten_sidebar', $sidebar)."</td></tr>";
		$disabled = "disabled=\"disabled\"";
		$startseiteeditieren = ($status != 's') || cms_r("website.seiten.startseite");
		if ($startseiteeditieren && cms_r("website.freigeben")) {$disabled = "";}
		$code .= "<tr><th>Status:</th><td><select name=\"cms_website_seiten_status\" id=\"cms_website_seiten_status\"$disabled>";
		$selected = "";
		if ($status == 'a') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"a\"$selected>Aktiv</option>";
		$selected = "";
		if ($status == 'i') {$selected = " selected=\"selected\"";}
		$code .= "<option value=\"i\"$selected>Inaktiv</option>";
		if ((!$startseiteeditieren) || cms_r("website.seiten.startseite")) {
			$selected = "";
			if ($status == 's') {$selected = " selected=\"selected\"";}
			$code .= "<option value=\"s\"$selected>Startseite</option>";
		}
		$code .= "</select>";
		if (!cms_r("website.freigeben")) {
			$code .= "<p>";
				$code .= cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Gemachte Änderungen werden nicht sofort angezeigt. Die Änderungen werden gesichtet und anschließend freigegeben oder verworfen.</p>');
			$code .= "</p>";
		}
		$code .= "</td></tr>";
		$code .= "<tr class=\"cms_website_seiten_fortgeschritten_mehrF\"><th>Styles:</th><td><input type=\"text\" name=\"cms_website_seiten_styles\" id=\"cms_website_seiten_styles\" value=\"".$styles."\"></td></tr>";
		$code .= "<tr class=\"cms_website_seiten_fortgeschritten_mehrF\"><th>Klassen:</th><td><input type=\"text\" name=\"cms_website_seiten_klassen\" id=\"cms_website_seiten_klassen\" value=\"".$klassen."\"></td></tr>";
	$code .= "</table>";

	$code .= "<p><span id=\"cms_website_seiten_fortgeschritten_mehr\" class=\"cms_button\" onclick=\"cms_schulhof_mehr('cms_website_seiten_fortgeschritten')\">Mehr</span> <span id=\"cms_website_seiten_fortgeschritten_weniger\" class=\"cms_button\" style=\"display: none;\" onclick=\"cms_schulhof_weniger('cms_website_seiten_fortgeschritten')\">Weniger</span> </p>";

	if (is_null($zuordnung)) {$zuordnung = '-';}
	$code .= "<p><input type=\"hidden\" id=\"cms_website_seiten_zuordnung\" name=\"cms_website_seiten_zuordnung\" value=\"$zuordnung\">";
	if ($id == '-') {
		$code .= "<span class=\"cms_button\" onclick=\"cms_schulhof_website_seite_neu_speichern();\">Speichern</span> ";
	}
	else {
		$code .= "<span class=\"cms_button\" onclick=\"cms_schulhof_website_seite_bearbeiten();\">Änderungen speichern</span> ";
	}
	$code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Website/Seiten\">Abbrechen</a></p>";

	cms_trennen($dbs);
	return $code;
}
?>
