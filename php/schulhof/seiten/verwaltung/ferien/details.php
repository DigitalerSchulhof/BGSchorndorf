<?php
function cms_ferien_details_laden($id) {
  global $CMS_SCHLUESSEL;
  $code = "";

	$zugriff = false;
	$fehler = false;

  if ((($id == '-') && cms_r("schulhof.organisation.ferien.anlegen")) || (($id != '-') && cms_r("schulhof.organisation.ferien.bearbeiten"))) {$zugriff = true;}

  $bez = '';
  $art = '';
  $beginn = time();
  $ende = time();

  // Falls ein bestehender Ferientermin geladen werden soll
  $dbs = cms_verbinden('s');
  if ($id != "-") {
	  $sql = $dbs->prepare("SELECT * FROM ferien WHERE id = ?");
    $sql->bind_param("i", $id);
		if ($sql->execute()) {
      $sql->bind_result($id, $bez, $art, $beginn, $ende, $mehrtaegigt, $idvon, $idzeit);
			$sql->fetch();
		}
    $sql->close();
  }

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();

	if ($angemeldet && $zugriff) {

		$code .= "<table class=\"cms_formular\">";
    $btag = date('d', $beginn);
    $bmonat = date('m', $beginn);
    $bjahr = date('Y', $beginn);
    $etag = date('d', $ende);
    $emonat = date('m', $ende);
    $ejahr = date('Y', $ende);

    $code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_ferien_bezeichnung\" id=\"cms_ferien_bezeichnung\" value=\"$bez\"/></td></tr>";
    $code .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe('cms_ferien_beginn_datum', $btag, $bmonat, $bjahr)."</td></tr>";
		$code .= "<tr><th>Ende:</th><td>".cms_datum_eingabe('cms_ferien_ende_datum', $etag, $emonat, $ejahr)."</td></tr>";
		$code .= "<tr><th>Art:</th><td><select name=\"cms_ferien_art\" id=\"cms_ferien_art\">";
      if ($art == 'f') {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"f\"$zusatz>Ferien</option>";
      if ($art == 'b') {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"b\">Bewegliche Ferientage</option>";
      if ($art == 't') {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"t\">Feiertag</option>";
      if ($art == 's') {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"s\">Sonderereignis</option>";
    $code .= "</select></td></tr>";
		$code .= "</table>";

    if ($id == "-") {
		  $code .= "<p><span class=\"cms_button\" onclick=\"cms_ferien_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Ferien\">Abbrechen</a></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_ferien_bearbeiten_speichern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Ferien\">Abbrechen</a></p>";
    }
		$code .= "</div>";

	}
	else {
		$code .= cms_meldung_berechtigung();
		$code .= "</div>";
	}
  cms_trennen($dbs);
  return $code;
}

?>
