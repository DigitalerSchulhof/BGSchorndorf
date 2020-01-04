<?php
function cms_auszeichnung_details_laden($id) {
  global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN, $CMS_BENUTZERART, $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID, $CMS_GRUPPEN, $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL;
  $code = "";

	$zugriff = false;
	$fehler = false;

  if ((($id == '-') && r("website.auszeichnungen.anlegen")) || (($id != '-') && r("website.auszeichnungen.bearbeiten"))) {$zugriff = true;}

  $bild = '';
  $bez = '';
  $link = '';
  $ziel = '_blank';
  $zieloptionen = "<option value=\"_blank\">Neuer Tab</option><option value=\"_self\">Dieser Tab</option>";
  $reihenfolge = null;
  $aktiv = 1;

  // Letzte Position ermitteln
  $dbs = cms_verbinden('s');
  $sql = $dbs->prepare("SELECT MAX(reihenfolge) FROM auszeichnungen");
  if ($sql->execute()) {
    $sql->bind_result($reihenfolge);
    $sql->fetch();
  }
  $sql->close();
  if ($reihenfolge === null) {$reihenfolge = 1;}
  else {if ($id == '-') {$reihenfolge ++;}}


  $reihenfolgeoptionen = "";
  for ($r=1; $r<=$reihenfolge; $r++) {
    $reihenfolgeoptionen .="<option value=\"$r\">$r</option>";
  }


  // Inhalte der Auszeichnung laden, falls gewünscht
  if ($id != "-") {
	  $sql = $dbs->prepare("SELECT bild, bezeichnung, link, ziel, reihenfolge, aktiv FROM auszeichnungen WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($bild, $bez, $link, $ziel, $reihenfolge, $aktiv);
      if (!$sql->fetch()) {$fehler = true;}
    }
    else {$fehler = true;}
    $sql->close();
  }

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();

	if ($angemeldet && $zugriff) {
    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('auszeichnung_aktiv', $aktiv)."</td></tr>";
    $code .= "<tr><th>Bild:</th><td>".cms_dateiwahl_knopf('website', 'cms_auszeichnung_bild', 's', 'website', '-', 'vorschaubild', $bild)."</td></tr>";
    $code .= "<tr><th>Bezeichnung:</th><td><textarea rows=\"10\" name=\"cms_auszeichnung_bezeichnung\" id=\"cms_auszeichnung_bezeichnung\">$bez</textarea></td></tr>";
    $code .= "<tr><th>Link:</th><td><input type=\"text\" name=\"cms_auszeichnung_link\" id=\"cms_auszeichnung_link\" value=\"$link\"/></td></tr>";
    $code .= "<tr><th>Ziel:</th><td><select name=\"cms_auszeichnung_ziel\" id=\"cms_auszeichnung_ziel\">".str_replace("<option value=\"$ziel\">", "<option value=\"$ziel\" selected=\"selected\">", $zieloptionen)."</select></td></tr>";
    $code .= "<tr><th>Position:</th><td><select name=\"cms_auszeichnung_position\" id=\"cms_auszeichnung_position\">".str_replace("<option value=\"$reihenfolge\">", "<option value=\"$reihenfolge\" selected=\"selected\">", $reihenfolgeoptionen)."</select></td></tr>";
		$code .= "</table>";

    if ($id == "-") {
		  $code .= "<p><span class=\"cms_button\" onclick=\"cms_auszeichnung_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Website/Auszeichnungen\">Abbrechen</a></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_auszeichnung_bearbeiten_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Website/Auszeichnungen\">Abbrechen</a></p>";
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
