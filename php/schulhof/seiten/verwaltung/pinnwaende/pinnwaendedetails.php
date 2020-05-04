<?php
function cms_pinnwaende_ausgeben ($pinnwandid) {
	global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$sichtbarl = 0;
	$sichtbars = 0;
	$sichtbarv = 0;
	$sichtbarx = 0;
	$sichtbare = 0;
	$schreibenl = 0;
	$schreibens = 0;
	$schreibenv = 0;
	$schreibene = 0;
	$schreibenx = 0;
	$schreibenp = 0;
	$beschreibung = "";

	if ($pinnwandid != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx, schreibens, schreibenl, schreibene, schreibenv, schreibenx, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') FROM pinnwaende WHERE id = ?");
	  $sql->bind_param("i", $pinnwandid);
	  if ($sql->execute()) {
	    $sql->bind_result($bezeichnung, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx, $schreibens, $schreibenl, $schreibene, $schreibenv, $schreibenx, $beschreibung);
	    $sql->fetch();
	  }
	  $sql->close();
	}
	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td colspan=\"5\"><input type=\"text\" name=\"cms_pinnwaende_bezeichnung\" id=\"cms_pinnwaende_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th>Beschreibung:</th><td colspan=\"5\"><textarea name=\"cms_pinnwaende_beschreibung\" id=\"cms_pinnwaende_beschreibung\" rows=\"5\">$beschreibung</textarea></td></tr>";
		$code .= "<tr><th></th><td>".cms_generiere_hinweisicon("lehrer", "Lehrer")."</td>";
		$code .= "<td>".cms_generiere_hinweisicon("schueler", "Schüler")."</td><td>".cms_generiere_hinweisicon("elter", "Eltern")."</td>";
		$code .= "<td>".cms_generiere_hinweisicon("verwaltung", "Verwaltungsangestellte")."</td><td>".cms_generiere_hinweisicon("extern", "Externe")."</td></tr>";
		$code .= "<tr><th>Sichtbarkeit:</th><td>".cms_generiere_schieber('pinnwaende_sichtbarl', $sichtbarl)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_sichtbars', $sichtbars)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_sichtbare', $sichtbare)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_sichtbarv', $sichtbarv)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_sichtbarx', $sichtbarx)."</td></tr>";
		$code .= "<tr><th>Schreibrecht:</th><td>".cms_generiere_schieber('pinnwaende_schreibenl', $schreibenl)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_schreibens', $schreibens)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_schreibene', $schreibene)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_schreibenv', $schreibenv)."</td>";
		$code .=     "<td>".cms_generiere_schieber('pinnwaende_schreibenx', $schreibenx)."</td></tr>";
	$code .= "</table>";

	cms_trennen($dbs);


	return $code;
}
?>
