<?php
function cms_listen_links_anzeigen($einschraenkung = false) {
	global $CMS_GRUPPEN;
	$liste = "";

	if (cms_r("schulhof.information.listen.lehrer")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Lehrer\">Lehrer</a></li> ";
	}
	if (cms_r("schulhof.information.listen.schüler")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Schüler\">Schüler</a></li> ";
	}
	if (cms_r("schulhof.information.listen.eltern")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Eltern\">Eltern</a></li> ";
	}
	if (cms_r("schulhof.information.listen.verwaltungsangestellte")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Verwaltung\">Verwaltungsangestellte</a></li> ";
	}
	if (cms_r("schulhof.information.listen.externe")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Externe\">Externe</a></li> ";
	}

	if (cms_r("schulhof.information.listen.elternvertreter")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Elternvertreter\">Elternvertreter</a></li> ";
	}
	if (cms_r("schulhof.information.listen.schülervertreter")) {
		$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Personen/Schülervertreter\">Schülervertreter</a></li> ";
	}

	if ($einschraenkung != 'personen') {
		foreach ($CMS_GRUPPEN as $g) {
			if(cms_r("schulhof.information.listen.gruppen.$g.[|sehen,sehenwenn]")) {
				$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Gruppen/".cms_textzulink($g)."\">$g</a></li> ";
			}
		}
	}

	if (strlen($liste) > 0) {
		return "<ul>".$liste."</ul>";
	}
	else {
		return '<p class="cms_notiz">Keine Listen verfügbar</p>';
	}
}
?>
