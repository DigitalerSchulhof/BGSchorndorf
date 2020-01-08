<?php
function cms_pinnwandanschlaege_ausgeben ($anschlagid, $pinnwandid) {
	global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN, $CMS_BENUTZERID;
	$dbs = cms_verbinden('s');
	$code = "";

	$titel = "";
	$inhalt = "";
	$beginn = time();
	$ende = $beginn+14*24*60*60;
	$fehler = false;

	if ($anschlagid != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), beginn, ende, idvon, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM pinnwandanschlag WHERE id = ? AND pinnwand = ?");
	  $sql->bind_param("ii", $anschlagid, $pinnwandid);
	  if ($sql->execute()) {
	    $sql->bind_result($titel, $beginn, $ende, $ersteller, $inhalt);
	    $sql->fetch();
	  }
	  $sql->close();

		if (($ersteller != $CMS_BENUTZERID) && !cms_r("schulhof.information.pinnwände.anschläge.bearbeiten"))) {$fehler = true;}
	}

	if (!$fehler) {
		$code .= "</div>";

		$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Details</h3>";
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th>Titel:</th><td colspan=\"5\"><input type=\"text\" name=\"cms_anschalg_titel\" id=\"cms_anschalg_titel\" value=\"".$titel."\"></td></tr>";
			$code .= "<tr><th>Aushängen ab:</th><td>".cms_datum_eingabe ('cms_anschlag_von', date('d', $beginn), date('m', $beginn), date('Y', $beginn))."</td></tr>";
			$code .= "<tr><th>Aushängen bis:</th><td>".cms_datum_eingabe ('cms_anschlag_bis', date('d', $ende), date('m', $ende), date('Y', $ende))."</td></tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Inhalt</h3>";
		$code .= cms_gruppeneditor('cms_anschlag_inhalt', $inhalt);
		$code .= "</div></div>";

		$code .= "<div class=\"cms_clear\"></div>";
		$code .= "<div class=\"cms_spalte_i\">";
	}

	cms_trennen($dbs);


	return $code;
}
?>
