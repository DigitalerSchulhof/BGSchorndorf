<?php
function cms_listen_links_anzeigen($einschraenkung = false) {
	global $CMS_RECHTE, $CMS_GRUPPEN;
	$liste = "";

	if ($CMS_RECHTE['Personen']['Lehrerliste sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Lehrer\">Lehrer</a></li> ";
	}
	if ($CMS_RECHTE['Personen']['Schülerliste sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Schüler\">Schüler</a></li> ";
	}
	if ($CMS_RECHTE['Personen']['Elternliste sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Eltern\">Eltern</a></li> ";
	}
	if ($CMS_RECHTE['Personen']['Verwaltungsliste sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Verwaltung\">Verwaltungsangestellte</a></li> ";
	}
	if ($CMS_RECHTE['Personen']['Externenliste sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Externe\">Externe</a></li> ";
	}

	if ($CMS_RECHTE['Personen']['Elternvertreter sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Elternvertreter\">Elternvertreter</a></li> ";
	}
	if ($CMS_RECHTE['Personen']['Schülervertreter sehen']) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Schülervertreter\">Schülervertreter</a></li> ";
	}

	if ($einschraenkung != 'personen') {
		foreach ($CMS_GRUPPEN as $g) {
			if ($CMS_RECHTE['Gruppen'][$g.' Listen sehen'] || $CMS_RECHTE['Gruppen'][$g.' Listen sehen wenn Mitglied']) {
				$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Gruppen/".cms_textzulink($g)."\">$g</a></li> ";
			}
		}
	}

	if (strlen($liste) > 0) {
		return "<ul>".$liste."</ul>";
	}
	else {
		return '<p>Keine Listen verfügbar</p>';
	}
}
?>
