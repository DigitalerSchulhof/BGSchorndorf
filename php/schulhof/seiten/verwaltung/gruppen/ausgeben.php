<?php
function cms_gruppen_verwaltung_gruppeneigenschaften($name, $anlegen, $bearbeiten, $id) {
  global $CMS_SCHLUESSEL, $CMS_IMLN, $CMS_EINSTELLUNGEN;

  if ($id == '-') {$zugriff = $anlegen;}
  else {$zugriff = $bearbeiten;}

  $namek = strtolower($name);
  $namek = str_replace(' ', '', $namek);

	$ausgabe = "";


	if ($zugriff) {
    $bezeichnung = "";
    $sichtbar = 3;
    $chat = 0;
    $icon = "standard.png";
    $verwendeteicons = array();
    $schuljahr = "-";
    $mitglieder = "";
    $vorsitz = "";
    $aufsicht = "";
    $schuljahre = array();

    // Bestimmte Gruppen können nur im Lehrernetz angelegt werden
    if ((($namek == "gremien") || ($namek == "fachschaften")) && ($id == '-')) {
      $sichtbar = 1;
      if (!$CMS_IMLN) {
        $ausgabe .= cms_meldung_eingeschraenkt();
        $ausgabe .= "<p><a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/".$name."\">Abbrechen</a></p>";
        return cms_meldung_eingeschraenkt();
      }
    }

    // Bestimmte Gruppen haben besondere Eigenschaften
    if ($namek == "klassen") {
      $stufe = '-';
      $stundenplanextern = '';
      $klassenbezextern = '';
      $stufenbezextern = '';
    }
    else if ($namek == "stufen") {
      $reihenfolge = '';
      $stufenanzahl = 0;
      $tagebuch = 0;
      $gfs = 0;
    }
    else if ($namek == "kurse") {
      $stufe = '-';
      $fach = '';
      $kursbezextern = '';
      $kurzbezeichnung = "";
      $zugeordneteklassen = array();
      $allezugeordnetenklassenids = "";
    }

    $dbs = cms_verbinden('s');
    // Gruppe selbst laden
    if ($id != '-') {
      $sqlzusatz = "";
      if ($namek == "klassen") {
        $sqlzusatz = ", AES_DECRYPT(stundenplanextern, '$CMS_SCHLUESSEL') AS stundenplanextern, AES_DECRYPT(stufenbezextern, '$CMS_SCHLUESSEL') AS stufenbezextern, AES_DECRYPT(klassenbezextern, '$CMS_SCHLUESSEL') AS klassenbezextern, stufe";
      }
      else if ($namek == "stufen") {
        $sqlzusatz = ", reihenfolge, tagebuch, gfs";
      }
      else if ($namek == "kurse") {
        $sqlzusatz = ", stufe, fach, AES_DECRYPT(kursbezextern, '$CMS_SCHLUESSEL') AS kursbezextern, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbezeichnung";
      }
      $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, schuljahr, chataktiv$sqlzusatz FROM $namek WHERE id = $id";
      if ($anfrage = $dbs->query($sql)) {
        if ($daten = $anfrage->fetch_assoc()) {
          $bezeichnung = $daten['bezeichnung'];
          $sichtbar = $daten['sichtbar'];
          $chat = $daten['chataktiv'];
          $icon = $daten['icon'];
          if ($daten['schuljahr'] != null) {$schuljahr = $daten['schuljahr'];}
          else {$schuljahr = "-";}

          if ($namek == "klassen") {
            $stufe = $daten['stufe'];
            $stundenplanextern = $daten['stundenplanextern'];
            $klassenbezextern = $daten['klassenbezextern'];
            $stufenbezextern = $daten['stufenbezextern'];
          }
          else if ($namek == "stufen") {
            $reihenfolge = $daten['reihenfolge'];
            $tagebuch = $daten['tagebuch'];
            $gfs = $daten['gfs'];
          }
          else if ($namek == "kurse") {
            $stufe = $daten['stufe'];
            $fach = $daten['fach'];
            $kursbezextern = $daten['kursbezextern'];
            $kurzbezeichnung = $daten['kurzbezeichnung'];
          }
        }
        $anfrage->free();
      }

      // Zugeordnete Klassen suchen
      if ($namek == 'kurse') {
        $sql = "SELECT klasse AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM kurseklassen JOIN klassen ON kurseklassen.klasse = klassen.id WHERE kurs = $id";
        if ($anfrage = $dbs->query($sql)) {
          while ($daten = $anfrage->fetch_assoc()) {
            array_push($zugeordneteklassen, $daten);
          }
          $anfrage->free();
        }
      }

      // Mitglieder
      $sql = "SELECT gruppe, person FROM $namek"."mitglieder WHERE gruppe = $id";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $mitglieder .= "|".$daten['person'];
        }
        $anfrage->free();
      }

      // Vorsitz
      $sql = "SELECT gruppe, person FROM $namek"."vorsitz WHERE gruppe = $id";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $vorsitz .= "|".$daten['person'];
        }
        $anfrage->free();
      }

      // Aufsicht
      $sql = "SELECT gruppe, person FROM $namek"."aufsicht WHERE gruppe = $id";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $aufsicht .= "|".$daten['person'];
        }
        $anfrage->free();
      }
    }

    // Anzahl Stufen in diesem Schuljahr
    if ($namek == 'stufen') {
      if ($schuljahr == '-') {$schuljahrwert = "IS NULL";} else {$schuljahrwert = " = ".$schuljahr;}
      $sql = "SELECT count(id) AS anzahl FROM stufen WHERE schuljahr $schuljahrwert";
      if ($anfrage = $dbs->query($sql)) {
        if ($daten = $anfrage->fetch_assoc()) {
          $stufenanzahl = $daten['anzahl'];
        }
        $anfrage->free();
      }
    }

    // Stufen in diesem Schuljahr
    if (($namek == 'klassen') || ($namek == 'kurse')) {
      $stufen = array();
      if ($schuljahr == '-') {$schuljahrwert = "IS NULL";} else {$schuljahrwert = " = ".$schuljahr;}
      $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr $schuljahrwert ORDER BY reihenfolge ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          array_push($stufen, $daten);
        }
        $anfrage->free();
      }

      $faecher = array();
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM faecher WHERE schuljahr $schuljahrwert) AS x ORDER BY bez ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          array_push($faecher, $daten);
        }
        $anfrage->free();
      }
    }

    // Fächer und Klassen laden
    if ($namek == 'kurse') {
      $klassen = array();
      if (($stufe != '-') && (!is_null($stufe))) {$stufetest = " AND stufe = $stufe";} else {$stufetest = "";}
      $sql = "SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr $schuljahrwert"."$stufetest) AS x ORDER BY reihenfolge ASC, bez ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          array_push($klassen, $daten);
        }
        $anfrage->free();
      }

      if ($id != '-') {
        $sql = "SELECT klasse FROM kurseklassen WHERE kurs = $id";
        if ($anfrage = $dbs->query($sql)) {
          while ($daten = $anfrage->fetch_assoc()) {
            array_push($zugeordneteklassen, $daten['klasse']);
            $allezugeordnetenklassenids .= "|".$daten['klasse'];
          }
          $anfrage->free();
        }
      }
    }

    // Alle Schuljahre laden
    $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY ende DESC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        array_push($schuljahre, $daten);
      }
      $anfrage->free();
    }
    // Verwendete Icons laden
    $sql = "SELECT DISTINCT AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $namek";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        array_push($verwendeteicons, $daten['icon']);
      }
      $anfrage->free();
    }

    $ausgabe .= "<h3>Gruppendetails</h3>";
    $ausgabe .= "<table class=\"cms_formular\">";
    $ausgabe .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_gruppe_bezeichnung\" id=\"cms_gruppe_bezeichnung\" value=\"$bezeichnung\"></td></tr>";
    if ($namek == 'kurse') {
      $ausgabe .= "<tr><th>Kurzbezeichnung:</th><td><input class=\"cms_klein\" type=\"text\" name=\"cms_gruppe_kurzbezeichnung\" id=\"cms_gruppe_kurzbezeichnung\" value=\"$kurzbezeichnung\"></td></tr>";
    }
    $ausgabe .= "<tr><th>Sichtbarkeit:</th><td><select name=\"cms_gruppe_sichtbar\" id=\"cms_gruppe_sichtbar\">";
      if ($sichtbar === 0) {$selected = " selected=\"selected\"";} else {$selected = "";}
      $ausgabe .= "<option$selected value=\"0\">Nur für Mitglieder</option>";
      if ($sichtbar === 1) {$selected = " selected=\"selected\"";} else {$selected = "";}
      $ausgabe .= "<option$selected value=\"1\">Nur für Lehrer</option>";
      if ($sichtbar === 2) {$selected = " selected=\"selected\"";} else {$selected = "";}
      $ausgabe .= "<option$selected value=\"2\">Nur für Lehrer und die Verwaltung</option>";
      if ($sichtbar === 3) {$selected = " selected=\"selected\"";} else {$selected = "";}
      $ausgabe .= "<option$selected value=\"3\">Für den ganzen Schulhof</option>";
    $ausgabe .= "</select></td></tr>";
    $ausgabe .= "<tr><th>Chat aktivieren:</th><td>".cms_schieber_generieren('gruppe_chat', $chat)."</td></tr>";

    if ($id != '-') {$ausgabe .= "<tr><th>Schuljahr:</th><td><select name=\"cms_gruppe_schuljahr\" id=\"cms_gruppe_schuljahr\" disabled=\"disabled\">";}
    else if ($namek == 'stufen') {$ausgabe .= "<tr><th>Schuljahr:</th><td><select name=\"cms_gruppe_schuljahr\" id=\"cms_gruppe_schuljahr\" onchange=\"cms_gruppe_reihenfolge_laden()\">";}
    else if ($namek == 'klassen') {$ausgabe .= "<tr><th>Schuljahr:</th><td><select name=\"cms_gruppe_schuljahr\" id=\"cms_gruppe_schuljahr\" onchange=\"cms_gruppe_stufen_laden();cms_gruppe_klassenfaecher_laden();\">";}
    else if ($namek == 'kurse') {$ausgabe .= "<tr><th>Schuljahr:</th><td><select name=\"cms_gruppe_schuljahr\" id=\"cms_gruppe_schuljahr\" onchange=\"cms_gruppe_stufen_laden();cms_gruppe_klassen_laden();cms_gruppe_kursefaecher_laden();\">";}
    else {$ausgabe .= "<tr><th>Schuljahr:</th><td><select name=\"cms_gruppe_schuljahr\" id=\"cms_gruppe_schuljahr\">";}
    // Schuljahre laden
    if ($schuljahr == '-') {$wahl = " selected=\"selected\";";} else {$wahl = "";}
    $ausgabe .= "<option value=\"-\"$wahl>Über die Schuljahre hinweg</option>";
    foreach ($schuljahre as $sj) {
      if ($schuljahr == $sj['id']) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
      $ausgabe .= "<option value=\"".$sj['id']."\"$wahl>".$sj['bezeichnung']."</option>";
    }
    $ausgabe .= "</select></td></tr>";
    if ($namek == 'stufen') {
      $ausgabe .= "<tr><th>Reihenfolge:</th><td><select name=\"cms_gruppe_reihenfolge\" id=\"cms_gruppe_reihenfolge\">";
      for ($i = 1; $i <= $stufenanzahl; $i++) {
        if ($reihenfolge == $i) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
        $ausgabe .= "<option value=\"$i\"$wahl>$i</option>";
      }
      if ($id == '-') {$ausgabe .= "<option value=\"$i\">$i</option>";}
      $ausgabe .= "</select></td></tr>";
      $ausgabe .= "<tr><th>Tagebuch:</th><td>".cms_schieber_generieren('gruppe_tagebuch', $tagebuch)."</td></tr>";
      $ausgabe .= "<tr><th>GFS-Veraltung:</th><td>".cms_schieber_generieren('gruppe_gfs', $gfs)."</td></tr>";
    }
    if (($namek == 'klassen') || ($namek == 'kurse')) {
      if ($id == '-') {
        if ($namek == 'klassen') {$ausgabe .= "<tr><th>Stufe:</th><td><select name=\"cms_gruppe_stufe\" id=\"cms_gruppe_stufe\">";}
        else if ($namek == 'kurse') {$ausgabe .= "<tr><th>Stufe:</th><td><select name=\"cms_gruppe_stufe\" id=\"cms_gruppe_stufe\" onchange=\"cms_gruppe_klassen_laden()\">";}
      }
      else {$ausgabe .= "<tr><th>Stufe:</th><td><select name=\"cms_gruppe_stufe\" id=\"cms_gruppe_stufe\" disabled=\"disabled\">";}
      foreach ($stufen AS $s) {
        if ($stufe === $s['id']) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
        $ausgabe .= "<option value=\"".$s['id']."\"$wahl>".$s['bez']."</option>";
      }
      if (($stufe === '-') || (is_null($stufe))) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
      $ausgabe .= "<option value=\"-\"$wahl>stufenübergreifend</option>";
      $ausgabe .= "</select></td></tr>";
    }
    if ($namek == 'kurse') {
      $ausgabe .= "<tr><th>Fach:</th><td><select name=\"cms_gruppe_fach\" id=\"cms_gruppe_fach\">";
      foreach ($faecher AS $f) {
        if ($fach == $f['id']) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
        $ausgabe .= "<option value=\"".$f['id']."\"$wahl>".$f['bez']."</option>";
      }
      if ($fach == '-') {$wahl = " selected=\"selected\";";} else {$wahl = "";}
      $ausgabe .= "<option value=\"-\"$wahl>fächerübergreifend</option>";
      $ausgabe .= "</select></td></tr>";

      $ausgabe .= "<tr><th>Zugeordnete Klassen:</th><td id=\"cms_gruppe_klassen_F\">";
      $alleklassenids = "";
      foreach ($klassen AS $k) {
        if (in_array($k['id'], $zugeordneteklassen)) {$wert = 1;}
        else {$wert = 0;}
        $ausgabe .= cms_togglebutton_generieren("cms_gruppe_klassen_".$k['id'], $k['bez'], $wert, 'cms_gruppe_klassenaktualisieren()')." ";
        $alleklassenids .= '|'.$k['id'];
      }
      $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_klassen_alle\" id=\"cms_gruppe_klassen_alle\" value=\"$alleklassenids\">";
      $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_klassen\" id=\"cms_gruppe_klassen\" value=\"$allezugeordnetenklassenids\">";
      $ausgabe .= "</td></tr>";
    }
    $ausgabe .= "<tr><th>Icon:</th><td><img id=\"cms_gruppe_icon_vorschau\" src=\"res/gruppen/gross/$icon\"> <span class=\"cms_button\" onclick=\"cms_einblenden('cms_gruppe_icon_auswahl');\">Ändern</span><input type=\"hidden\" name=\"cms_gruppe_icon\" id=\"cms_gruppe_icon\" value=\"$icon\">";

      $ausgabe .= "<div id=\"cms_gruppe_icon_auswahl\" style=\"display: none;\">";
      $ausgabe .= "<p><span class=\"cms_button_nein cms_button_schliessen\" onclick=\"cms_ausblenden('cms_gruppe_icon_auswahl');\">&times</span>";
      $iconsanzahl = 0;
      $icons = scandir('res/gruppen/gross');
      foreach ($icons as $i) {
        $endung = substr($i, -4);
        if ($endung == '.png') {
          $iconsanzahl++;
          if ($i == $icon) {$zusatz = "_aktiv";} else {$zusatz = "";}
          if (in_array($i, $verwendeteicons)) {$zusatz .= " cms_icon_verwendet";}
          $ausgabe .= "<span class=\"cms_kategorie_icon$zusatz\" id=\"cms_gruppe_icon_$iconsanzahl\" onclick=\"cms_kategorie_icon_waehlen('cms_gruppe', $iconsanzahl)\"><img src=\"res/gruppen/gross/$i\"><input type=\"hidden\" name=\"cms_gruppe_icon_".$iconsanzahl."_name\" id=\"cms_gruppe_icon_".$iconsanzahl."_name\" value=\"$i\"></span>";
        }
      }
      $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_icon_anzahl\" id=\"cms_gruppe_icon_anzahl\" value=\"$iconsanzahl\"></p>";
      $ausgabe .= "</div>";
    $ausgabe .= "</td></tr>";
    if ($namek == "klassen") {
      if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == 1) {
        $ausgabe .= "<tr><th>Externer Stundenplan:</th><td>".cms_dateiwahl_knopf ('schulhof/stundenplaene', 'cms_gruppe_stundenplan_extern', 's', 'Vertretungsplan', '-', 'download', $stundenplanextern)."</td>";
      }
      if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == 1) {
        $ausgabe .= "<tr><th>Externe Klassenbezeichnung:</th><td><input type=\"text\" class=\"cms_klein\" name=\"cms_gruppe_klassenbez_extern\" id=\"cms_gruppe_klassenbez_extern\" value=\"$klassenbezextern\"></td></tr>";
        $ausgabe .= "<tr><th>Externe Stufenbezeichnung:</th><td><input type=\"text\" class=\"cms_klein\" name=\"cms_gruppe_stufenbez_extern\" id=\"cms_gruppe_stufenbez_extern\" value=\"$stufenbezextern\"></td></tr>";
      }
    }
    if ($namek == "kurse") {
      if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == 1) {
        $ausgabe .= "<tr><th>Externe Kursbezeichnung:</th><td><input type=\"text\" class=\"cms_klein\" name=\"cms_gruppe_kursbez_extern\" id=\"cms_gruppe_kursbez_extern\" value=\"$kursbezextern\"></td></tr>";
      }
    }
    $ausgabe .= "</table>";

    if ($namek == "klassen") {
      $ausgabe .= "<p>";
      if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] != 1) {
        $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_stundenplan_extern\" id=\"cms_gruppe_stundenplan_extern\" value=\"\">";
      }
      if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] != 1) {
        $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_klassenbez_extern\" id=\"cms_gruppe_klassenbez_extern\" value=\"\">";
        $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_stufenbez_extern\" id=\"cms_gruppe_stufenbez_extern\" value=\"\">";
      }
      $ausgabe .= "</p>";
    }

    if ($namek == "kurse") {
      if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] != 1) {
        $ausgabe .= "<p><input type=\"hidden\" name=\"cms_gruppe_kursbez_extern\" id=\"cms_gruppe_kursbez_extern\" value=\"\"></p>";
      }
    }


    if (($namek == 'klassen') && ($id == '-')) {
      $ausgabe .= "<h3>Kurse für die folgenden Fächer erstellen</h3>";
      $ausgabe .= "<p>Es werden Kurse mit den Mitgliedern dieser Klasse erstellt:</p>";
      $ausgabe .= "<p id=\"cms_grupppe_faecher_F\">";
      $allefaecherids = "";
      foreach ($faecher as $f) {
        $ausgabe .= cms_togglebutton_generieren("cms_gruppe_faecher_".$f['id'], $f['bez'], 0, 'cms_gruppe_faecheraktualisieren()')." ";
        $allefaecherids .= "|".$f['id'];
      }
      if (count($faecher) == 0) {$ausgabe .= "<span class=\"cms_notiz\">Keine Fächer für dieses Schuljahr angelegt</span>";}
      $ausgabe .= "<input type=\"hidden\" name=\"cms_gruppe_faecher_alle\" id=\"cms_gruppe_faecher_alle\" value=\"$allefaecherids\">";
      $ausgabe .= "</p>";
    }
    if ($namek == 'klassen') {
      $ausgabe .= "<p><input type=\"hidden\" name=\"cms_gruppe_faecher\" id=\"cms_gruppe_faecher\" value=\"\"></p>";
    }



    $ausgabe .= "<h3>Personen</h3>";
    $ausgabe .= cms_personensuche_generieren($dbs, 'cms_gruppe_mitglieder', $name, $id, 'mitglieder', $mitglieder, $vorsitz, $namek);
    $ausgabe .= cms_personensuche_generieren($dbs, 'cms_gruppe_aufsicht', $name, $id, 'aufsicht', $aufsicht);
    cms_trennen($dbs);


    if ($id == '-') {$event = "cms_gruppen_neu_speichern('$name');";}
    else {$event = "cms_gruppen_bearbeiten_speichern('$name');";}
    $ausgabe .= "<p><span class=\"cms_button\" onclick=\"$event\">Speichern</span> ";
    $ausgabe .= "<a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/".str_replace(' ', '_', $name)."\">Abbrechen</a></p>";
	}
	else {
		$ausgabe .= cms_meldung_berechtigung();
	}
  return $ausgabe;
}
?>
