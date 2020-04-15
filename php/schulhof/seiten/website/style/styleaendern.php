<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Style ändern</h1>

<?php
if (cms_r("website.styleändern")) {
  if ($CMS_EINSTELLUNGEN['Website Darkmode'] == 0) {
    $meldetext = "<h4>Dunkler Modus</h4><p>Viele Betriebssysteme bieten einen Dunklen Modus, da dieser für manche angenehmer zum Lesen erscheint. Dieser Modus ist aktuell <b>deaktiviert</b>.";
    if (cms_r("schulhof.verwaltung.einstellungen")) {
      $meldetext .= " Er kann in den Allgemeinen Einstellungen aktiviert werden.</p><p><a href=\"Schulhof/Verwaltung/Allgemeine_Einstellungen\" class=\"cms_button\">zu den Allgemeinen Einstellungen</a>";
    }
    $meldetext .= "</p>";
    echo cms_meldung("info", $meldetext);
  }

  // Style-Werte laden
  $styles = array();
  $sql = $dbs->prepare("SELECT * FROM style");
  if ($sql->execute()) {
    $ergebnis = $sql->get_result();
    while ($e = $ergebnis->fetch_assoc()) {
      $styles[$e['name']] = $e;
    }
  }
  $sql->close();

  //print_r($styles);

  $code = "<h2>Hauptwerte</h2>";
  $code .= "<p class=\"cms_notiz\">Hauptwerte können in den übrigen Sektionen verwendet werden.</p>";

  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Schriftart:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_schriftart", $styles)."</td></tr>";
  $code .= "<tr><th>Schriftgröße:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_schriftgroesse", $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe positiv:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_schriftfarbepositiv", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_schriftfarbepositiv", $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe negativ:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_schriftfarbenegativ", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_schriftfarbenegativ", $styles)."</td></tr>";
  $code .= "<tr><th>Absatz auf der Website:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_absatzwebsite", $styles)."</td></tr>";
  $code .= "<tr><th>Absatz auf dem Schulhof:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_absatzschulhof", $styles)."</td></tr>";
  $code .= "<tr><th>Absatz nach den Brotkrumen:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_absatzbrotkrumen", $styles)."</td></tr>";
  $code .= "<tr><th>Zeilenhöhe auf der Website:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_zeilenhoehewebsite", $styles)."</td></tr>";
  $code .= "<tr><th>Zeilenhöhe auf dem Schulhof:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_zeilenhoeheschulhof", $styles)."</td></tr>";
  $code .= "<tr><th>Seitenbreite:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_seitenbreite", $styles)."</td></tr>";
  $code .= "<tr><th>Seitenhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_hintergrund", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_hintergrund", $styles)."</td></tr>";
  $code .= "<tr><th>Körperhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_koerperhintergrund", $styles)."</td><td>";
      $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_koerperhintergrund", $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Abstufung 1:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_abstufung1", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_abstufung1", $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Abstufung 2:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_abstufung2", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_abstufung2", $styles)."</td></tr>";
  $code .= "<tr><th>Themafarbe 1:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_thema1", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_thema1", $styles)."</td></tr>";
  $code .= "<tr><th>Themafarbe 2:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_thema2", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_thema2", $styles)."</td></tr>";
  $code .= "<tr><th>Themafarbe 3:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_thema3", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_thema3", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Erfolg Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldungerfolghinter", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldungerfolghinter", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Erfolg Akzent:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldungerfolgakzent", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldungerfolgakzent", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Warnung Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldungwarnunghinter", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldungwarnunghinter", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Warnung Akzent:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldungwarnungakzent", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldungwarnungakzent", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Fehler Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldungfehlerhinter", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldungfehlerhinter", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Fehler Akzent:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldungfehlerakzent", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldungfehlerakzent", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Information Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldunginfohinter", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldunginfohinter", $styles)."</td></tr>";
  $code .= "<tr><th>Meldung Information Akzent:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_meldunginfoakzent", $styles)."</td><td>";
    $code.= cms_generiere_stylefarbwahl("cms_style_d_haupt_meldunginfoakzent", $styles)."</td></tr>";
  $code .= "<tr><th>Sehr kleiner Radius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_radiussehrklein", $styles)."</td></tr>";
  $code .= "<tr><th>Kleiner Radius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_radiusklein", $styles)."</td></tr>";
  $code .= "<tr><th>Mittelerer Radius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_radiusmittel", $styles)."</td></tr>";
  $code .= "<tr><th>Großer Radius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_radiusgross", $styles)."</td></tr>";
  $code .= "<tr><th>Sehr großer Radius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_haupt_radiussehrgross", $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Notizen:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_haupt_notizschrift", $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_haupt_notizschrift", $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $aliashfarbe = "<option value=\"-\">kein Farbalias</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_schriftfarbepositiv\">Schriftfarbe positiv</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_schriftfarbenegativ\">Schriftfarbe negativ</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_koerperhintergrund\">Körperhintergrund</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_hintergrund\">Hintergrund</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_abstufung1\">Hintergrund Abstufung 1</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_abstufung2\">Hintergrund Abstufung 2</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_thema1\">Themafarbe 1</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_thema2\">Themafarbe 2</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_thema3\">Themafarbe 3</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldungerfolghinter\">Erfolg Hintergrund</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldungerfolgakzent\">Erfolg Akzent</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldungwarnunghinter\">Warnung Hintergrund</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldungwarnungakzent\">Warnung Akzent</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldungfehlerhinter\">Fehler Hintergrund</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldungfehlerakzent\">Fehler Akzent</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldunginfohinter\">Information Hintergrund</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_meldunginfoakzent\">Information Akzent</option>";
  $aliashfarbe .= "<option value=\"cms_style_h_haupt_notizschrift\">Notizen</option>";

  $aliasdfarbe = "<option value=\"-\">kein Farbalias</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_schriftfarbepositiv\">Schriftfarbe positiv</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_schriftfarbenegativ\">Schriftfarbe negativ</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_koerperhintergrund\">Körperhintergrund</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_hintergrund\">Hintergrund</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_abstufung1\">Hintergrund Abstufung 1</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_abstufung2\">Hintergrund Abstufung 2</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_thema1\">Themafarbe 1</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_thema2\">Themafarbe 2</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_thema3\">Themafarbe 3</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldungerfolghinter\">Erfolg Hintergrund</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldungerfolgakzent\">Erfolg Akzent</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldungwarnunghinter\">Warnung Hintergrund</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldungwarnungakzent\">Warnung Akzent</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldungfehlerhinter\">Fehler Hintergrund</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldungfehlerakzent\">Fehler Akzent</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldunginfohinter\">Information Hintergrund</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_meldunginfoakzent\">Information Akzent</option>";
  $aliasdfarbe .= "<option value=\"cms_style_d_haupt_notizschrift\">Notizen</option>";

  $aliaswerte = "<option value=\"-\">kein Wertalias</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_schriftart\">Schriftart</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_schriftgroesse\">Schriftgröße</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_absatzwebsite\">Absatz auf der Website</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_absatzschulhof\">Absatz auf dem Schulhof</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_absatzbrotkrumen\">Absatz nach den Brotkrumen</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_zeilenhoehewebsite\">Zeilenhöhe auf der Website</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_zeilenhoeheschulhof\">Zeilenhöhe auf dem Schulhof</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_seitenbreite\">Seitenbreite</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_radiussehrklein\">Sehr kleiner Radius</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_radiusklein\">Kleiner Radius</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_radiusmittel\">Mittlerer Radius</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_radiusgross\">Großer Radius</option>";
  $aliaswerte .= "<option value=\"cms_style_haupt_radiussehrgross\">Sehr großer Radius</option>";

  $aliaspositionierung = "<option value=\"inherit\">inherit</option>";
  $aliaspositionierung .= "<option value=\"static\">static</option>";
  $aliaspositionierung .= "<option value=\"relative\">relative</option>";
  $aliaspositionierung .= "<option value=\"absolute\">absolute</option>";
  $aliaspositionierung .= "<option value=\"fixed\">fixed</option>";

  $aliasanzeige = "<option value=\"inherit\">inherit</option>";
  $aliasanzeige .= "<option value=\"inline\">inline</option>";
  $aliasanzeige .= "<option value=\"block\">block</option>";
  $aliasanzeige .= "<option value=\"inline-block\">inline-block</option>";
  $aliasanzeige .= "<option value=\"list-item\">list-item</option>";
  $aliasanzeige .= "<option value=\"run-in\">run-in</option>";
  $aliasanzeige .= "<option value=\"inline-table\">inline-table</option>";
  $aliasanzeige .= "<option value=\"table\">table</option>";
  $aliasanzeige .= "<option value=\"table-caption\">table-caption</option>";
  $aliasanzeige .= "<option value=\"table-cell\">table-cell</option>";
  $aliasanzeige .= "<option value=\"table-column\">table-column</option>";
  $aliasanzeige .= "<option value=\"table-columns-group\">table-columns-group</option>";
  $aliasanzeige .= "<option value=\"table-footer-group\">table-footer-group</option>";
  $aliasanzeige .= "<option value=\"table-header-group\">table-header-group</option>";
  $aliasanzeige .= "<option value=\"table-row\">table-row</option>";
  $aliasanzeige .= "<option value=\"table-row-group\">table-row-group</option>";
  $aliasanzeige .= "<option value=\"flex\">flex</option>";
  $aliasanzeige .= "<option value=\"none\">none</option>";

  $aliasdicke = "<option value=\"inherit\">inherit</option>";
  $aliasdicke .= "<option value=\"normal\">normal</option>";
  $aliasdicke .= "<option value=\"bold\">bold</option>";
  $aliasdicke .= "<option value=\"bolder\">bolder</option>";
  $aliasdicke .= "<option value=\"lighter\">lighter</option>";

  $code = "<h2>Links</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Schrift:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_link_schrift", $styles).cms_generiere_styleselect("cms_style_h_link_schrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_link_schrift", $styles).cms_generiere_styleselect("cms_style_d_link_schrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schrift Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_link_schrifthover", $styles).cms_generiere_styleselect("cms_style_h_link_schrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_link_schrifthover", $styles).cms_generiere_styleselect("cms_style_d_link_schrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Buttons</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_button_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_button_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_button_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_button_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_button_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_button_hintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_button_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_button_hintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schrift:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_button_schrift", $styles).cms_generiere_styleselect("cms_style_h_button_schrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_button_schrift", $styles).cms_generiere_styleselect("cms_style_d_button_schrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schrift Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_button_schrifthover", $styles).cms_generiere_styleselect("cms_style_h_button_schrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_button_schrifthover", $styles).cms_generiere_styleselect("cms_style_d_button_schrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Abegrundete Ecken:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_button_rundeecken", $styles).cms_generiere_styleselect("cms_style_button_rundeecken_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Formulare</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Formularhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_formular_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_formular_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_formular_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_formular_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Formularfeldhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_formular_feldhintergrund", $styles).cms_generiere_styleselect("cms_style_h_formular_feldhintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_formular_feldhintergrund", $styles).cms_generiere_styleselect("cms_style_d_formular_feldhintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Formularfeldhintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_formular_feldhoverhintergrund", $styles).cms_generiere_styleselect("cms_style_h_formular_feldhoverhintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_formular_feldhoverhintergrund", $styles).cms_generiere_styleselect("cms_style_d_formular_feldhoverhintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Formularfeldhintergrund Focus:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_formular_feldfocushintergrund", $styles).cms_generiere_styleselect("cms_style_h_formular_feldfocushintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_formular_feldfocushintergrund", $styles).cms_generiere_styleselect("cms_style_d_formular_feldfocushintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Kopfzeile</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Außenabstand:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kopfzeile_aussenabstand", $styles).cms_generiere_styleselect("cms_style_kopfzeile_aussenabstand_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Positionierung:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kopfzeile_positionierung", $aliaspositionierung, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Abstand von oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kopfzeile_abstandvonoben", $styles).cms_generiere_styleselect("cms_style_kopfzeile_abstandvonoben_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Höhe:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kopfzeile_hoehe", $styles).cms_generiere_styleselect("cms_style_kopfzeile_hoehe_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand des Platzhalters:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kopfzeile_platzhalter", $styles).cms_generiere_styleselect("cms_style_kopfzeile_platzhalter_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kopfzeile_linienstaerkeunten", $styles).cms_generiere_styleselect("cms_style_kopfzeile_linienstaerkeunten_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_buttonhintergrund", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_buttonhintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_buttonhintergrund", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_buttonhintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonschriftfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_buttonschrift", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_buttonschrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_buttonschrift", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_buttonschrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonhintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_buttonhintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_buttonhintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_buttonhintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_buttonhintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonschriftfarbe Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_buttonschrifthover", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_buttonschrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_buttonschrifthover", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_buttonschrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Suchergebnisse Hintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_suchehintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_suchehintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_suchehintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_suchehintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schattenausmaße:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kopfzeile_schattenausmasse", $styles).cms_generiere_styleselect("cms_style_kopfzeile_schattenausmasse_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Schattenfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kopfzeile_schattenfarbe", $styles).cms_generiere_styleselect("cms_style_h_kopfzeile_schattenfarbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kopfzeile_schattenfarbe", $styles).cms_generiere_styleselect("cms_style_d_kopfzeile_schattenfarbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Logo</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Schriftfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_logo_schriftfarbe", $styles).cms_generiere_styleselect("cms_style_h_logo_schriftfarbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_logo_schriftfarbe", $styles).cms_generiere_styleselect("cms_style_d_logo_schriftfarbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Breite:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_logo_breite", $styles).cms_generiere_styleselect("cms_style_logo_breite_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Anzeige:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_logo_anzeige", $aliasanzeige, $styles, 'wert')."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Hauptnavigation</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Abstand von oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_abstandvonoben", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_abstandvonoben_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand von unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_abstandvonunten", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_abstandvonunten_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand von links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_abstandvonlinks", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_abstandvonlinks_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand von rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_abstandvonrechts", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_abstandvonrechts_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Anzeige der Kategorien:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_hauptnavigation_anzeigekategorie", $aliasanzeige, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Außenabstand der Kategorien:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_aussenabstandkategorie", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_aussenabstandkategorie_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Kategorieschriftfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_hauptnavigation_kategoriefarbe", $styles).cms_generiere_styleselect("cms_style_h_hauptnavigation_kategoriefarbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_hauptnavigation_kategoriefarbe", $styles).cms_generiere_styleselect("cms_style_d_hauptnavigation_kategoriefarbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Kategoriehintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_hauptnavigation_kategoriehintergrund", $styles).cms_generiere_styleselect("cms_style_h_hauptnavigation_kategoriehintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_hauptnavigation_kategoriehintergrund", $styles).cms_generiere_styleselect("cms_style_d_hauptnavigation_kategoriehintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Kategorieschriftfarbe Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_hauptnavigation_kategoriefarbehover", $styles).cms_generiere_styleselect("cms_style_h_hauptnavigation_kategoriefarbehover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_hauptnavigation_kategoriefarbehover", $styles).cms_generiere_styleselect("cms_style_d_hauptnavigation_kategoriefarbehover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Kategoriehintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_hauptnavigation_kategoriehintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_hauptnavigation_kategoriehintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_hauptnavigation_kategoriehintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_hauptnavigation_kategoriehintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Akzentfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_hauptnavigation_akzentfarbe", $styles).cms_generiere_styleselect("cms_style_h_hauptnavigation_akzentfarbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_hauptnavigation_akzentfarbe", $styles).cms_generiere_styleselect("cms_style_d_hauptnavigation_akzentfarbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Höhe der Kategoriebuttons:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_kategoriehoehe", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_kategoriehoehe_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Kategorieradius oben links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_kategorieradiusol", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_kategorieradiusol_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Kategorieradius oben rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_kategorieradiusor", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_kategorieradiusor_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Kategorieradius unten links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_kategorieradiusul", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_kategorieradiusul_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Kategorieradius unten rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_kategorieradiusur", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_kategorieradiusur_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Innenabstand in Kategoriebuttons:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hauptnavigation_kategorieinnenabstand", $styles).cms_generiere_styleselect("cms_style_hauptnavigation_kategorieinnenabstand_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Navigationselemente</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Mobilnavigation Iconhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_mobilnavigation_iconhintergrund", $styles).cms_generiere_styleselect("cms_style_h_mobilnavigation_iconhintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_mobilnavigation_iconhintergrund", $styles).cms_generiere_styleselect("cms_style_d_mobilnavigation_iconhintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Mobilnavigation Iconhintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_mobilnavigation_iconhintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_mobilnavigation_iconhintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_mobilnavigation_iconhintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_mobilnavigation_iconhintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Unternavigation Abstand von oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_unternavigation_abstandvonoben", $styles).cms_generiere_styleselect("cms_style_unternavigation_abstandvonoben_alias", $aliaswerte, $styles)."</td></tr>";
$code .= "<tr><th>Anzeige der Suche:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_suche_anzeige", $aliasanzeige, $styles, 'wert')."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Schulhofnavigation</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Abstand von oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_schulhofnavigation_abstandvonoben", $styles).cms_generiere_styleselect("cms_style_schulhofnavigation_abstandvonoben_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand von unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_schulhofnavigation_abstandvonunten", $styles).cms_generiere_styleselect("cms_style_schulhofnavigation_abstandvonunten_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand von links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_schulhofnavigation_abstandvonlinks", $styles).cms_generiere_styleselect("cms_style_schulhofnavigation_abstandvonlinks_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Abstand von rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_schulhofnavigation_abstandvonrechts", $styles).cms_generiere_styleselect("cms_style_schulhofnavigation_abstandvonrechts_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Fußzeile</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_fusszeile_linienstaerkeoben", $styles).cms_generiere_styleselect("cms_style_fusszeile_linienstaerkeoben_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonhintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_buttonhintergrund", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_buttonhintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_buttonhintergrund", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_buttonhintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonschriftfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_buttonschrift", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_buttonschrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_buttonschrift", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_buttonschrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonhintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_buttonhintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_buttonhintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_buttonhintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_buttonhintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonschriftfarbe Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_buttonschrifthover", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_buttonschrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_buttonschrifthover", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_buttonschrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Linkschriftfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_linkschrift", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_linkschrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_linkschrift", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_linkschrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Linkschriftfarbe Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_fusszeile_linkschrifthover", $styles).cms_generiere_styleselect("cms_style_h_fusszeile_linkschrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_fusszeile_linkschrifthover", $styles).cms_generiere_styleselect("cms_style_d_fusszeile_linkschrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Galerien</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Button:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_galerie_button", $styles).cms_generiere_styleselect("cms_style_h_galerie_button_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_galerie_button", $styles).cms_generiere_styleselect("cms_style_d_galerie_button_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Button Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_galerie_buttonhover", $styles).cms_generiere_styleselect("cms_style_h_galerie_buttonhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_galerie_buttonhover", $styles).cms_generiere_styleselect("cms_style_d_galerie_buttonhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Button Aktiv:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_galerie_buttonaktiv", $styles).cms_generiere_styleselect("cms_style_h_galerie_buttonaktiv_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_galerie_buttonaktiv", $styles).cms_generiere_styleselect("cms_style_d_galerie_buttonaktiv_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonbreite:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_galerie_buttonbreite", $styles).cms_generiere_styleselect("cms_style_galerie_buttonbreite_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Buttonhöhe:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_galerie_buttonhoehe", $styles).cms_generiere_styleselect("cms_style_galerie_buttonhoehe_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Zeitdiagramm</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Balken:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_zeitdiagramm_balken", $styles).cms_generiere_styleselect("cms_style_h_zeitdiagramm_balken_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_zeitdiagramm_balken", $styles).cms_generiere_styleselect("cms_style_d_zeitdiagramm_balken_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Balken Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_zeitdiagramm_balkenhover", $styles).cms_generiere_styleselect("cms_style_h_zeitdiagramm_balkenhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_zeitdiagramm_balkenhover", $styles).cms_generiere_styleselect("cms_style_d_zeitdiagramm_balkenhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_zeitdiagramm_radiusoben", $styles).cms_generiere_styleselect("cms_style_zeitdiagramm_radiusoben_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_zeitdiagramm_radiusunten", $styles).cms_generiere_styleselect("cms_style_zeitdiagramm_radiusunten_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Auszeichnungen</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Außenabstand:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_auszeichnung_aussenabstand", $styles).cms_generiere_styleselect("cms_style_auszeichnung_aussenabstand_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_auszeichnung_radius", $styles).cms_generiere_styleselect("cms_style_auszeichnung_radius_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_auszeichnung_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_auszeichnung_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_auszeichnung_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_auszeichnung_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schrift:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_auszeichnung_schrift", $styles).cms_generiere_styleselect("cms_style_h_auszeichnung_schrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_auszeichnung_schrift", $styles).cms_generiere_styleselect("cms_style_d_auszeichnung_schrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_auszeichnung_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_auszeichnung_hintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_auszeichnung_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_auszeichnung_hintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schrift Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_auszeichnung_schrifthover", $styles).cms_generiere_styleselect("cms_style_h_auszeichnung_schrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_auszeichnung_schrifthover", $styles).cms_generiere_styleselect("cms_style_d_auszeichnung_schrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Reiter</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_reiter_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_reiter_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_reiter_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_reiter_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Farbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_reiter_farbe", $styles).cms_generiere_styleselect("cms_style_h_reiter_farbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_reiter_farbe", $styles).cms_generiere_styleselect("cms_style_d_reiter_farbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_reiter_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_reiter_hintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_reiter_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_reiter_hintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Farbe Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_reiter_farbehover", $styles).cms_generiere_styleselect("cms_style_h_reiter_farbehover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_reiter_farbehover", $styles).cms_generiere_styleselect("cms_style_d_reiter_farbehover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Aktiv:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_reiter_hintergrundaktiv", $styles).cms_generiere_styleselect("cms_style_h_reiter_hintergrundaktiv_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_reiter_hintergrundaktiv", $styles).cms_generiere_styleselect("cms_style_d_reiter_hintergrundaktiv_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Farbe Aktiv:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_reiter_farbeaktiv", $styles).cms_generiere_styleselect("cms_style_h_reiter_farbeaktiv_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_reiter_farbeaktiv", $styles).cms_generiere_styleselect("cms_style_d_reiter_farbeaktiv_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_reiter_radiusoben", $styles).cms_generiere_styleselect("cms_style_reiter_radiusoben_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_reiter_radiusunten", $styles).cms_generiere_styleselect("cms_style_reiter_radiusunten_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Kalenderblatt klein</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Linienfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_linienfarbe", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_linienfarbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_linienfarbe", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_linienfarbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkeobenmonat", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkeobenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkeuntenmonat", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkeuntenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkelinksmonat", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkelinksmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkerechtsmonat", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkerechtsmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Monat:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_hintergrundmonat", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_hintergrundmonat_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_hintergrundmonat", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_hintergrundmonat_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Monat:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_farbemonat", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_farbemonat_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_farbemonat", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_farbemonat_alias", $aliasdfarbe, $styles)."</td></tr>";
    $code .= "<tr><th>Schriftdicke Monat:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kalenderklein_schriftdickemonat", $aliasdicke, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Eckenradius Monat oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_radiusobenmonat", $styles).cms_generiere_styleselect("cms_style_kalenderklein_radiusobenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius Monat unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_radiusuntenmonat", $styles).cms_generiere_styleselect("cms_style_kalenderklein_radiusuntenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkeobentagbez", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkeobentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkeuntentagbez", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkeuntentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkelinkstagbez", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkelinkstagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkerechtstagbez", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkerechtstagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Tagbezeichnung:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_hintergrundtagbez", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_hintergrundtagbez_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_hintergrundtagbez", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_hintergrundtagbez_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Tagbezeichnung:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_farbetagbez", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_farbetagbez_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_farbetagbez", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_farbetagbez_alias", $aliasdfarbe, $styles)."</td></tr>";
    $code .= "<tr><th>Schriftdicke Tagbezeichnung:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kalenderklein_schriftdicketagbez", $aliasdicke, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagbezeichnung oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_radiusobentagbez", $styles).cms_generiere_styleselect("cms_style_kalenderklein_radiusobentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagbezeichnung unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_radiusuntentagbez", $styles).cms_generiere_styleselect("cms_style_kalenderklein_radiusuntentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkeobentagnr", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkeobentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkeuntentagnr", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkeuntentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkelinkstagnr", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkelinkstagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_linienstaerkerechtstagnr", $styles).cms_generiere_styleselect("cms_style_kalenderklein_linienstaerkerechtstagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Tagesdatum:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_hintergrundtagnr", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_hintergrundtagnr_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_hintergrundtagnr", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_hintergrundtagnr_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Tagesdatum:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalenderklein_farbetagnr", $styles).cms_generiere_styleselect("cms_style_h_kalenderklein_farbetagnr_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalenderklein_farbetagnr", $styles).cms_generiere_styleselect("cms_style_d_kalenderklein_farbetagnr_alias", $aliasdfarbe, $styles)."</td></tr>";
    $code .= "<tr><th>Schriftdicke Tagesdatum:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kalenderklein_schriftdicketagnr", $aliasdicke, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagesdatum oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_radiusobentagnr", $styles).cms_generiere_styleselect("cms_style_kalenderklein_radiusobentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagesdatum unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalenderklein_radiusuntentagnr", $styles).cms_generiere_styleselect("cms_style_kalenderklein_radiusuntentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Kalenderblatt groß</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Linienfarbe:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_linienfarbe", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_linienfarbe_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_linienfarbe", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_linienfarbe_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkeobenmonat", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkeobenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkeuntenmonat", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkeuntenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkelinksmonat", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkelinksmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Monat rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkerechtsmonat", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkerechtsmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Monat:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_hintergrundmonat", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_hintergrundmonat_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_hintergrundmonat", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_hintergrundmonat_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Monat:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_farbemonat", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_farbemonat_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_farbemonat", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_farbemonat_alias", $aliasdfarbe, $styles)."</td></tr>";
    $code .= "<tr><th>Schriftdicke Monat:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kalendergross_schriftdickemonat", $aliasdicke, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Eckenradius Monat oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_radiusobenmonat", $styles).cms_generiere_styleselect("cms_style_kalendergross_radiusobenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius Monat unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_radiusuntenmonat", $styles).cms_generiere_styleselect("cms_style_kalendergross_radiusuntenmonat_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkeobentagbez", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkeobentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkeuntentagbez", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkeuntentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkelinkstagbez", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkelinkstagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagbezeichnung rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkerechtstagbez", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkerechtstagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Tagbezeichnung:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_hintergrundtagbez", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_hintergrundtagbez_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_hintergrundtagbez", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_hintergrundtagbez_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Tagbezeichnung:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_farbetagbez", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_farbetagbez_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_farbetagbez", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_farbetagbez_alias", $aliasdfarbe, $styles)."</td></tr>";
    $code .= "<tr><th>Schriftdicke Tagbezeichnung:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kalendergross_schriftdicketagbez", $aliasdicke, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagbezeichnung oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_radiusobentagbez", $styles).cms_generiere_styleselect("cms_style_kalendergross_radiusobentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagbezeichnung unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_radiusuntentagbez", $styles).cms_generiere_styleselect("cms_style_kalendergross_radiusuntentagbez_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkeobentagnr", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkeobentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkeuntentagnr", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkeuntentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum links:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkelinkstagnr", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkelinkstagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Linienstärke Tagesdatum rechts:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_linienstaerkerechtstagnr", $styles).cms_generiere_styleselect("cms_style_kalendergross_linienstaerkerechtstagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Hintergrund Tagesdatum:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_hintergrundtagnr", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_hintergrundtagnr_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_hintergrundtagnr", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_hintergrundtagnr_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Tagesdatum:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_kalendergross_farbetagnr", $styles).cms_generiere_styleselect("cms_style_h_kalendergross_farbetagnr_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_kalendergross_farbetagnr", $styles).cms_generiere_styleselect("cms_style_d_kalendergross_farbetagnr_alias", $aliasdfarbe, $styles)."</td></tr>";
    $code .= "<tr><th>Schriftdicke Tagesdatum:</th><td colspan=\"2\">".cms_generiere_styleselect("cms_style_kalendergross_schriftdicketagnr", $aliasdicke, $styles, 'wert')."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagesdatum oben:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_radiusobentagnr", $styles).cms_generiere_styleselect("cms_style_kalendergross_radiusobentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius Tagesdatum unten:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_kalendergross_radiusuntentagnr", $styles).cms_generiere_styleselect("cms_style_kalendergross_radiusuntentagnr_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Zugehörig</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Hintergrund Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_zugehoerig_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_h_zugehoerig_hintergrundhover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_zugehoerig_hintergrundhover", $styles).cms_generiere_styleselect("cms_style_d_zugehoerig_hintergrundhover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schriftfarbe Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_zugehoerig_farbehover", $styles).cms_generiere_styleselect("cms_style_h_zugehoerig_farbehover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_zugehoerig_farbehover", $styles).cms_generiere_styleselect("cms_style_d_zugehoerig_farbehover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_zugehoerig_radius", $styles).cms_generiere_styleselect("cms_style_zugehoerig_radius_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Hinweise</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Hintergrund:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_hinweis_hintergrund", $styles).cms_generiere_styleselect("cms_style_h_hinweis_hintergrund_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_hinweis_hintergrund", $styles).cms_generiere_styleselect("cms_style_d_hinweis_hintergrund_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Eckenradius:</th><td colspan=\"2\">".cms_generiere_styleinput("cms_style_hinweis_radius", $styles).cms_generiere_styleselect("cms_style_hinweis_radius_alias", $aliaswerte, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Neuigkeiten</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Schrift:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_neuigkeit_schrift", $styles).cms_generiere_styleselect("cms_style_h_neuigkeit_schrift_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_neuigkeit_schrift", $styles).cms_generiere_styleselect("cms_style_d_neuigkeit_schrift_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Schrift Hover:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_neuigkeit_schrifthover", $styles).cms_generiere_styleselect("cms_style_h_neuigkeit_schrifthover_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_neuigkeit_schrifthover", $styles).cms_generiere_styleselect("cms_style_d_neuigkeit_schrifthover_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  $code = "<h2>Chat</h2>";
  $code .= "<table class=\"cms_liste\" style=\"table-layout=fixed;\">";
  $code .= "<tr><th style=\"width:30%;\"></th><th style=\"width:35%;\">Heller Modus</th><th style=\"width:35%;\">Dunkler Modus</th></tr>";
  $code .= "<tr><th>Eigene Sprechblase:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_chat_eigen", $styles).cms_generiere_styleselect("cms_style_h_chat_eigen_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_chat_eigen", $styles).cms_generiere_styleselect("cms_style_d_chat_eigen_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "<tr><th>Gegenüber Sprechblase:</th><td>".cms_generiere_stylefarbwahl("cms_style_h_chat_gegenueber", $styles).cms_generiere_styleselect("cms_style_h_chat_gegenueber_alias", $aliashfarbe, $styles)."</td>";
    $code .= "<td>".cms_generiere_stylefarbwahl("cms_style_d_chat_gegenueber", $styles).cms_generiere_styleselect("cms_style_d_chat_gegenueber_alias", $aliasdfarbe, $styles)."</td></tr>";
  $code .= "</table>";
  echo $code;

  echo "<p><span class=\"cms_button\" onclick=\"cms_website_style_aendern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Website/Style_ändern\">Abbrechen</a></p>";
}

?>

</div>
<div class="cms_clear"></div>
