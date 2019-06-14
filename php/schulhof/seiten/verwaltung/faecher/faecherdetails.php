<?php
function cms_faecher_ausgeben ($id) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$kuerzel = "";

	if ($id != "-") {
		$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM faecher WHERE id = $id";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$bezeichnung = $daten['bezeichnung'];
				$kuerzel = $daten['kuerzel'];
			}
			$anfrage->free();
		}
	}

	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_faecher_bezeichnung\" id=\"cms_faecher_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th>Kürzel:</th><td><input type=\"text\" name=\"cms_faecher_kuerzel\" id=\"cms_faecher_kuerzel\" value=\"".$kuerzel."\"></td></tr></td></tr>";
	$code .= "</table>";

	cms_trennen($dbs);
	return $code;
}
?>
