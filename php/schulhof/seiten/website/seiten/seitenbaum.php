<?php
function cms_seitenbaum_ausgeben($dbs, $oberseite, $tiefe, $bearbeiten = false) {
	global $CMS_RECHTE;
	$code = "";
	$dbs = cms_verbinden('s');

	$zuordnung = "";
	if (cms_check_ganzzahl($oberseite,0)) {$zuordnung = "zuordnung = '$oberseite'";}
	else if ($oberseite == '-') {$zuordnung = "zuordnung IS NULL";}

	$sql = "SELECT * FROM seiten WHERE $zuordnung ORDER BY position";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$code .= "<tr>";

			if ($bearbeiten) {
				if ($daten['art'] == 's') {$art = "Seite"; $articon = "seite";}
				else if ($daten['art'] == 'b') {$art = "Blog"; $articon = "blog";}
				else if ($daten['art'] == 'g') {$art = "Galerie"; $articon = "galerie";}
				else if ($daten['art'] == 't') {$art = "Termine"; $articon = "termine";}
				else {$art = "Menüpunkt"; $articon = "menuepunkt";}
				$code .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/$articon".".png\"><span class=\"cms_hinweis\">$art</span></span></td>";
			}
			$paddingleft = 7+16*($tiefe-2);
			if ($paddingleft < 0) {$paddingleft = 7;}
			$icon = "";
			if ($tiefe > 1) {$icon = '&#10149;';}
			$code .= "<td style=\"padding-left: $paddingleft"."px\">$icon ".$daten['bezeichnung']."</td> ";



			if ($bearbeiten) {
				if ($daten['status'] == 's') {$status = "Startseite";}
				else if ($daten['status'] == 'a') {$status = "aktiv";}
				else {$status = "inaktiv";}
				$code .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/".strtolower($status).".png\"><span class=\"cms_hinweis\">$status</span></span></td>";

				// Aktionen
				$code .= "<td>";
				if ($CMS_RECHTE['Website']['Seiten bearbeiten']) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_website_seite_bearbeiten_vorbereiten('".$daten['id']."');\"><span class=\"cms_hinweis\">Seite bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				}
				if ($CMS_RECHTE['Website']['Startseite festlegen'] && ($daten['status'] != 's') && ($daten['art'] == 's')) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_website_seite_startseite_anzeigen('".$daten['bezeichnung']."', '".$daten['id']."');\"><span class=\"cms_hinweis\">Seite zur Startseite machen</span><img src=\"res/icons/klein/startseite.png\"></span> ";
				}
				if ($CMS_RECHTE['Website']['Seiten anlegen']) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_schulhof_website_seite_neu_vorbereiten('".$daten['id']."');\"><span class=\"cms_hinweis\">Neue Unterseite anlegen</span><img src=\"res/icons/klein/hinzufuegen.png\"></span> ";
				}
				if ($CMS_RECHTE['Website']['Seiten löschen']) {
					$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_website_seite_loeschen_anzeigen('".$daten['bezeichnung']."', '".$daten['id']."');\"><span class=\"cms_hinweis\">Seite löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}
				$code .= "</td>";
			}
			$code .= cms_seitenbaum_ausgeben($dbs, $daten['id'], $tiefe + 1, $bearbeiten);
			$code .= "</tr>";
		}
		$anfrage->free();
	}

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
