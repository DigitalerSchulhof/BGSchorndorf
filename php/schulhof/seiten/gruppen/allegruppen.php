<?php
$dbs = cms_verbinden('s');


$code = "";
$code .= "<div class=\"cms_spalte_i\">";
	$code .= "<p class=\"cms_brotkrumen\">";
	$code .= cms_brotkrumen($CMS_URL);
	$code .= "</p>";

  $code .= "<h1>Gruppen</h1>";

	$gruppencode = array();
	foreach ($CMS_GRUPPEN AS $g) {
    $gruppen = cms_gruppen_links_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
    if (strlen($gruppen) > 0) {
			array_push($gruppencode, "<h2>$g</h2>".$gruppen);
		}
	}


	$anzahl = count($gruppencode);

	if ($anzahl > 0) {
		$code .= "</div>";
		$spalten = 4;
		$zeilen = ceil($anzahl/$spalten);

		$feldnr = 0;
		for ($i = 0; $i < $zeilen; $i ++) {
			for ($j=0; $j<$spalten; $j++) {
				$code .= "<div class=\"cms_spalte_$spalten\"><div class=\"cms_spalte_i\">";
				if (isset($gruppencode[$feldnr])) {$code .= $gruppencode[$feldnr];}
				$code .= "</div></div>";
				$feldnr++;
			}
			$code .= "<div class=\"cms_clear\"></div>";
		}
	}
	else {
		$code .= "<p>In diesem Zeitraum wurde keine sichtbare Gruppe gefunden und es bestehen auch keine Mitgliedschaften.</p>";
		$code .= "</div>";
	}

echo $code;
cms_trennen($dbs);
?>
