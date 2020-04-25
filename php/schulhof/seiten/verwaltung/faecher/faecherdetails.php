<?php
function cms_faecher_ausgeben ($id) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$kuerzel = "";
	$kollegen = "";
	$farbe = 0;
	$icon = "standard.png";

	if ($id != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, farbe FROM faecher WHERE id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($bezeichnung, $kuerzel, $icon, $farbe);
			$sql->fetch();
		}
		$sql->close();

		// Kolelgen
		$sql = $dbs->prepare("SELECT kollege FROM fachkollegen WHERE fach = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($pid);
			while ($sql->fetch()) {
				$kollegen .= "|".$pid;
			}
		}
		$sql->close();
	}

	include_once('php/schulhof/seiten/personensuche/personensuche.php');

	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_faecher_bezeichnung\" id=\"cms_faecher_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th>Kürzel:</th><td><input type=\"text\" name=\"cms_faecher_kuerzel\" id=\"cms_faecher_kuerzel\" value=\"".$kuerzel."\"></td></tr>";
		$code .= "<tr><th>Farbe:</th><td>";
		$pause = 0;
		for ($i=0; $i<16*4; $i++) {
			if ($pause == 16) {$code .= "<br>"; $pause = 0;}
			if ($farbe == $i) {$zusatz = "_aktiv";} else {$zusatz = "";}
			$pause++;
			$code .= "<span class=\"cms_farbbeispiel$zusatz cms_farbbeispiel_".$i."\" id=\"cms_farbbeispiel_".$i."\" onclick=\"cms_farbbeispiel_waehlen($i, 'cms_faecher_farbe')\"></span>";
		}
		$code .= "<input type=\"hidden\" name=\"cms_faecher_farbe\" id=\"cms_faecher_farbe\" value=\"$farbe\">";
		$code .= "</td></tr>";
		$code .= "<tr><th>Icon:</th><td><img id=\"cms_gruppe_icon_vorschau\" src=\"res/gruppen/gross/$icon\"> <span class=\"cms_button\" onclick=\"cms_einblenden('cms_gruppe_icon_auswahl');\">Ändern</span><input type=\"hidden\" name=\"cms_gruppe_icon\" id=\"cms_gruppe_icon\" value=\"$icon\">";
    $code .= "<div id=\"cms_gruppe_icon_auswahl\" style=\"display: none;\">";
    $code .= "<p><span class=\"cms_button_nein cms_button_schliessen\" onclick=\"cms_ausblenden('cms_gruppe_icon_auswahl');\">&times</span>";
    $iconsanzahl = 0;
    $icons = scandir('res/gruppen/gross');
    foreach ($icons as $i) {
      $endung = substr($i, -4);
      if ($endung == '.png') {
        $iconsanzahl++;
        if ($i == $icon) {$zusatz = "_aktiv";} else {$zusatz = "";}
        $code .= "<span class=\"cms_kategorie_icon$zusatz\" id=\"cms_gruppe_icon_$iconsanzahl\" onclick=\"cms_kategorie_icon_waehlen('cms_gruppe', $iconsanzahl)\"><img src=\"res/gruppen/gross/$i\"><input type=\"hidden\" name=\"cms_gruppe_icon_".$iconsanzahl."_name\" id=\"cms_gruppe_icon_".$iconsanzahl."_name\" value=\"$i\"></span>";
      }
    }
    $code .= "<input type=\"hidden\" name=\"cms_gruppe_icon_anzahl\" id=\"cms_gruppe_icon_anzahl\" value=\"$iconsanzahl\"></p>";
    $code .= "</div>";
  	$code .= "</td></tr>";
		$code .= "<tr><th>Kollegen:</th><td>".cms_personensuche_personhinzu_generieren($dbs, 'cms_faecher_kollegen', 'l', $kollegen)."</td></tr>";
	$code .= "</table>";

	cms_trennen($dbs);
	return $code;
}
?>
