<?php
function cms_dauerbrenner_ausgeben ($dauerbrennerid) {
	global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$sichtbarl = 0;
	$sichtbars = 0;
	$sichtbarv = 0;
	$sichtbarx = 0;
	$sichtbare = 0;
	$inhalt = "";

	if ($dauerbrennerid != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM dauerbrenner WHERE id = ?");
	  $sql->bind_param("i", $dauerbrennerid);
	  if ($sql->execute()) {
	    $sql->bind_result($bezeichnung, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx, $inhalt);
	    $sql->fetch();
	  }
	  $sql->close();
	}

	$code .= "</div>";

	$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td colspan=\"5\"><input type=\"text\" name=\"cms_dauerbrenner_bezeichnung\" id=\"cms_dauerbrenner_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th></th><td>".cms_generiere_hinweisicon("lehrer", "Lehrer")."</td>";
		$code .= "<td>".cms_generiere_hinweisicon("schueler", "Schüler")."</td><td>".cms_generiere_hinweisicon("elter", "Eltern")."</td>";
		$code .= "<td>".cms_generiere_hinweisicon("verwaltung", "Verwaltungsangestellte")."</td><td>".cms_generiere_hinweisicon("extern", "Externe")."</td></tr>";
		$code .= "<tr><th>Sichtbarkeit:</th><td>".cms_schieber_generieren('dauerbrenner_sichtbarl', $sichtbarl)."</td>";
		$code .=     "<td>".cms_schieber_generieren('dauerbrenner_sichtbars', $sichtbars)."</td>";
		$code .=     "<td>".cms_schieber_generieren('dauerbrenner_sichtbare', $sichtbare)."</td>";
		$code .=     "<td>".cms_schieber_generieren('dauerbrenner_sichtbarv', $sichtbarv)."</td>";
		$code .=     "<td>".cms_schieber_generieren('dauerbrenner_sichtbarx', $sichtbarx)."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Inhalt</h3>";
	$code .= cms_gruppeneditor('cms_dauerbrenner_intern', $inhalt);

	$code .= "</div></div>";

	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	cms_trennen($dbs);


	return $code;
}
?>
