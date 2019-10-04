<?php
$dbs = cms_verbinden('s');

$schuljahr = cms_linkzutext($CMS_URL[2]);
$g = cms_linkzutext($CMS_URL[3]);
$gk = cms_textzudb($g);
$schuljahrid = "-";

$fehler = true;
// Prüfen, ob diese Gruppe existiert
if (in_array($g, $CMS_GRUPPEN)) {
	if ($schuljahr != "Schuljahrübergreifend") {
		$sql = $dbs->prepare("SELECT id, COUNT(*) as anzahl FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$sql->bind_param("s", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($schuljahrid, $anzahl);
			if ($sql->fetch()) {if ($anzahl == 1) {$fehler = false;}}
		}
		$sql->close();
	}
  else {$fehler = false;}
}



if (!$fehler) {
	$code = "";
	$code .= "<div class=\"cms_spalte_i\">";
		$code .= "<p class=\"cms_brotkrumen\">";
		$code .= cms_brotkrumen($CMS_URL);
		$code .= "</p>";
    if ($schuljahrid == '-') {
	    $code .= "<h1>$g schuljahrübergreifend</h1>";
    }
    else {
      $code .= "<h1>$g im Schuljahr $schuljahr</h1>";
    }

    $gruppen = cms_gruppen_links_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $schuljahrid, true);

    if (strlen($gruppen) > 0) {$code .= $gruppen;}
    else {$code .= "<p>In diesem Zeitraum wurde keine sichtbare Gruppe gefunden und es bestehen auch keine Mitgliedschaften.</p>";}
	$code .= "</div>";
}
else {
	cms_fehler('Schulhof', '404');
}

echo $code;
cms_trennen($dbs);
?>
