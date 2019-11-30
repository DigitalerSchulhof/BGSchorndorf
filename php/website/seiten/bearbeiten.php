<div id="cms_website_bearbeiten_o">
<div id="cms_website_bearbeiten_m">
  <div id="cms_website_bearbeiten_i">
    <div class="cms_spalte_i">
    <?php
    $code = "";
    $code .= "<div id=\"cms_aktivitaet_out\"><div id=\"cms_aktivitaet_in\"></div></div>";
    if ((isset($CMS_SEITENDETAILS['id'])) && (($CMS_SEITENDETAILS['art'] == 's') || ($CMS_SEITENDETAILS['art'] == 'm'))) {
      $code .= "<table class=\"cms_bearbeitenwahl\">";
      $code .= "<tr><th>Modus</th><th>Version</th><th>Aktionen</th></tr>";
      $code .= "<tr>";
        if (isset($CMS_URL[1])) {
          if ($CMS_URL[1] == 'Bearbeiten') {
            $bearbeitenmaktiv = " cms_toggle_aktiv";
            $betrachtenmaktiv = "";
          }
          else {
            $CMS_URL[1] = 'Seiten';
            $bearbeitenmaktiv = "";
            $betrachtenmaktiv = " cms_toggle_aktiv";
          }
        }
        else {
          $CMS_URL[1] = 'Seiten';
          $bearbeitenmaktiv = "";
          $betrachtenmaktiv = " cms_toggle_aktiv";
        }

        if (isset($CMS_URL[2])) {
          if ($CMS_URL[2] == 'Alt') {
            $altedaten = " cms_toggle_aktiv";
            $aktuelledaten = "";
            $neuedaten = "";
          }
          else if ($CMS_URL[2] == 'Neu') {
            $altedaten = "";
            $aktuelledaten = "";
            $neuedaten = " cms_toggle_aktiv";
          }
          else {
            $CMS_URL[2] = 'Aktuell';
            $altedaten = "";
            $aktuelledaten = " cms_toggle_aktiv";
            $neuedaten = "";
          }
        }
        else {
          $CMS_URL[2] = 'Aktuell';
          $altedaten = "";
          $aktuelledaten = " cms_toggle_aktiv";
          $neuedaten = "";
        }


        $CMS_URLANHANG = implode('/', array_slice($CMS_URL,2));
        $code .= "<td><a class=\"cms_iconbutton$betrachtenmaktiv\" id=\"cms_button_website_betrachtungsmodus\" href=\"Website/Seiten/$CMS_URLANHANG\">Betrachten</a> ";
        $code .= "<a class=\"cms_iconbutton$bearbeitenmaktiv\" id=\"cms_button_website_bearbeitungsmodus\" href=\"Website/Bearbeiten/$CMS_URLANHANG\">Bearbeiten</a></td>";

        $CMS_URLANHANG = implode('/', array_slice($CMS_URL,3));
        $code .= "<td><a class=\"cms_iconbutton$altedaten\" id=\"cms_button_website_altedaten\" href=\"Website/".$CMS_URL[1]."/Alt/$CMS_URLANHANG\">Alte Daten</a> ";
        $code .= "<a class=\"cms_iconbutton$aktuelledaten\" id=\"cms_button_website_aktuelledaten\" href=\"Website/".$CMS_URL[1]."/Aktuell/$CMS_URLANHANG\">Aktuelle Daten</a> ";
        $code .= "<a class=\"cms_iconbutton$neuedaten\" id=\"cms_button_website_neuedaten\" href=\"Website/".$CMS_URL[1]."/Neu/$CMS_URLANHANG\">Neue Daten</a></td>";

        $code .= "<td>";
          if (($CMS_RECHTE['Website']['Dateien hochladen']) || ($CMS_RECHTE['Website']['Dateien umbenennen']) || ($CMS_RECHTE['Website']['Dateien löschen'])) {
            $code .= "<a class=\"cms_iconbutton cms_button_website_dateien\" href=\"Schulhof/Website/Dateien\">Dateien</a> ";
          }
          if (($CMS_RECHTE['Website']['Inhalte freigeben']) && ($CMS_URL[2] == 'Neu')) {
            $code .= "<span class=\"cms_iconbutton cms_button_website_freigeben\" onclick=\"cms_element_allefreigeben('".$CMS_SEITENDETAILS['id']."', '".$CMS_URL[2]."', '$CMS_ZUSATZ')\">Freigeben</span> ";
          }
          if ($CMS_RECHTE['Website']['Inhalte freigeben']) {
            $CMS_ZUSATZ = implode('/', array_slice($CMS_URL, 3));
            $code .= "<span class=\"cms_iconbutton cms_button_website_aktivieren\" onclick=\"cms_element_alleaktivieren('".$CMS_SEITENDETAILS['id']."', '".$CMS_URL[2]."', '$CMS_ZUSATZ')\">Aktivieren</span> ";
          }
          if ($CMS_RECHTE['Website']['Seiten bearbeiten']) {
            $code .= "<span class=\"cms_iconbutton cms_button_website_bearbeiten\" onclick=\"cms_schulhof_website_seite_bearbeiten_vorbereiten('".$CMS_SEITENDETAILS['id']."');\">Seite bearbeiten</span> ";
          }
          if ($CMS_RECHTE['Website']['Seiten löschen']) {
            $code .= "<span class=\"cms_iconbutton cms_button_nein cms_button_website_loeschen\" onclick=\"cms_schulhof_website_seite_loeschen_anzeigen('".$CMS_SEITENDETAILS['bezeichnung']."', '".$CMS_SEITENDETAILS['id']."');\">Seite löschen</span> ";
          }
        $code .= "</td>";
      $code .= "</tr></table>";
    }
    else if (($CMS_URL[1] == 'Datenschutz') || ($CMS_URL[1] == 'Impressum')) {
      $code = cms_meldung('info', '<h4>Generierte Seite</h4><p>Diese Seite ist gesetzlich vorgeschrieben und wird automatisch durch das System generiert. Auf diese Weise enthält sie alle Informationen, die auch gesetzlich vorgeschrieben sind, sodass unfreiwilliger Rechtsbruch verhindert wird.</p><p>Voraussetzung für die Verhinderung des Rechtsbruchs ist eine aktuelle Version des Schulhofs. Für die Aktualisierung des Schulhofs muss selbst Sorge getragen werden.</p>');
    }
    else if (($CMS_URL[1] == 'Blog') || ($CMS_URL[1] == 'Galerien') || ($CMS_URL[1] == 'Termine') || ($CMS_URL[1] == 'Voranmeldung') || ($CMS_URL[1] == 'Ferien')) {
      $code = cms_meldung('info', '<h4>Generierte Seite</h4><p>Diese Seite wird aus den Inhalten des Schulhofs generiert. Um den hier angezeigten Inhalt zu bearbeiten müssen die Daten im Schulhof verändert werden.</p>');
    }
    else {
      $code = cms_meldung('info', '<h4>Fehlerseite</h4><p>Diese Seite kann nicht bearbeitet werden.</p>');
    }
    echo $code;
    ?>
    </div>
  </div>
</div>
</div>
