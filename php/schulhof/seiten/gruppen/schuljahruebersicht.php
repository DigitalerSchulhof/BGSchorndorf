<?php
$dbs = cms_verbinden('s');

$schuljahr = cms_linkzutext($CMS_URL[2]);
$schuljahrid = "-";

$fehler = true;
// Prüfen, ob diese Gruppe existiert
if ($schuljahr != "Schuljahrübergreifend") {
	$sql = $dbs->prepare("SELECT id, COUNT(*) as anzahl FROM schuljahre WHERE bezeichnung = AES_ENCRYPT('$schuljahr', '$CMS_SCHLUESSEL')");
	$sql->bind_param("si", $schuljahrid, $anzahl);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {if ($anzahl == 1) {$fehler = false;}}
	}
	$sql->close();
}
else {$fehler = false;}


if (!$fehler) {
	$code = "";
	$code .= "<div class=\"cms_spalte_i\">";
		$code .= "<p class=\"cms_brotkrumen\">";
		$code .= cms_brotkrumen($CMS_URL);
		$code .= "</p>";
    if ($schuljahrid == '-') {
	    $code .= "<h1>Gruppen schuljahrübergreifend</h1>";
    }
    else {
      $code .= "<h1>Gruppen im Schuljahr $schuljahr</h1>";
    }

		$gruppencode = array();
		foreach ($CMS_GRUPPEN AS $g) {
	    $gruppen = cms_gruppen_links_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $schuljahrid, true);
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


}
else {
	cms_fehler('Schulhof', '404');
}

echo $code;
cms_trennen($dbs);
?>
