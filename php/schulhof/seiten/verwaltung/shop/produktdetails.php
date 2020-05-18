<?php
function cms_produkt_ausgeben ($produktid) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";

	$titel = "";
	$beschreibung = "";
	$bild = "";
	$preis = "0,00";
	$stk = 0;
	$lieferzeit = "";

	if ($produktid != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(bild, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), preis, stk, AES_DECRYPT(lieferzeit, '$CMS_SCHLUESSEL') FROM egeraete WHERE id = ?");
	  $sql->bind_param("i", $produktid);
	  if ($sql->execute()) {
	    $sql->bind_result($titel, $bild, $beschreibung, $preis, $stk, $lieferzeit);
	    $sql->fetch();

			if ($preis != null) {$preis = cms_format_preis($preis/100);}
	  }
	  $sql->close();
	}

	$code .= "</div>";

	$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Titel:</th><td>".cms_generiere_input("cms_produkt_titel", $titel)."</td></tr>";
		$code .= "<tr><th>Bilderlink:</th><td>".cms_generiere_input("cms_produkt_bild", $bild)."</td></tr>";
		$code .= "<tr><th>Preis:</th><td>".cms_generiere_input("cms_produkt_preis", $preis)."</td></tr>";
		$code .= "<tr><th>Anzahl:</th><td>".cms_generiere_input("cms_produkt_stk", $stk)."</td></tr>";
		$code .= "<tr><th>Lieferzeit:</th><td>".cms_generiere_input("cms_produkt_lieferzeit", $lieferzeit)."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Beschreibung</h3>";
	$code .= cms_gruppeneditor('cms_produkt_beschreibung', $beschreibung);

	$code .= "</div></div>";

	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	cms_trennen($dbs);


	return $code;
}
?>
