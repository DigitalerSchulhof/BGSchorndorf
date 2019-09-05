<?php
function cms_gruppen_verwaltung_listeausgeben($name, $anlegen, $bearbeiten, $loeschen) {
  global $CMS_SCHLUESSEL, $CMS_IMLN;
	$zugriff = $anlegen || $bearbeiten || $loeschen;

	$ausgabe = "";

	if ($zugriff) {
    if (($name == "Gremien") || ($name == "Fachschaften")) {
      if (!$CMS_IMLN && ($anlegen || $loeschen)) {
        $ausgabe .= cms_meldung_eingeschraenkt();
      }
    }
    // Schuljahre ausgeben
    $schuljahrids = "|-";
    $schuljahre = "<span class=\"cms_button_ja\" id=\"cms_gruppen_schuljahr_-\" onclick=\"cms_gruppen_listeausgeben('$name', '-')\">Schuljahrübergreifend</span> ";
    $dbs = cms_verbinden('s');
    $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, id FROM schuljahre ORDER BY beginn DESC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $schuljahre .= "<span class=\"cms_button\" id=\"cms_gruppen_schuljahr_".$daten['id']."\" onclick=\"cms_gruppen_listeausgeben('$name', '".$daten['id']."')\">".$daten['bezeichnung']."</span> ";
        $schuljahrids .= "|".$daten['id'];
      }
      $anfrage->free();
    }

    $ausgabe .= "<p>$schuljahre <input type=\"hidden\" name=\"cms_gruppen_schuljahr_alle\" id=\"cms_gruppen_schuljahr_alle\" value=\"$schuljahrids\"> <input type=\"hidden\" name=\"cms_gruppen_schuljahr_aktiv\" id=\"cms_gruppen_schuljahr_aktiv\" value=\"-\"></p>";

    $ausgabe .= "<table class=\"cms_liste\">";
      $ausgabe .= "<thead>";
        $ausgabe .= "<tr><th></th><th>Bezeichnung</th><th>Mitglieder</th><th>Vorsitz</th><th>Aufsicht</th><th>Sichtbar</th>";
        if ($name == "Stufen") {$ausgabe .= "<th></th>";}
        $ausgabe .= "<th>Aktionen</th></tr>";
      $ausgabe .= "</thead>";
      $ausgabe .= "<tbody id=\"cms_gruppenliste\">";
      $ausgabe .= cms_gruppen_verwaltung_listeausgeben_schuljahr($dbs, $name, $bearbeiten, $loeschen, "-");
      $ausgabe .= "</tbody>";
    $ausgabe .= "</table>";

    cms_trennen($dbs);

		if ($anlegen) {
      if ((($name != "Gremien") && ($name != "Fachschaften")) || ($CMS_IMLN)) {
				$ausgabe .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Gruppen/".cms_textzulink($name)."/Neue_Gruppe_anlegen\">+ Neue Gruppe anlegen</a></p>";
			}
			else {
				$ausgabe .=  "<p><span class=\"cms_button_eingeschraenkt\">+ Neue Gruppe anlegen</span></p>";
			}
		}

	}
	else {
    $ausgabe .= cms_meldung('info', '<h3>Teilweise Rechte</h3><p>Möglicherweise gilt eine Berechtigung für einige Gruppen, in denen der Vorsitz oder die Aufsicht ausgeübt werden. Jedoch gibt es keine Berechtigung, alle Gruppen zu sehen. In diesem Fall muss die Bearbeitung über das Aufrufen der Gruppen erfolgen.</p><p><a class="cms_button" href="Schulhof/Nutzerkonto">zum Nutzerkonto</a></p>');
		$ausgabe .= cms_meldung_berechtigung();
	}
  return $ausgabe;
}


function cms_gruppen_verwaltung_listeausgeben_schuljahr($dbs, $name, $bearbeiten, $loeschen, $schuljahr) {
  global $CMS_SCHLUESSEL, $CMS_IMLN;

  $namek = cms_textzudb($name);

  $code = "";

  // Gruppen laden und auflisten
  if ($schuljahr == '-') {$sqlwhere = "schuljahr IS NULL";}
  else {$sqlwhere = "schuljahr = $schuljahr";}

  if ($namek == 'stufen') {
    $sortierkriterium = "reihenfolge ASC,";
    $zusatzspalten = "reihenfolge, tagebuch, gfs, ";
  }
  else {
    $sortierkriterium = "";
    $zusatzspalten = "";
  }

  $sql = "SELECT * FROM (SELECT $zusatzspalten id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, COUNT(person) AS mitglieder FROM $namek LEFT JOIN $namek"."mitglieder ON $namek.id = $namek"."mitglieder.gruppe WHERE $sqlwhere GROUP BY $namek.id) AS x ORDER BY $sortierkriterium bezeichnung ASC";

  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $code .= "<tr>";
      $code .= "<td><img src=\"res/gruppen/klein/".$daten['icon']."\"></td>";
      $code .= "<td>".$daten['bezeichnung']."</td>";
      $code .= "<td>".$daten['mitglieder']."</td>";
      // Vorsitzende ausgeben
      $sql = "SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN $namek"."vorsitz ON personen.id = $namek"."vorsitz.person WHERE gruppe = ".$daten['id'].") AS x ORDER BY nachname ASC, vorname ASC";
      $vorsitz = "";
      if ($anfrage2 = $dbs->query($sql)) {
        while ($daten2 = $anfrage2->fetch_assoc()) {
          $vorsitz .= ", ".cms_generiere_anzeigename($daten2['vorname'], $daten2['nachname'], $daten2['titel']);
        }
        $anfrage2->free();
      }
      if (strlen($vorsitz) > 0) {$vorsitz = substr($vorsitz, 2);}
      $code .= "<td>".$vorsitz."</td>";
      // Aufsichten ausgeben
      $sql = "SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN $namek"."aufsicht ON personen.id = $namek"."aufsicht.person WHERE gruppe = ".$daten['id'].") AS x ORDER BY nachname ASC, vorname ASC";
      $aufsicht = "";
      if ($anfrage2 = $dbs->query($sql)) {
        while ($daten2 = $anfrage2->fetch_assoc()) {
          $aufsicht .= ", ".cms_generiere_anzeigename($daten2['vorname'], $daten2['nachname'], $daten2['titel']);
        }
        $anfrage2->free();
      }
      if (strlen($aufsicht) > 0) {$aufsicht = substr($aufsicht, 2);}
      $code .= "<td>".$aufsicht."</td><td>";

      if ($daten['sichtbar'] == 0) {$code .= "Mitglieder";}
      if ($daten['sichtbar'] == 1) {$code .= "Lehrer";}
      if ($daten['sichtbar'] == 2) {$code .= "Lehrer und Verwaltung";}
      if ($daten['sichtbar'] == 3) {$code .= "Schulhof";}

      $code .= "</td>";
      if ($name == "Stufen") {
        $code .= "<td>";
          if ($daten['tagebuch'] == 1) {$code .= cms_generiere_hinweisicon('tagebuch', 'Tagebuch aktiv')." ";}
            if ($daten['gfs'] == 1) {$code .= cms_generiere_hinweisicon('gfs', 'GFS-Verwaltung aktiv');}
        $code .= "</td>";
      }
      $code .= "<td>";
      if ($bearbeiten) {
        $code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_gruppen_bearbeiten_vorbereiten('$name', ".$daten['id'].")\"><span class=\"cms_hinweis\">Gruppe bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
      }

      if ($loeschen) {
        if (($CMS_IMLN && (($namek == "gremien") || ($namek == "fachschaften"))) || (($namek != "gremien") && ($namek != "fachschaften"))) {
          $code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_gruppen_loeschen_anzeigen('$name', '".$daten['bezeichnung']."', ".$daten['id'].")\"><span class=\"cms_hinweis\">Gruppe löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
        }
        else {
          $code .= "<span class=\"cms_aktion_klein cms_button_eingeschraenkt\"><span class=\"cms_hinweis\">Gruppe löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
        }
      }
      $code .= "</td></tr>";
    }
    $anfrage->free();
  }

  $spalten = 7;
  if ($name == "Stufen") {$spalten++;}
  if (strlen($code) == 0) {$code = "<tr><td class=\"cms_notiz\" colspan=\"$spalten\">- keine Datensätze gefunden -</td></tr>";}

  return $code;
}
?>
