<?php
function cms_downloadelemente($dbs, $art, $id, $gruppe = '-', $gruppenid = '-') {
  global $CMS_SCHLUESSEL;
  $code = "";

  $gk = cms_textzudb($gruppe);

  // Vorhandene Downloads laden
  $sql = "";
  $downloads = array();
  if ($id != '-') {
    if ($art == 'termine') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, dateiname, dateigroesse FROM terminedownloads WHERE termin = $id) AS x ORDER BY titel";
    }
    else if ($art == 'blogeintraege') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, dateiname, dateigroesse FROM blogeintragdownloads WHERE blogeintrag = $id) AS x ORDER BY titel";
    }
    else if ($art == 'blogintern') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, dateiname, dateigroesse FROM $gk"."blogeintragdownloads WHERE blogeintrag = $id) AS x ORDER BY titel";
    }
    else if ($art == 'terminintern') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, dateiname, dateigroesse FROM $gk"."termineinterndownloads WHERE termin = $id) AS x ORDER BY titel";
    }

    if (strlen($sql) > 0) {
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          array_push($downloads, $daten);
        }
        $anfrage->free();
      }
    }
  }


  $code .= "<div id=\"cms_downloads\">";
  $anzahl = 0;
  $ids = "";
  foreach ($downloads as $d) {
    $did = $d['id'];
    $code .= "<table class=\"cms_formular\" id=\"cms_download_$did\">";
      $code .= "<tr><th>Titel:</th><td colspan=\"4\"><input type=\"text\" name=\"cms_download_titel_".$did."\" id=\"cms_download_titel_".$did."\" value=\"".$d['titel']."\"></td></tr>";
      $code .= "<tr><th>Beschreibung:</th><td colspan=\"4\"><textarea name=\"cms_download_beschreibung_".$did."\" id=\"cms_download_beschreibung_".$did."\">".$d['beschreibung']."</textarea></td></tr>";
      // Datei
      $code .= "<tr><th>Datei:</th>";
      $code .= "<td colspan=\"4\"><input id=\"cms_download_datei_".$did."\" name=\"cms_download_datei_".$did."\" type=\"hidden\" value=\"".$d['pfad']."\">";
      $code .= "<p class=\"cms_notiz cms_vorschau\" id=\"cms_download_datei_".$did."_vorschau\"><span class=\"cms_datei_gewaehlt\">";
        $dateiname = explode('/', $d['pfad']);
        $dateiname = $dateiname[count($dateiname)-1];
        $endung = explode('.', $dateiname);
        $endung = $endung[count($endung)-1];
        $icon = cms_dateisystem_icon($endung);
        $code .= "<img src=\"res/dateiicons/klein/$icon\"> $dateiname</span></p>";
        if (($art != 'blogintern') && ($art != 'terminintern')) {
          $code .= "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'website', '-', 'website', 'cms_download_datei_".$did."', 'download')\">Datei auswählen</span></p>";
        }
        else {
          $code .= "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'gruppe', '$gruppenid', 'schulhof/gruppen/$gk/$gruppenid', 'cms_download_datei_".$did."', 'download', '$gk', '$gruppenid')\">Datei auswählen</span></p>";
        }
        $code .= "<p id=\"cms_download_datei_".$did."_verzeichnis\"></p></td>";
      $code .= "</tr>";
      $code .= "<tr><th></th><th>Dateiname anzeigen:</th><td>".cms_schieber_generieren('cms_download_dateiname_'.$did, $d['dateiname'])."</td>";
      $code .= "<th>Dateigröße anzeigen:</th><td>".cms_schieber_generieren('cms_download_dateigroesse_'.$did, $d['dateigroesse'])."</td></tr>";

      $code .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_download_entfernen('$did');\">Download löschen</span></td></tr>";
    $code .= "</table>";
    $anzahl++;
    $ids .= "|".$did;
  }
  $code .= "</div>";

  if (($art != 'blogintern') && ($art != 'terminintern')) {$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_download();\">+ Neuer Download</span>";}
  else {$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_internerdownload('$gk', '$gruppenid');\">+ Neuer Download</span>";}
    $code .= "<input type=\"hidden\" id=\"cms_downloads_anzahl\" name=\"cms_downloads_anzahl\" value=\"$anzahl\">";
    $code .= "<input type=\"hidden\" id=\"cms_downloads_nr\"     name=\"cms_downloads_nr\" value=\"$anzahl\">";
    $code .= "<input type=\"hidden\" id=\"cms_downloads_ids\"    name=\"cms_downloads_ids\" value=\"$ids\">";
  $code.= "</p>";


  return $code;
}
?>
