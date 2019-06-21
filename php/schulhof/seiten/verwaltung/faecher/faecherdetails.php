<?php
function cms_faecher_ausgeben ($id) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$kuerzel = "";
	$kollegen = "";

	if ($id != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM faecher WHERE id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($bezeichnung, $kuerzel);
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
		$code .= "<tr><th>Kürzel:</th><td><input type=\"text\" name=\"cms_faecher_kuerzel\" id=\"cms_faecher_kuerzel\" value=\"".$kuerzel."\"></td></tr></td></tr>";
		$code .= "<tr><th>Kollegen:</th><td>".cms_personensuche_personhinzu_generieren($dbs, 'cms_faecher_kollegen', 'l', $kollegen)."</td></tr></td></tr>";
	$code .= "</table>";

	cms_trennen($dbs);
	return $code;
}
?>
