<?php
function cms_seitenbaum_ausgeben($dbs, $oberseite, $tiefe, $bearbeiten = false) {
	$code = "";
	$dbs = cms_verbinden('s');

	$zuordnung = "";
	if (cms_check_ganzzahl($oberseite,0)) {
		$sql = $dbs->prepare("SELECT * FROM seiten WHERE zuordnung = ? ORDER BY position");
		$sql->bind_param("i", $oberseite);
	}
	else if ($oberseite == '-') {
		$sql = $dbs->prepare("SELECT * FROM seiten WHERE zuordnung IS NULL ORDER BY position");
	}

	if ($sql->execute()) {
		$sql->bind_result($sid, $sart, $spos, $szuord, $sbez, $sbes, $sside, $sstat, $sstyle, $sklasse, $sidvon, $sidzeit);
		while ($sql->fetch()) {
			$code .= "<tr>";

			if ($bearbeiten) {
				if ($sart == 's') {$art = "Seite"; $articon = "seite";}
				else if ($sart == 'b') {$art = "Blog"; $articon = "blog";}
				else if ($sart == 'g') {$art = "Galerie"; $articon = "galerie";}
				else if ($sart == 't') {$art = "Termine"; $articon = "termine";}
				else {$art = "Menüpunkt"; $articon = "menuepunkt";}
				$code .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/$articon".".png\"><span class=\"cms_hinweis\">$art</span></span></td>";
			}
			$paddingleft = 7+16*($tiefe-2);
			if ($paddingleft < 0) {$paddingleft = 7;}
			$icon = "";
			if ($tiefe > 1) {$icon = '&#10149;';}
			$code .= "<td style=\"padding-left: $paddingleft"."px\">$icon $sbez</td> ";

			if ($bearbeiten) {
				if ($sstat == 's') {$status = "Startseite";}
				else if ($sstat == 'a') {$status = "aktiv";}
				else {$status = "inaktiv";}
				$code .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/".strtolower($status).".png\"><span class=\"cms_hinweis\">$status</span></span></td>";

				// Aktionen
				$code .= "<td>";
				if (cms_r("website.seiten.bearbeiten")) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_website_seite_bearbeiten_vorbereiten('$sid');\"><span class=\"cms_hinweis\">Seite bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				}
				if ((($sstat != 's') && ($sart == 's')) && cms_r("website.seiten.startseite")) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_website_seite_startseite_anzeigen($sbez', '$sid');\"><span class=\"cms_hinweis\">Seite zur Startseite machen</span><img src=\"res/icons/klein/startseite.png\"></span> ";
				}
				if (cms_r("website.seiten.anlegen")) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_schulhof_website_seite_neu_vorbereiten('$sid');\"><span class=\"cms_hinweis\">Neue Unterseite anlegen</span><img src=\"res/icons/klein/hinzufuegen.png\"></span> ";
				}
				if (cms_r("website.seiten.löschen")) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_website_seite_loeschen_anzeigen('$sbez', '$sid');\"><span class=\"cms_hinweis\">Seite löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}
				$code .= "</td>";
			}
			$code .= cms_seitenbaum_ausgeben($dbs, $sid, $tiefe + 1, $bearbeiten);
			$code .= "</tr>";
		}
	}
	$sql->close();

	cms_trennen($dbs);

	if (strlen($code) == 0) {
		if ($tiefe > 1) {
			$code .= "<tr class=\"cms_sitemap_versteckt cms_sitemap_$oberseite\">";
		}
		else {
			$code .= "<tr>";
		}
		$code .= "<td colspan=\"3\" class=\"cms_notiz\">-- keine Seiten --</td></tr>";
	}

	return $code;
}
?>
