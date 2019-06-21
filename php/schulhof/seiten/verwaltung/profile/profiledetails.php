<?php
function cms_profile_ausgeben ($id, $SCHULJAHR = false) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$art = "p";
	$stufe = "";
	$faecher = array();

	if ($id != "-") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, art, stufe, schuljahr FROM profile WHERE id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($bezeichnung, $art, $stufe, $SCHULJAHR);
			$sql->fetch();
		}
		$sql->close();

		// Kollegen
		$sql = $dbs->prepare("SELECT fach FROM profilfaecher JOIN faecher ON profilfaecher.fach = faecher.id WHERE profil = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($fid);
			while ($sql->fetch()) {
				array_push($faecher, $fid);
			}
		}
		$sql->close();
	}

	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_profil_bezeichnung\" id=\"cms_profil_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th>Stufe:</th><td><select name=\"cms_profil_stufe\" id=\"cms_profil_stufe\">";
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), reihenfolge FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($sid, $sbez, $sreihenfolge);
			while ($sql->fetch()) {
				if ($sid == $stufe) {$wahl = " selected=\"selected\"";} else {$wahl = "";}
				$code .= "<option value=\"$sid\"$wahl>$sbez</option>";
			}
		}
		$sql->close();

		$code .= "</select></td></tr>";
		$code .= "<tr><th>Profilart:</th><td><select name=\"cms_profil_art\" id=\"cms_profil_art\">";
		if ($art == "p") {$wahl = " selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"p\"$wahl>Pflichtprofil</option>";
		if ($art == "w") {$wahl = " selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"w\"$wahl>Wahlprofil</option>";
		$code .= "</select></td></tr>";
		$code .= "<tr><th>Wahlfächer:</th><td>";
		$faecherids = "";
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM faecher WHERE schuljahr = ?");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($fid, $fbez, $fkur);
			while ($sql->fetch()) {
				if (in_array($fid, $faecher)) {$code .= cms_togglebutton_generieren ("cms_profil_fach_".$fid, $fbez." ($fkur)", 1)." ";}
				else {$code .= cms_togglebutton_generieren ("cms_profil_fach_".$fid, $fbez." ($fkur)", 0)." ";}
				$faecherids .= "|".$fid;
			}
		}
		$sql->close();

		foreach ($faecher as $f) {


		}
		$code .= "<input type=\"hidden\" name=\"cms_profil_faecherids\" id=\"cms_profil_faecherids\" value=\"$faecherids\">";
		$code .= "</td></tr></td></tr>";
	$code .= "</table>";

	cms_trennen($dbs);
	return $code;
}
?>
